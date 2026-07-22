<?php

use Classes\PostgresMigration;

class M11AuditoriaGatilhoDdl extends PostgresMigration
{

    public function up()
    {

        $this->execute("	CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_gatilho_ddl()
		RETURNS event_trigger AS
		$$
		BEGIN
			-- Somente cria/atualiza se triggers instaladas
			IF NOT EXISTS (SELECT 1 FROM pg_trigger WHERE tgname LIKE 'tg_%_auditoria') THEN
				RETURN;
			END IF;

			PERFORM	configuracoes.fc_auditoria_cria_funcao(obj.object_identity::text)
			FROM	pg_event_trigger_ddl_commands() AS obj;
		END;
		$$
		LANGUAGE plpgsql;");

        $sSql = <<<STRING
-- Prevenir erro de sintaxe na 9.2 por nao existir ainda EVENT TRIGGERS
SET check_function_bodies TO false;

DO
$$
BEGIN
	-- Cria apenas apartir do PostgreSQL 9.5
	IF current_setting('server_version_num')::INTEGER >= 90500 THEN
	

		DROP EVENT TRIGGER IF EXISTS evtg_auditoria_gatilho_ddl;
		CREATE EVENT TRIGGER evtg_auditoria_gatilho_ddl
			ON ddl_command_end
			WHEN tag IN ('CREATE TABLE', 'ALTER TABLE')
			EXECUTE PROCEDURE configuracoes.fc_auditoria_gatilho_ddl();
	END IF;
END;
$$ LANGUAGE plpgsql;


STRING;

        $this->execute($sSql);


    }


    public function down()
    {

        $sSql = <<<STRING
             DROP FUNCTION IF EXISTS  configuracoes.fc_auditoria_gatilho_ddl() cascade;
STRING;



        $this->execute($sSql);
    }
}
