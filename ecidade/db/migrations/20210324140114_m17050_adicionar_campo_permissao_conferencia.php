<?php

use Classes\PostgresMigration;

class M17050AdicionarCampoPermissaoConferencia extends PostgresMigration
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
        $this->execute(
            <<<SQL
                INSERT INTO db_syscampo VALUES(1013139,'la06_permitidoconferencia','bool','Permitido conferencia','f', 'Permitido conferencia',1,'f','f','f',5,'text','Permitido conferencia');
                INSERT INTO db_sysarqcamp VALUES(2772,1013139,12,0);

                ALTER TABLE lab_labresp ADD COLUMN la06_permitidoconferencia BOOLEAN DEFAULT FALSE;
SQL
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL
                DELETE FROM db_sysarqcamp WHERE codcam = 1013139;
                DELETE FROM db_syscampo WHERE codcam = 1013139;

                ALTER TABLE lab_labresp DROP COLUMN la06_permitidoconferencia;
SQL
        );
    }
}
