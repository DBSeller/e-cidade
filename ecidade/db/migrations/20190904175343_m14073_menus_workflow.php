<?php

use Classes\PostgresMigration;

class M14073MenusWorkflow extends PostgresMigration
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

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228157 ,'workflow' ,'Fluxos de workflow' ,'con1_workflow004.php' ,'1' ,'1' ,'Conjunto de ações do workflow do modulo de configuração' ,'true' );
            delete from db_menu where id_item_filho = 228157 AND modulo = 1;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228157 ,284 ,1 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            delete from db_menu where id_item_filho in (228157) AND modulo = 1;
            delete from db_itensmenu where id_item in (228157);

SQL
        );
    }
}
