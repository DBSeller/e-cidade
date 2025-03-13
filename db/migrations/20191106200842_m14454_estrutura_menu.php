<?php

use Classes\PostgresMigration;

class M14454EstruturaMenu extends PostgresMigration
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
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228178 ,'Emissão/Reemissão de resultados' ,'Emissão de lotes de resultados' ,'' ,'1' ,'1' ,'Emissão de lotes de resultados, podendo escolher imprimir tanto em formato de texto em uma impressora matricial quanto em PDF.' ,'true' );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228166 ,'Lote' ,'Emitir resultado de exames em lote' ,'lab4_emissaoresultadolote.php' ,'1' ,'1' ,'Rotina que emite os resultados do exame em lote de acordo com os filtros selecionados.' ,'true' );
            update db_itensmenu set id_item = 8349 , descricao = 'Individual' , help = 'Emissão/Reemissão de Resultado' , funcao = 'lab4_emissaoresult001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Emissão/Reemissão de Resultado' , libcliente = 'true' where id_item = 8349;
            delete from db_menu where id_item_filho = 8349 AND modulo = 8167;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8355 ,228178 ,6 ,8167 );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228178 ,8349 ,1 ,8167 );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228178 ,228166 ,2 ,8167 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
               delete from db_menu where id_item_filho = 228178 AND modulo = 8167;
               delete from db_menu where id_item_filho = 8349 AND modulo = 8167;
               delete from db_menu where id_item_filho = 228166 AND modulo = 8167;
               update db_itensmenu set descricao = 'Emissão/Reemissão de Resultado' where id_item = 8349;
               insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8355 ,8349 ,7 ,8167 );
               delete from db_itensmenu where id_item in(228178, 228166);
SQL
        );
    }
}
