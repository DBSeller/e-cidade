<?php

use Classes\PostgresMigration;

class M17292AlteracaoProcedenciaDiversos extends PostgresMigration
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
        $this->execute(<<<SQL
ALTER TABLE procdiver ADD COLUMN dv09_cobranca boolean NOT NULL DEFAULT FALSE;
INSERT INTO db_syscampo VALUES (1011944,'dv09_cobranca','bool','Tipo de Cobrança','f','Tipo de Cobrança',1,'f','f','f',5,'text','Tipo de Cobrança');
INSERT INTO db_sysarqcamp VALUES (374,1011944,10,0);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
DELETE FROM db_sysarqcamp WHERE codcam = 1011944;
DELETE FROM db_syscampo WHERE codcam = 1011944;
ALTER TABLE procdiver DROP COLUMN dv09_cobranca;
SQL
        );
    }
}
