<?php

use Classes\PostgresMigration;

class M16814AjustarCpfTabelaCgsUnd extends PostgresMigration
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
    public function change()
    {
        $this->execute("update cgs_und set z01_v_cgccpf = replace(replace(z01_v_cgccpf,'-',''),'.','') where (z01_v_cgccpf like '%.%' or z01_v_cgccpf like '%-%');");
    }
}
