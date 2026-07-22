<?php

use Classes\PostgresMigration;

class M15750AtualizacaoPls extends PostgresMigration
{

    public function up()
    {
        $this->execute("ALTER TABLE db_auditoria ADD COLUMN gatilho BOOLEAN;");
        $this->auditoriaParticiona();
        $this->auditoriaTemplate();
    }

    public function down()
    {
        $this->execute("ALTER TABLE db_auditoria DROP COLUMN gatilho;");
        $this->downAuditoriaParticiona();
        $this->downAuditoriaTemplate();
    }

    private function auditoriaParticiona()
    {
         $this->execute(<<<SQL
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particiona_inc() RETURNS trigger AS
$$
DECLARE
	sEsquema TEXT;
	sTabela  TEXT;

	sEsquemaParticao TEXT;
	sTabelaParticao  TEXT;

	sDataIni    TEXT;
	sDataFim    TEXT;
	iAno        INTEGER;
	iMes        INTEGER;

	sSQL        TEXT;
BEGIN

	sEsquema := TG_TABLE_SCHEMA;
	sTabela  := TG_TABLE_NAME;
	iAno     := extract(year  from NEW.datahora_servidor);
	iMes     := extract(month from NEW.datahora_servidor);

	sEsquemaParticao := COALESCE(fc_getsession('db_esquema_auditoria_particao'), sEsquema);
	sTabelaParticao  := sTabela || '_' ||
    to_char(iAno, 'FM0000') ||
    to_char(iMes, 'FM00') || '_' ||
    NEW.instit::TEXT;

	sSQL := FORMAT('INSERT INTO %I.%I ('
        || ' sequencial, '
        || ' esquema, '
        || ' tabela, '
        || ' operacao, '
        || ' datahora_sessao, '
        || ' datahora_servidor, '
        || ' tempo, '
        || ' usuario, '
        || ' chave, '
        || ' mudancas, '
        || ' logsacessa, '
        || ' instit, '
        || ' gatilho '
        || ') VALUES ( '
        || '$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)', sEsquemaParticao, sTabelaParticao);

	IF NEW.sequencial IS NULL THEN
		-- Usar sequence do acount para compatibilidade
		NEW.sequencial := NEXTVAL('db_acount_id_acount_seq');
	END IF;

	<<loop_insere_auditoria>>
	LOOP
		BEGIN
			EXECUTE	sSQL
			USING	NEW.sequencial, NEW.esquema, NEW.tabela, NEW.operacao, NEW.datahora_sessao, NEW.datahora_servidor,
					(clock_timestamp() - COALESCE(CAST(fc_getsession('clock_timestamp') AS TIMESTAMP WITH TIME ZONE), NOW())),
					NEW.usuario, NEW.chave, NEW.mudancas, NEW.logsacessa, NEW.instit, NEW.gatilho;

			EXIT loop_insere_auditoria;
		EXCEPTION
			WHEN undefined_table THEN
				sDataIni := iAno::TEXT || '-' || iMes::TEXT || '-01 00:00:00.000000';
				sDataFim := iAno::TEXT || '-' || iMes::TEXT || '-' || fc_ultimodiames(iAno, iMes)::TEXT || ' 23:59:59.999999';

				PERFORM configuracoes.fc_auditoria_particao_cria (
        sEsquema,
        sTabela,
        sEsquemaParticao,
        sTabelaParticao,
        'datahora_servidor BETWEEN '||quote_literal(sDataIni)||' AND '||quote_literal(sDataFim)|| ' AND instit = ' || NEW.instit::TEXT
    );
		END;
	END LOOP;

	RETURN NULL;
END;
$$
LANGUAGE plpgsql;
SQL
);
    }

    private function downAuditoriaParticiona()
    {
        $this->execute(<<<SQL
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particiona_inc() RETURNS trigger AS
$$
DECLARE
	sEsquema TEXT;
	sTabela  TEXT;

	sEsquemaParticao TEXT;
	sTabelaParticao  TEXT;

	sDataIni    TEXT;
	sDataFim    TEXT;
	iAno        INTEGER;
	iMes        INTEGER;

	sSQL        TEXT;
BEGIN

	sEsquema := TG_TABLE_SCHEMA;
	sTabela  := TG_TABLE_NAME;
	iAno     := extract(year  from NEW.datahora_servidor);
	iMes     := extract(month from NEW.datahora_servidor);

	sEsquemaParticao := COALESCE(fc_getsession('db_esquema_auditoria_particao'), sEsquema);
	sTabelaParticao  := sTabela || '_' ||
		to_char(iAno, 'FM0000') ||
		to_char(iMes, 'FM00') || '_' ||
		NEW.instit::TEXT;

	sSQL := FORMAT('INSERT INTO %I.%I ('
		|| ' sequencial, '
		|| ' esquema, '
		|| ' tabela, '
		|| ' operacao, '
		|| ' datahora_sessao, '
		|| ' datahora_servidor, '
		|| ' tempo, '
		|| ' usuario, '
		|| ' chave, '
		|| ' mudancas, '
		|| ' logsacessa, '
		|| ' instit '
		|| ') VALUES ( '
		|| '$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)', sEsquemaParticao, sTabelaParticao);

	IF NEW.sequencial IS NULL THEN
		NEW.sequencial := NEXTVAL('db_acount_id_acount_seq');
	END IF;

	<<loop_insere_auditoria>>
	LOOP
		BEGIN
			EXECUTE	sSQL
			USING	NEW.sequencial, NEW.esquema, NEW.tabela, NEW.operacao, NEW.datahora_sessao, NEW.datahora_servidor,
					(clock_timestamp() - COALESCE(CAST(fc_getsession('clock_timestamp') AS TIMESTAMP WITH TIME ZONE), NOW())),
					NEW.usuario, NEW.chave, NEW.mudancas, NEW.logsacessa, NEW.instit;

			EXIT loop_insere_auditoria;
		EXCEPTION
			WHEN undefined_table THEN
				sDataIni := iAno::TEXT || '-' || iMes::TEXT || '-01 00:00:00.000000';
				sDataFim := iAno::TEXT || '-' || iMes::TEXT || '-' || fc_ultimodiames(iAno, iMes)::TEXT || ' 23:59:59.999999';

				PERFORM configuracoes.fc_auditoria_particao_cria (
					sEsquema,
					sTabela,
					sEsquemaParticao,
					sTabelaParticao,
					'datahora_servidor BETWEEN '||quote_literal(sDataIni)||' AND '||quote_literal(sDataFim)|| ' AND instit = ' || NEW.instit::TEXT
				);
		END;
	END LOOP;

	RETURN NULL;
END;
$$
LANGUAGE plpgsql;
SQL
        );
    }

    private function auditoriaTemplate()
    {
        $this->execute(<<<SQLX

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
		BEGIN
			EXECUTE	format('
				SELECT	codsequen
				FROM	db_logsacessa_%s_%s
				WHERE	instit  = %L
				AND		data    = %L
				AND		hora    = %L
				AND		arquivo = %L',
				to_char(tDataHora, 'FMYYYYMM'), iInstit, iInstit, dData,
				to_char(tDataHora, 'HH24:MI:SS'), 'classes/db_{%tpl_tabela_nome}_classe.php')
			INTO	iLogsAcessa;
		EXCEPTION
			WHEN undefined_table THEN
				iLogsAcessa := NULL;
		END;

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

			PERFORM fc_putsession('__logsacessa_'||iLogsAcessa||'_auditoria_alterado__', 't');
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
		instit,
		gatilho
	) VALUES (
		NEXTVAL('db_acount_id_acount_seq'),
		TG_TABLE_SCHEMA,
		TG_TABLE_NAME,
		SUBSTR(TG_OP,1,1),
		tDataHora,
		sLogin,
		xChave,
		xMudancas,
		iLogsAcessa,
		iInstit,
		TRUE
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
SECURITY DEFINER
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

SQLX
        );
    }
    private function downAuditoriaTemplate()
    {
        $this->execute(<<<SQL

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
		BEGIN
			EXECUTE	format('
				SELECT	codsequen
				FROM	db_logsacessa_%s_%s
				WHERE	instit  = %L
				AND		data    = %L
				AND		hora    = %L
				AND		arquivo = %L',
				to_char(tDataHora, 'FMYYYYMM'), iInstit, iInstit, dData,
				to_char(tDataHora, 'HH24:MI:SS'), 'classes/db_{%tpl_tabela_nome}_classe.php')
			INTO	iLogsAcessa;
		EXCEPTION
			WHEN undefined_table THEN
				iLogsAcessa := NULL;
		END;

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

			PERFORM fc_putsession('__logsacessa_'||iLogsAcessa||'_auditoria_alterado__', 't');
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
		NEXTVAL('db_acount_id_acount_seq'),
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
SECURITY DEFINER
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

SQL
        );
    }
}
