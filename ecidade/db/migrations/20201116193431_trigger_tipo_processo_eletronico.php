<?php

use Classes\PostgresMigration;

class TriggerTipoProcessoEletronico extends PostgresMigration
{

    public function up(){
        $sql = <<<SQL
CREATE OR REPLACE
FUNCTION protocolo.fc_historico_tipo_processo()
RETURNS TRIGGER LANGUAGE plpgsql AS $$
BEGIN

 IF(fc_getsession('DB_id_usuario') IS NULL OR fc_getsession('DB_id_usuario')='') THEN
      RAISE  'DB_id_usuario USUÁRIO NÃO INFORMADO';
      RETURN NULL;
 END IF;

 IF(fc_getsession('DB_instit') IS NULL OR fc_getsession('DB_instit')='') THEN
      RAISE 'DB_instit INSTITUIÇÃO NÃO INFORMADO';
      RETURN NULL;
 END IF;

 IF(fc_getsession('DB_coddepto') IS NULL OR fc_getsession('DB_coddepto')='') THEN
      RAISE  'DB_coddepto DEPARTAMENTO NÃO INFORMADO';
      RETURN NULL;
 END IF;

 IF( TG_OP = 'INSERT') THEN

				INSERT
					INTO
					protocolo.historico_tipo_processo (
						  p112_usuario
						, p112_instituicao
						, p112_departamento
						, p112_tipoprocesso
						, p112_codigoprocesso
						, p112_data_registro
					)
				VALUES (
					fc_getsession('DB_id_usuario')::int8
					, fc_getsession('DB_instit')::int8
					, fc_getsession('DB_coddepto')::int8
					, NEW.p58_tipoprocesso
					, NEW.p58_codproc
					, CURRENT_TIMESTAMP
				);

			RETURN NEW;

		ELSEIF (TG_OP = 'UPDATE') THEN

			IF(OLD.p58_tipoprocesso <> NEW.p58_tipoprocesso ) THEN

					INSERT
						INTO
						protocolo.historico_tipo_processo (
							  p112_usuario
							, p112_instituicao
							, p112_departamento
							, p112_tipoprocesso
							, p112_codigoprocesso
							, p112_data_registro
						)
					VALUES(
						  fc_getsession('DB_id_usuario')::int8
						, fc_getsession('DB_instit')::int8
						, fc_getsession('DB_coddepto')::int8
						, new.p58_tipoprocesso
						, new.p58_codproc
						, CURRENT_TIMESTAMP
					);

				RETURN NEW;

				END IF;
	    END IF;

		RETURN NULL;
END;
$$;

CREATE TRIGGER fc_historico_tipo_processo AFTER
INSERT
	OR
UPDATE
	ON
	protocolo.protprocesso FOR EACH ROW EXECUTE PROCEDURE fc_historico_tipo_processo();
SQL;

        $this->execute($sql);
    }

    public function down(){
        $sql = <<<SQL
        DROP TRIGGER  IF EXISTS  fc_historico_tipo_processo ON protocolo.protprocesso;
        DROP FUNCTION IF EXISTS  protocolo.fc_historico_tipo_processo;
SQL;

        $this->execute($sql);
    }

}
