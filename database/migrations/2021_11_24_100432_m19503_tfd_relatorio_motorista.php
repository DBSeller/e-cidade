<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M19503TfdRelatorioMotorista extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228596 ,'Viagens por Motorista' ,'Relatório de viagens por motorista' ,'tfd2_viagensmotorista.php' ,'1' ,'1' ,'Relatório de viagens por motorista' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8324 ,228596 ,7 ,8322 );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("delete from db_menu where id_item_filho = 228596;");
        DB::statement("delete from db_itensmenu where id_item = 228596;");
    }
}
