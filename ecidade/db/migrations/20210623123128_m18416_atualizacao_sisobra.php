<?php

use Classes\PostgresMigration;

class M18416AtualizacaoSisobra extends PostgresMigration
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
        INSERT INTO
            obrasenvioregalvara (ob31_obrasenvioreg, ob31_codalvara)
        SELECT
            ob17_codobrasenvioreg,
            ob04_alvara
        FROM
            obrasenvioreg
            INNER JOIN obrasalvara ON ob17_codobra = ob04_codobra;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        DELETE FROM obrasenvioregalvara;
SQL
        );
    }
}
