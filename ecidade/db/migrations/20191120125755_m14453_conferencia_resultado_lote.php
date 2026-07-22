<?php

use Classes\PostgresMigration;

class M14453ConferenciaResultadoLote extends PostgresMigration
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
        
        $this->createDicionarioDados();
    }    
         
    public function down()
    {    
        
        $this->dropDicionarioDados();
    }    
         
    public function createDicionarioDados()
    {    
        
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228172 ,'Lote' ,'Lote' ,'lab4_conferenciaresultadolote001.php' ,'1' ,'1' ,'Lote' ,'false' );
            delete from db_menu where id_item_filho = 228172 AND modulo = 8167;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8348 ,228172 ,2 ,8167 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228173 ,'Individual' ,'Individual' ,'lab4_confresult001.php' ,'1' ,'1' ,'Individual' ,'true' );
            delete from db_menu where id_item_filho = 228173 AND modulo = 8167;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8348 ,228173 ,1 ,8167 );
            update db_itensmenu set funcao = '' where id_item = 8348;
SQL;
        $this->execute($sql);
    }

    public function dropDicionarioDados()
    {

        $sql = <<<SQL
            delete from db_itensmenu where id_item in (228172, 228173);
            delete from db_menu where id_item_filho = 228172 AND modulo = 8167;
            delete from db_menu where id_item_filho = 228173 AND modulo = 8167;
            update db_itensmenu set funcao = 'lab4_confresult001.php' where id_item = 8348;
SQL;
        $this->execute($sql);
    }         
    
}
