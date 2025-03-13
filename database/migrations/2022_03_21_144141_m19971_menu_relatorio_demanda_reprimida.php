<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19971MenuRelatorioDemandaReprimida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228638 ,'Controle Demanda Reprimida' ,'Relatório de Controle de Demanda Reprimida' ,'far2_controledemandareprimida.php' ,'1' ,'1' ,'Relatório para controle de medicamentos com demanda reprimida.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7071 ,228638 ,6 ,6877 );
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('db_menu')->where('id_item_filho', 228638)->delete();
        DB::table('db_itensmenu')->where('id_item', 228638)->delete();
    }
}
