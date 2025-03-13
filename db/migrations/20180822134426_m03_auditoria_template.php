<?php

use Classes\PostgresMigration;

class M03AuditoriaTemplate extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<STRING
/***
 *
 *  TEMPLATE de PL para auditoria de tabelas
 *
 *  Variáveis do Template
 *   . %tpl_tabela_esquema               = nome do esquema da tabela a ser auditada
 *   . %tpl_tabela_nome                  = nome da tabela a ser auditada
 *   . %tpl_tabela_oid                   = identificador da tabela no catálogo do PostgreSQL (pg_class.oid)
 *   . %tpl_data_hora                    = data e hora da criação ou última atualização
 *   . %tpl_bloco_codigo_definicao_chave = bloco de codigo da definicao da chave (xChave)
 *   . %tpl_bloco_codigo_update          = bloco de codigo a ser processado no UPDATE para montar Array com valores realmente alterados
 *   . %tpl_array_campo_nome             = definicao de array com nome dos campos da tabela a ser auditada
 *   . %tpl_array_insert_campo_valor_old = definicao de array com valores OLD dos campos para INSERT
 *   . %tpl_array_insert_campo_valor_new = definicao de array com valores NEW dos campos para INSERT
 *   . %tpl_array_delete_campo_valor_old = definicao de array com valores OLD dos campos para DELETE
 *   . %tpl_array_delete_campo_valor_new = definicao de array com valores NEW dos campos para DELETE
 *
 */


CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_template() RETURNS TEXT AS
$$
	SELECT \$SQL$
CREATE OR REPLACE FUNCTION {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}() RETURNS trigger AS
\$AUDITORIA$
/***
 * Função para registro de auditoria de INSERT/UPDATE/DELETE
 * . tabela = {%tpl_tabela_esquema}.{%tpl_tabela_nome} 
 * . pg_class.oid = {%tpl_tabela_oid}
 * . timestamp = {%tpl_data_hora}
 */
DECLARE
	xMudancas configuracoes.tp_auditoria_mudancas_campo;
	xChave    configuracoes.tp_auditoria_chave_primaria;

	tDataHora   TIMESTAMP   DEFAULT COALESCE(fc_getsession('DB_datausu')::TIMESTAMP, NOW());
	sLogin      VARCHAR(20) DEFAULT COALESCE(fc_getsession('DB_login'), 'dbseller');
	iLogsAcessa INTEGER     DEFAULT fc_getsession('DB_acessado')::INTEGER;
	iInstit     INTEGER     DEFAULT fc_getsession('DB_instit')::INTEGER;
	iUsuario    INTEGER     DEFAULT COALESCE(fc_getsession('DB_id_usuario')::INTEGER, 1);
	dData       DATE        DEFAULT tDataHora::DATE;

	rRegistro   {%tpl_tabela_esquema}.{%tpl_tabela_nome}%ROWTYPE;
	rLogsAcessa RECORD;

	aCampo      TEXT[];
	aValorOld   TEXT[];
	aValorNew   TEXT[];
BEGIN
	IF TG_OP = 'DELETE' THEN
		rRegistro := OLD;
	ELSE
		rRegistro := NEW;
	END IF;

	/* Se existir na sessão informação explícita para desabilitar log de auditoria */
	IF fc_getsession('__disable_audit__') IS NOT NULL OR /* desabiltado global */
		fc_getsession('__disable_audit_{%tpl_tabela_esquema}_{%tpl_tabela_nome}__') IS NOT NULL THEN /* ou apenas para tabela especifica */
		RETURN rRegistro;
	END IF;

	/* Caso não tenha instituição definida na sessão, busca padrão prefeitura */
	IF iInstit IS NULL THEN
		SELECT	codigo
		INTO	iInstit
		FROM	db_config
		WHERE	prefeitura IS TRUE
		ORDER	BY codigo
		LIMIT	1;

		IF iInstit IS NULL THEN
			SELECT	codigo
			INTO	iInstit
			FROM	db_config
			ORDER	BY codigo
			LIMIT	1;
			IF iInstit IS NULL THEN
				RAISE EXCEPTION 'Impossível realizar auditoria. Nenhuma instituição encontrada nesta base de dados!';
			END IF;
		END IF; 
	END IF;

	/* Caso não tenha um db_logsacessa definido na sessão, então devemos gerar um automático */
	IF iLogsAcessa IS NULL THEN
		SELECT	codsequen
		INTO	iLogsAcessa
		FROM	db_logsacessa
		WHERE	instit  = iInstit
		AND		data    = dData
		AND		hora    = to_char(tDataHora, 'HH24:MI:SS')
		AND		arquivo = 'classes/db_{%tpl_tabela_nome}_classe.php';

		IF iLogsAcessa IS NULL THEN
			SELECT	b.codmod, c.id_item
			INTO	rLogsAcessa
			FROM	db_sysarquivo a
					JOIN db_sysarqmod b ON b.codarq = a.codarq
					JOIN db_auditoria_migracao_depara_codarq_codmod_id_modulo c ON c.codarq = b.codarq AND c.codmod = b.codmod
			WHERE	nomearq = '{%tpl_tabela_nome}';

			INSERT INTO db_logsacessa (
				codsequen, data, hora, arquivo, obs, instit, auditoria, id_usuario, id_modulo, id_item
			) VALUES (
				NEXTVAL('db_logsacessa_codsequen_seq'), dData,
				to_char(tDataHora, 'HH24:MI:SS'), 'classes/db_{%tpl_tabela_nome}_classe.php',
				'LogsAcessa Automatico DML Manual', iInstit, TRUE,
				iUsuario, rLogsAcessa.codmod, rLogsAcessa.id_item
			);

			iLogsAcessa = CURRVAL('db_logsacessa_codsequen_seq');
		END IF;
	END IF;

	/* Define chave primária da tabela */
	{%tpl_bloco_codigo_definicao_chave}

	/* Verifica mudanças realizadas de acordo com operação */
	IF TG_OP = 'INSERT' THEN

		xMudancas := ROW(
			ARRAY[ {%tpl_array_campo_nome} ],
			ARRAY[ {%tpl_array_insert_campo_valor_old} ],
			ARRAY[ {%tpl_array_insert_campo_valor_new} ] ); 

	ELSIF TG_OP = 'UPDATE' THEN

		IF ROW(OLD.*) IS DISTINCT FROM ROW(NEW.*) THEN

			{%tpl_bloco_codigo_update}

		ELSE
			RETURN rRegistro;
		END IF;

		xMudancas := ROW(aCampo, aValorOld, aValorNew);
	ELSE

		xMudancas := ROW(
			ARRAY[ {%tpl_array_campo_nome} ],
			ARRAY[ {%tpl_array_delete_campo_valor_old} ],
			ARRAY[ {%tpl_array_delete_campo_valor_new} ] ); 

	END IF;

	/* Grava dados na auditoria */
	INSERT INTO db_auditoria (
		sequencial,
		esquema, 
		tabela, 
		operacao, 
		datahora_sessao, 
		usuario, 
		chave, 
		mudancas, 
		logsacessa, 
		instit
	) VALUES (
		NEXTVAL('db_acount_id_acount_seq'), -- FUTURAMENTE REMOVER
		TG_TABLE_SCHEMA, 
		TG_TABLE_NAME, 
		SUBSTR(TG_OP,1,1), 
		tDataHora, 
		sLogin,
		xChave,
		xMudancas,
		iLogsAcessa,
		iInstit 
	);

	/* Indicar que o log de acesso possui auditoria */
	UPDATE	db_logsacessa
	SET		auditoria = TRUE
	WHERE	instit    = iInstit
	AND		data      = dData
	AND		codsequen = iLogsAcessa
	AND		auditoria IS FALSE;

	RETURN rRegistro;
END;
\$AUDITORIA$
LANGUAGE plpgsql;

CREATE TRIGGER tg_auditoria_insert_delete_{%tpl_tabela_oid}
	AFTER INSERT OR DELETE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
	FOR EACH ROW EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}();

CREATE TRIGGER tg_auditoria_update_{%tpl_tabela_oid}
	AFTER UPDATE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
	FOR EACH ROW WHEN (NEW.* IS DISTINCT FROM OLD.*)
	EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}(); \$SQL$::TEXT;

$$
LANGUAGE sql;
STRING;


        $this->execute($sSql);
    }


    public function down()
    {
        $sSql = <<<STRING
/***
 *
 *  TEMPLATE de PL para auditoria de tabelas
 *
 *  Variáveis do Template
 *   . %tpl_tabela_esquema               = nome do esquema da tabela a ser auditada
 *   . %tpl_tabela_nome                  = nome da tabela a ser auditada
 *   . %tpl_bloco_codigo_definicao_chave = bloco de codigo da definicao da chave (xChave)
 *   . %tpl_bloco_codigo_update          = bloco de codigo a ser processado no UPDATE para montar Array com valores realmente alterados
 *   . %tpl_array_campo_nome             = definicao de array com nome dos campos da tabela a ser auditada
 *   . %tpl_array_insert_campo_valor_old = definicao de array com valores OLD dos campos para INSERT
 *   . %tpl_array_insert_campo_valor_new = definicao de array com valores NEW dos campos para INSERT
 *   . %tpl_array_delete_campo_valor_old = definicao de array com valores OLD dos campos para DELETE
 *   . %tpl_array_delete_campo_valor_new = definicao de array com valores NEW dos campos para DELETE
 *
 */


CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_template() RETURNS TEXT AS
$$
	SELECT \$SQL$
CREATE OR REPLACE FUNCTION {%tpl_tabela_esquema}.fc_{%tpl_tabela_nome}_auditoria() RETURNS trigger AS
\$AUDITORIA$
DECLARE
	xMudancas configuracoes.tp_auditoria_mudancas_campo;
	xChave    configuracoes.tp_auditoria_chave_primaria;

	tDataHora   TIMESTAMP   DEFAULT COALESCE(fc_getsession('DB_datausu')::TIMESTAMP, NOW());
	sLogin      VARCHAR(20) DEFAULT COALESCE(fc_getsession('DB_login'), 'dbseller');
	iLogsAcessa INTEGER     DEFAULT fc_getsession('DB_acessado')::INTEGER;
	iInstit     INTEGER     DEFAULT fc_getsession('DB_instit')::INTEGER;
	iUsuario    INTEGER     DEFAULT COALESCE(fc_getsession('DB_id_usuario')::INTEGER, 1);
	dData       DATE        DEFAULT tDataHora::DATE;

	rRegistro   {%tpl_tabela_esquema}.{%tpl_tabela_nome}%ROWTYPE;
	rLogsAcessa RECORD;

	aCampo      TEXT[];
	aValorOld   TEXT[];
	aValorNew   TEXT[];
BEGIN
	IF TG_OP = 'DELETE' THEN
		rRegistro := OLD;
	ELSE
		rRegistro := NEW;
	END IF;

	/* Se existir na sessão informação explícita para desabilitar log de auditoria */
	IF fc_getsession('__disable_audit__') IS NOT NULL OR /* desabiltado global */
		fc_getsession('__disable_audit_{%tpl_tabela_esquema}_{%tpl_tabela_nome}__') IS NOT NULL THEN /* ou apenas para tabela especifica */
		RETURN rRegistro;
	END IF;

	/* Caso não tenha instituição definida na sessão, busca padrão prefeitura */
	IF iInstit IS NULL THEN
		SELECT	codigo
		INTO	iInstit
		FROM	db_config
		WHERE	prefeitura IS TRUE
		ORDER	BY codigo
		LIMIT	1;

		IF iInstit IS NULL THEN
			SELECT	codigo
			INTO	iInstit
			FROM	db_config
			ORDER	BY codigo
			LIMIT	1;
			IF iInstit IS NULL THEN
				RAISE EXCEPTION 'Impossível realizar auditoria. Nenhuma instituição encontrada nesta base de dados!';
			END IF;
		END IF; 
	END IF;

	/* Caso não tenha um db_logsacessa definido na sessão, então devemos gerar um automático */
	IF iLogsAcessa IS NULL THEN
		SELECT	codsequen
		INTO	iLogsAcessa
		FROM	db_logsacessa
		WHERE	instit  = iInstit
		AND		data    = dData
		AND		hora    = to_char(tDataHora, 'HH24:MI:SS')
		AND		arquivo = 'classes/db_{%tpl_tabela_nome}_classe.php';

		IF iLogsAcessa IS NULL THEN
			SELECT	b.codmod, c.id_item
			INTO	rLogsAcessa
			FROM	db_sysarquivo a
					JOIN db_sysarqmod b ON b.codarq = a.codarq
					JOIN db_auditoria_migracao_depara_codarq_codmod_id_modulo c ON c.codarq = b.codarq AND c.codmod = b.codmod
			WHERE	nomearq = '{%tpl_tabela_nome}';

			INSERT INTO db_logsacessa (
				codsequen, data, hora, arquivo, obs, instit, auditoria, id_usuario, id_modulo, id_item
			) VALUES (
				NEXTVAL('db_logsacessa_codsequen_seq'), dData,
				to_char(tDataHora, 'HH24:MI:SS'), 'classes/db_{%tpl_tabela_nome}_classe.php',
				'LogsAcessa Automatico DML Manual', iInstit, TRUE,
				iUsuario, rLogsAcessa.codmod, rLogsAcessa.id_item
			);

			iLogsAcessa = CURRVAL('db_logsacessa_codsequen_seq');
		END IF;
	END IF;

	/* Define chave primária da tabela */
	{%tpl_bloco_codigo_definicao_chave}

	/* Verifica mudanças realizadas de acordo com operação */
	IF TG_OP = 'INSERT' THEN

		xMudancas := ROW(
			ARRAY[ {%tpl_array_campo_nome} ],
			ARRAY[ {%tpl_array_insert_campo_valor_old} ],
			ARRAY[ {%tpl_array_insert_campo_valor_new} ] ); 

	ELSIF TG_OP = 'UPDATE' THEN

		IF ROW(OLD.*) IS DISTINCT FROM ROW(NEW.*) THEN

			{%tpl_bloco_codigo_update}

		ELSE
			RETURN rRegistro;
		END IF;

		xMudancas := ROW(aCampo, aValorOld, aValorNew);
	ELSE

		xMudancas := ROW(
			ARRAY[ {%tpl_array_campo_nome} ],
			ARRAY[ {%tpl_array_delete_campo_valor_old} ],
			ARRAY[ {%tpl_array_delete_campo_valor_new} ] ); 

	END IF;

	/* Grava dados na auditoria */
	INSERT INTO db_auditoria (
		sequencial,
		esquema, 
		tabela, 
		operacao, 
		datahora_sessao, 
		usuario, 
		chave, 
		mudancas, 
		logsacessa, 
		instit
	) VALUES (
		NEXTVAL('db_acount_id_acount_seq'), -- FUTURAMENTE REMOVER
		TG_TABLE_SCHEMA, 
		TG_TABLE_NAME, 
		SUBSTR(TG_OP,1,1), 
		tDataHora, 
		sLogin,
		xChave,
		xMudancas,
		iLogsAcessa,
		iInstit 
	);

	/* Indicar que o log de acesso possui auditoria */
	UPDATE	db_logsacessa
	SET		auditoria = TRUE
	WHERE	instit    = iInstit
	AND		data      = dData
	AND		codsequen = iLogsAcessa
	AND		auditoria IS FALSE;

	RETURN rRegistro;
END;
\$AUDITORIA$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tg_{%tpl_tabela_nome}_auditoria
	ON {%tpl_tabela_esquema}.{%tpl_tabela_nome};
CREATE TRIGGER tg_{%tpl_tabela_nome}_auditoria
	AFTER INSERT OR UPDATE OR DELETE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
	FOR EACH ROW EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_{%tpl_tabela_nome}_auditoria(); \$SQL$::TEXT;

$$
LANGUAGE sql;
STRING;
        $this->execute($sSql);
    }


}
