<?php

use Classes\PostgresMigration;

class M16386AdicionarColunaIdadeGestacionalTriagem extends PostgresMigration
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
        $this->execute("
            INSERT INTO db_syscampo VALUES(1011802,'s152_idadegestacional','int4','Idade gestacional em semanas','0', 'Idade Gestacional (Semanas)',10,'t','f','f',1,'text','Idade Gestacional (Semanas)');
            INSERT INTO db_sysarqcamp VALUES(3043,1011802,23,0);

            ALTER TABLE sau_triagemavulsa ADD COLUMN s152_idadegestacional INTEGER;
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_sysarqcamp WHERE codcam = 1011802;
            DELETE FROM db_syscampo WHERE codcam = 1011802;

            ALTER TABLE sau_triagemavulsa DROP COLUMN s152_idadegestacional;
        ");
    }
}
