<?php

use Classes\PostgresMigration;

class M16215AtividadeInternaAlvara extends PostgresMigration
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
        ALTER TABLE tabativ DROP COLUMN q07_val_ativ_int;
        ALTER TABLE tabativ ADD COLUMN q07_val_ativ_int VARCHAR(50) DEFAULT 'Sim';
SQL
        );
    }
    public function down()
    {
        $this->execute(<<<SQL
        ALTER TABLE tabativ DROP COLUMN q07_val_ativ_int;
        ALTER TABLE tabativ ADD COLUMN q07_val_ativ_int VARCHAR(50);
SQL
        );
    }
}
