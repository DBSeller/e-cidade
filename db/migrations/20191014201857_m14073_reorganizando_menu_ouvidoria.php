<?php

use Classes\PostgresMigration;

class M14073ReorganizandoMenuOuvidoria extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228164 AND modulo = 7837;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7862 ,228164 ,5 ,7837 );
SQL
        );
    }
}
