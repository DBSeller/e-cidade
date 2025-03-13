<?php

use Classes\PostgresMigration;

class M13003AuditoriaParticiona extends PostgresMigration
{
    public function up()
    {
	$this->execute("
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particao_cria (
	sEsquema         TEXT,
	sTabela          TEXT,
	sEsquemaParticao TEXT,
	sTabelaParticao  TEXT,
	sCheck           TEXT
) RETURNS void AS
$$
DECLARE
	sSQL TEXT;
BEGIN

	IF fc_clone_table(sEsquema||'.'||sTabela, sEsquemaParticao||'.'||sTabelaParticao, null, true) IS TRUE THEN

		sSQL := 'ALTER TABLE '||sEsquemaParticao||'.'||sTabelaParticao;
		sSQL := sSQL || ' ADD CONSTRAINT '||sTabelaParticao||'_datahora_servidor_ck';
		sSQL := sSQL || ' CHECK ('||sCheck||');';

		EXECUTE sSQL;

	END IF;

	RETURN;
END;
$$
LANGUAGE plpgsql;

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
		-- Usar sequence do acount para compatibilidade
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

DROP TRIGGER IF EXISTS tg_auditoria_particiona_inc ON configuracoes.db_auditoria;
CREATE TRIGGER tg_auditoria_particiona_inc BEFORE INSERT ON configuracoes.db_auditoria
	FOR EACH ROW EXECUTE PROCEDURE configuracoes.fc_auditoria_particiona_inc();

CREATE OR REPLACE FUNCTION configuracoes.fc_logsacessa_particiona_inc() RETURNS trigger AS
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
	iInstit     INTEGER;

	sSQL        TEXT;
BEGIN

	sEsquema := TG_TABLE_SCHEMA;
	sTabela  := TG_TABLE_NAME;
	iAno     := extract(year  from NEW.data);
	iMes     := extract(month from NEW.data);
	iInstit  := coalesce(NEW.instit, 0);

	sEsquemaParticao := COALESCE(fc_getsession('db_esquema_auditoria_particao'), sEsquema);
	sTabelaParticao  := sTabela || '_' ||
		to_char(iAno, 'FM0000') ||
		to_char(iMes, 'FM00') || '_' ||
		iInstit::TEXT;

	sSQL := FORMAT('INSERT INTO %I.%I ('
		|| ' codsequen, '
		|| ' ip, '
		|| ' data, '
		|| ' hora, '
		|| ' arquivo, '
		|| ' obs, '
		|| ' id_usuario, '
		|| ' id_modulo, '
		|| ' id_item, '
		|| ' coddepto, '
		|| ' instit, '
		|| ' auditoria '
		|| ') VALUES ( '
		|| '$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)', sEsquemaParticao, sTabelaParticao);

	IF NEW.codsequen IS NULL THEN
		NEW.codsequen := NEXTVAL('db_logsacessa_codsequen_seq');
	END IF;

	<<loop_insere_logsacessa>>
	LOOP
		BEGIN
			EXECUTE	sSQL
			USING	NEW.codsequen, NEW.ip, NEW.data, NEW.hora, NEW.arquivo, NEW.obs, NEW.id_usuario,
					NEW.id_modulo, NEW.id_item, NEW.coddepto, iInstit, NEW.auditoria;
			
			EXIT loop_insere_logsacessa;
		EXCEPTION
			WHEN undefined_table THEN
				sDataIni := iAno::TEXT || '-' || iMes::TEXT || '-01';
				sDataFim := iAno::TEXT || '-' || iMes::TEXT || '-' || fc_ultimodiames(iAno, iMes)::TEXT;

				PERFORM configuracoes.fc_auditoria_particao_cria (
					sEsquema,
					sTabela,
					sEsquemaParticao,
					sTabelaParticao,
					'data BETWEEN '||quote_literal(sDataIni)||' AND '||quote_literal(sDataFim)|| ' AND instit = ' || iInstit::TEXT
				);
		END;
	END LOOP;

	RETURN NULL;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tg_logsacessa_particiona_inc ON configuracoes.db_logsacessa;
CREATE TRIGGER tg_logsacessa_particiona_inc BEFORE INSERT ON configuracoes.db_logsacessa
	FOR EACH ROW EXECUTE PROCEDURE configuracoes.fc_logsacessa_particiona_inc();
        ");
    }

    public function down() {}
}
