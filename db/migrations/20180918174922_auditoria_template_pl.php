<?php

use Classes\PostgresMigration;

class AuditoriaTemplatePl extends PostgresMigration
{

    public function up()
    {
        $this->createFunction();
    }

    public function down()
    {

    }

    private function createFunction()
    {
        $this->execute(
            <<<SQL_UP


CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_template() RETURNS TEXT AS
$$
	SELECT \$SQL$
CREATE OR REPLACE FUNCTION {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}() RETURNS trigger AS
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

	
	{%tpl_bloco_codigo_definicao_chave}

	
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

	
	IF fc_getsession('__logsacessa_'||iLogsAcessa||'_auditoria_alterado__') IS NULL THEN
	
		UPDATE	db_logsacessa
		SET		auditoria = TRUE
		WHERE	instit    = iInstit
		AND		data      = dData
		AND		codsequen = iLogsAcessa
		AND		auditoria IS FALSE;

		
		PERFORM fc_putsession('__logsacessa_'||iLogsAcessa||'_auditoria_alterado__', 't');
	END IF;

	RETURN rRegistro;
END;
\$AUDITORIA$
LANGUAGE plpgsql;

CREATE TRIGGER tg_auditoria_insert_delete_{%tpl_tabela_oid}
	AFTER INSERT OR DELETE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
	FOR EACH ROW
		WHEN	(fc_getsession('__disable_audit__') IS NULL
		AND		 fc_getsession('__disable_audit_{%tpl_tabela_esquema}_{%tpl_tabela_nome}__') IS NULL)
	EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}();

CREATE TRIGGER tg_auditoria_update_{%tpl_tabela_oid}
	AFTER UPDATE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
	FOR EACH ROW
		WHEN	(NEW.* IS DISTINCT FROM OLD.*
		AND		 fc_getsession('__disable_audit__') IS NULL
		AND		 fc_getsession('__disable_audit_{%tpl_tabela_esquema}_{%tpl_tabela_nome}__') IS NULL)
	EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_auditoria_tabela_{%tpl_tabela_oid}();
	
\$SQL$::TEXT;

$$
LANGUAGE sql;

SQL_UP
        );
    }
}
