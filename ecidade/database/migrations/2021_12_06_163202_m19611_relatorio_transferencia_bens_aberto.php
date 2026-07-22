<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19611RelatorioTransferenciaBensAberto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("insert into db_itensmenu(
                         id_item ,
                         descricao ,
                         help ,
                         funcao ,
                         itemativo ,
                         manutencao ,
                         desctec ,
                         libcliente )
                         values
                                (
                                 228599 ,
                                 'Transferência de bens em aberto' ,
                                 'Relatório Transferência de bens em aberto' ,
                                 'pat2_transferenciabensaberto.php' ,
                                 '1' ,'1' ,'Relatório Transferência de bens em aberto' ,'true' );");


        DB::statement("insert into db_menu( id_item ,
                    id_item_filho ,
                    menusequencia ,
                    modulo ) values ( 30 ,228599 ,839 ,439 );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("delete from db_menu where id_item_filho = 228599;");
        DB::statement("delete from db_itensmenu where id_item = 228599;");
    }
}
