<?php

use Classes\PostgresMigration;

class M18041AjustaTabelaOperacoestef extends PostgresMigration
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
        $this->execute(<<<SQL
            insert into operacoestef (k195_descricao, k195_codigoperacao) values ('DÉBITO', 101), ('CRÉDITO À VISTA', 112), ('CRÉDITO PARCELADO', 113);
SQL
        );
    }
}
