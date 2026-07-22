<?php

use Classes\PostgresMigration;

class M13280AuditoriaGatilhoDdl  extends PostgresMigration
{
    public function up()
    {
	
		$stringSQL = <<<FUNCOESAUDITORIA
-- Criar apenas apartir do PostgreSQL 9.5
SELECT	CASE
			WHEN current_setting('server_version_num')::INTEGER >= 90500 THEN
				fc_executa_ddl($$
					CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_gatilho_ddl()
					RETURNS event_trigger AS
					\$_\$
					BEGIN
						-- Somente cria/atualiza se triggers instaladas
						IF NOT EXISTS (SELECT 1 FROM pg_trigger WHERE tgname ~ '^tg_auditoria_(insert_delete|update)_[0-9]') THEN
							RETURN;
						END IF;

						PERFORM	configuracoes.fc_auditoria_cria_funcao(obj.object_identity::text)
						FROM	pg_event_trigger_ddl_commands() AS obj;
					END;
					\$_\$
					SECURITY DEFINER
					LANGUAGE plpgsql;

					DROP EVENT TRIGGER IF EXISTS evtg_auditoria_gatilho_ddl;
					CREATE EVENT TRIGGER evtg_auditoria_gatilho_ddl
						ON ddl_command_end
						WHEN tag IN ('CREATE TABLE', 'ALTER TABLE')
						EXECUTE PROCEDURE configuracoes.fc_auditoria_gatilho_ddl();$$
				)
			ELSE
				NULL
		END;
FUNCOESAUDITORIA;
		$this->execute($stringSQL);
    }

    public function down() {}
}
