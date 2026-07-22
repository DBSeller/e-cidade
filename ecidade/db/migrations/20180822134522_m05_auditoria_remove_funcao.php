<?php

use Classes\PostgresMigration;

class M05AuditoriaRemoveFuncao extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<STRING
-- Função para remover trigger de auditoria das tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
	sEsquema	TEXT;
	sTabela		TEXT;
	aTabela		TEXT[];

	sSQL		TEXT;

	rTabela		RECORD;
	rTrigger	RECORD;
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
		RAISE INFO 'Removendo funcao e trigger de auditoria da tabela %.%',
			rTabela.esquema, rTabela.nome;

		-- Apaga Funcao e Trigger(s) de Auditoria
		sSQL := 'DROP FUNCTION IF EXISTS '||rTabela.esquema||'.fc_auditoria_tabela_'||rTabela.oid::text||'() CASCADE;';
		EXECUTE sSQL;

	END LOOP;

	RETURN;
END;
$$
LANGUAGE plpgsql;

-- Wrapper para remover funcoes para todas tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao() RETURNS VOID AS
$$
	SELECT configuracoes.fc_auditoria_remove_funcao('%.%');
$$
LANGUAGE sql;
STRING;


        $this->execute($sSql);
    }


    public function down()
    {
        $sSql = <<<STRING
-- Função para remover trigger de auditoria das tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
	sEsquema	TEXT;
	sTabela		TEXT;
	aTabela		TEXT[];

	sSQL		TEXT;

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
		SELECT	esquema,
				nome
		FROM	configuracoes.vw_auditoria_lista_tabelas
		WHERE	esquema LIKE sEsquema
		AND		nome    LIKE sTabela
	LOOP
		RAISE INFO 'Removendo funcao e trigger de auditoria da tabela %.%',
			rTabela.esquema, rTabela.nome;

		-- Apaga Trigger de Auditoria
		sSQL := 'DROP TRIGGER IF EXISTS tg_'||rTabela.nome||'_auditoria ON '||rTabela.esquema||'.'||rTabela.nome||';';
		EXECUTE sSQL;

		-- Apaga Funcao de Auditoria
		sSQL := 'DROP FUNCTION IF EXISTS '||rTabela.esquema||'.fc_'||rTabela.nome||'_auditoria();';
		EXECUTE sSQL;

	END LOOP;

	RETURN;
END;
$$
LANGUAGE plpgsql;

-- Wrapper para remover funcoes para todas tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao() RETURNS VOID AS
$$
	SELECT configuracoes.fc_auditoria_remove_funcao('%.%');
$$
LANGUAGE sql;
STRING;


        $this->execute($sSql);
    }
}
