<?php

use Classes\PostgresMigration;

/**
 * Class M9919CampoSiConfiInstituicao
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M9919CampoSiConfiInstituicao extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sSql = <<<SQL
          ALTER TABLE db_config ADD COLUMN db21_codsiconfi VARCHAR(15) DEFAULT NULL;
          INSERT INTO db_syscampo VALUES(1009629, 'db21_codsiconfi', 'varchar(15)', 'Código SICONFI', '', 'Código SICONFI', 15, 't', 't', 'f', 0, 'text', 'Código SICONFI');
          INSERT INTO db_sysarqcamp VALUES(83,1009629,47,0);
SQL;

        $this->execute($sSql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sSql = <<<SQL
          ALTER TABLE db_config DROP COLUMN db21_codsiconfi;
          DELETE FROM db_sysarqcamp WHERE codcam = 1009629;
          DELETE FROM db_syscampo WHERE codcam = 1009629;
SQL;

        $this->execute($sSql);
    }
}
