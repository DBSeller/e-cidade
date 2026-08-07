<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20813MenuManutencaoDdrPorFonte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228675 ,'Manutenção da DDR por fonte' ,'Manutenção da DDR por fonte' ,'con4_lancamentoajusteddr001.php' ,'1' ,'1' ,'Manutenção da DDR por fonte (lançamentos em lote).' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9828 ,228675 ,8 ,209 );
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
        delete from db_itensmenu where id_item = 228675;
        delete from db_menu where id_item_filho = 228675 AND modulo = 209;
SQL
        );
    }
}
