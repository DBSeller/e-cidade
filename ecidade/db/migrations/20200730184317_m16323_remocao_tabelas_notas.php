<?php

use Classes\PostgresMigration;

class M16323RemocaoTabelasNotas extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upRemoveTabelasNotas();
    }

    public function upRemoveTabelasNotas() {
        $this->execute(
        <<<SQL

SET search_path TO nfse;

DROP TABLE IF EXISTS nfse.competencias CASCADE;
DROP SEQUENCE IF EXISTS nfse.competencias_id_seq CASCADE;

DROP TABLE IF EXISTS nfse.dms_nota_servicos CASCADE;

DROP SEQUENCE IF EXISTS nfse.dms_nota_id_seq CASCADE;
DROP TABLE IF EXISTS nfse.dms_nota CASCADE;

DROP TABLE IF EXISTS nfse.notas_servicos CASCADE;

DROP SEQUENCE IF EXISTS nfse.notas_id_seq CASCADE;
DROP TABLE IF EXISTS nfse.notas CASCADE;

DROP TABLE IF EXISTS nfse.guias_numpre CASCADE;
DROP SEQUENCE IF EXISTS nfse.guias_numpre_id_seq;

DROP TABLE IF EXISTS nfse.guias_notas CASCADE;

DROP TABLE IF EXISTS nfse.guias_dms CASCADE;

DROP SEQUENCE IF EXISTS nfse.dms_id_seq CASCADE;
DROP TABLE IF EXISTS nfse.dms CASCADE;

DROP SEQUENCE IF EXISTS nfse.guias_id_seq CASCADE;
DROP TABLE IF EXISTS nfse.guias CASCADE;

DROP TABLE IF EXISTS nfse.importacao_dms_nota CASCADE;
DROP SEQUENCE IF EXISTS nfse.importacao_dms_nota_id_seq;

DROP TABLE IF EXISTS nfse.importacao_dms CASCADE;
DROP SEQUENCE IF EXISTS nfse.importacao_dms_id_seq;

DROP TABLE IF EXISTS nfse.importacao_desif CASCADE;
DROP SEQUENCE IF EXISTS nfse.importacao_desif_id_seq;

DROP TABLE IF EXISTS nfse.usuarios_contribuintes CASCADE;
DROP SEQUENCE IF EXISTS nfse.usuarios_contribuintes_id_seq;

SQL
        );
    }

    public function down()
    {
    }

}
