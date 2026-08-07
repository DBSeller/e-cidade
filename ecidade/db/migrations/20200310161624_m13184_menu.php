<?php

use Classes\PostgresMigration;

class M13184Menu extends PostgresMigration
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
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228242 ,'Alteração de Parcelamento' ,'Rotina para alterar parcelamento do foro para parcelamento de dívida' ,'arr4_alteracaoparcelamento001.php' ,'1' ,'1' ,'Rotina para alterar parcelamento do foro para parcelamento de dívida' ,'true' );
            delete from db_menu where id_item_filho = 228242 AND modulo = 1985522;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228242 ,512 ,1985522 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228242;
            delete from db_itensmenu where id_item = 228242;
SQL
        );
    }
}
