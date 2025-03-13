<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21100CriacaoMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228680 ,'Manutenção dados bancários do servidor' ,'Manutenção dados bancários do servidor' ,'pes4_manutencaoDadosBancariosServidor001.php' ,'1' ,'1' ,'Manutenção dados bancários do servidor' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228680 ,11 ,952 );
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
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228680;
delete from db_itensmenu where id_item = 228680;
SQL
        );
    }
}
