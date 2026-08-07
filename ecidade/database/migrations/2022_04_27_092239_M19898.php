<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19898 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228652 ,'Relatório de Infraestrutura da Escola' ,'Relatório de Infraestrutura da Escola' ,'edu2_relatorioinfradaescola001.php' ,'1' ,'1' ,'Relatório de Infraestrutura da Escola' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4964 ,228652 ,24 ,7159 );
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
            delete from db_menu where id_item_filho = 228652;
            delete from db_itensmenu where id_item = 228652;

SQL
        );
    }
}
