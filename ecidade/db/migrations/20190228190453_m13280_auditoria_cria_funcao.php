<?php

use Classes\PostgresMigration;

class M13280AuditoriaCriaFuncao  extends PostgresMigration
{
    public function up()
    {
	
		$stringSQL = <<<FUNCOESAUDITORIA
/* Apenas esqueleto por dependencia na funcao fc_auditoria_cria_funcao.
   Conteudo completo no 05_auditoria_remove_funcao.sql */
SELECT
	CASE WHEN NOT EXISTS (
		SELECT	1
		FROM	pg_proc p
				JOIN pg_namespace n ON n.oid = p.pronamespace
		AND		p.proname = 'fc_auditoria_remove_funcao'
		AND		n.nspname = 'configuracoes') THEN
		fc_executa_ddl('
		CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
		$$
		BEGIN
			RETURN;
		END;
		$$
		LANGUAGE plpgsql;')
	END;



CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_cria_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
	sEsquema	TEXT;
	sTabela		TEXT;
	aTabela		TEXT[];

	aProcura	TEXT[];
	aSubstitui	TEXT[];

	sTemplate	TEXT;
	sColunas	TEXT;
	sNulls		TEXT;
	sValores	TEXT;

	sBlocoUpdate	TEXT;
	sBlocoChave		TEXT;

	rTabela		RECORD;
BEGIN

	-- Separa Esquema e Tabela
	IF position('.' in $1) > 0 THEN
		aTabela  := string_to_array($1, '.');
		sEsquema := aTabela[1];
		sTabela  := aTabela[2];
	ELSE
		sEsquema := 'public';
		sTabela  := $1;
	END IF;

	FOR rTabela IN
		SELECT	*
		FROM	configuracoes.vw_auditoria_lista_tabelas
		WHERE	esquema LIKE sEsquema
		AND		nome    LIKE sTabela
	LOOP
		aProcura   := '{}';
		aSubstitui := '{}';

		-- Variaveis para macro-substituicao
		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_data_hora}');
		aSubstitui := ARRAY_APPEND(aSubstitui, now()::TEXT);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_tabela_oid}');
		aSubstitui := ARRAY_APPEND(aSubstitui, rTabela.oid::TEXT);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_tabela_esquema}');
		aSubstitui := ARRAY_APPEND(aSubstitui, rTabela.esquema::TEXT);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_tabela_nome}');
		aSubstitui := ARRAY_APPEND(aSubstitui, rTabela.nome::TEXT);

		-- Carrega template de PL de auditoria
		sTemplate := configuracoes.fc_auditoria_template();

		-- Monta Bloco de Codigo da Chave Primaria, se existir
		SELECT	COALESCE(
					'xChave := ROW( ARRAY['||
					string_agg(quote_literal(attname::TEXT), ', ')||'], ARRAY['||
					string_agg('rRegistro.'::TEXT||attname::TEXT||'::TEXT', ', ')||'] );', '')
		INTO	sBlocoChave
		FROM	pg_class a
				INNER JOIN pg_constraint b   ON b.conrelid = a.oid
				INNER JOIN pg_namespace c    ON c.oid      = a.relnamespace
				INNER JOIN pg_attribute t    ON t.attrelid = b.conrelid
											AND t.attnum   = ANY(b.conkey)
		WHERE	a.oid = rTabela.oid
		AND		b.contype = 'p';

		IF sBlocoChave = 'xChave := ROW( ARRAY[], ARRAY[] );' THEN
			sBlocoChave := '';
		END IF;

		-- Colunas da tabela e Bloco de Codigo para UPDATE
		-- @TODO: Usar "encode" para colunas do tipo BYTEA
		SELECT	string_agg(quote_literal(column_name::TEXT), ', '),
				string_agg('NULL'::TEXT, ', '),
				string_agg('rRegistro.'::TEXT||column_name::TEXT||'::TEXT', ', '),

				string_agg(
					'	IF OLD.'||column_name||' IS DISTINCT FROM NEW.'||column_name||' THEN
							aCampo    := ARRAY_APPEND(aCampo,    '||quote_literal(column_name)||');
							aValorOld := ARRAY_APPEND(aValorOld, OLD.'||column_name||'::TEXT);
							aValorNew := ARRAY_APPEND(aValorNew, NEW.'||column_name||'::TEXT);
						END IF;', '\n\n')
		INTO	sColunas,
				sNulls,
				sValores,
				sBlocoUpdate
		FROM	information_schema.columns
		WHERE	table_schema = rTabela.esquema
		AND		table_name   = rTabela.nome;

		-- Variaveis para macro-substituicao
		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_campo_nome}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sColunas);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_insert_campo_valor_old}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sNulls);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_insert_campo_valor_new}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sValores);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_delete_campo_valor_old}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sValores);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_delete_campo_valor_new}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sNulls);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_bloco_codigo_definicao_chave}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sBlocoChave);

		aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_bloco_codigo_update}');
		aSubstitui := ARRAY_APPEND(aSubstitui, sBlocoUpdate);

		-- Macro-substituicao das variaveis dentro do bloco de codigo do template
		sTemplate := fc_replace_multi(sTemplate, aProcura, aSubstitui);

		-- Remove a funcao caso seja necessario
		IF EXISTS (	SELECT	1
					FROM	pg_trigger
					WHERE	tgrelid = rTabela.oid
					AND		tgname ~ ('^tg_auditoria_(insert_delete|update)_'||rTabela.oid::text) ) THEN
			PERFORM configuracoes.fc_auditoria_remove_funcao($1);
		END IF;

		-- Execucao do codigo do template
		RAISE INFO 'Criando/atualizando funcao e trigger de auditoria na tabela %.%',
			rTabela.esquema, rTabela.nome;
		EXECUTE sTemplate;
	END LOOP;

	RETURN;
END;
$$
LANGUAGE plpgsql;
FUNCOESAUDITORIA;
		$this->execute($stringSQL);
    }

    public function down() {}
}
