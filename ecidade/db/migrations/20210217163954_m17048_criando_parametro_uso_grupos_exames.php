<?php

use Classes\PostgresMigration;

class M17048CriandoParametroUsoGruposExames extends PostgresMigration
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
                INSERT INTO db_syscampo VALUES(1012585,'la49_habilitargrupo','bool','Habilitar Grupo de Exames na Requisição','f', 'Habilitar Grupo de Exames na Requisição',1,'t','f','f',5,'text','Habilitar Grupo de Exames na Requisição');
                INSERT INTO db_sysarqcamp VALUES(2909,1012585,10,0);

                ALTER TABLE lab_parametros ADD COLUMN la49_habilitarGrupo BOOLEAN DEFAULT FALSE; 
SQL
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL
                DELETE FROM db_sysarqcamp WHERE codcam = 1012585;
                DELETE FROM db_syscampo WHERE codcam = 1012585;

                ALTER TABLE lab_parametros DROP COLUMN la49_habilitarGrupo;
SQL
        );
    }
}
