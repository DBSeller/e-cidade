<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21528AdicaoMenuSagres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            -- alterar o true pra false: LEMBRETINHOOOOOO
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228739 ,'Gerar SAGRES' ,'Relatório Sagres' ,'pes3_gerarsagres001.php' ,'1' ,'1' ,' ' ,'false' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5110 ,228739 ,8 ,952 );
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            delete from db_itensmenu where id_item = 228739;
            delete from db_menu where id_item = 5110 and id_item_filho = 228739;    
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}