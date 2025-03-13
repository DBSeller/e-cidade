<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22026AdicionandoItemDeMenuRelatorioEmpenhoRelatorioLiquidacaoAgrupamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228764 ,' Relatório de Liquidação/Agrupamento' ,' Relatório de Liquidação/Agrupamento' ,'emp2_relempenholiquidacao001.php' ,'1' ,'1' ,' Relatório de Liquidação/Agrupamento' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5602 ,228764 ,11 ,398 );
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
        delete from db_menu where id_item_filho = 228764;
        delete from db_itensmenu where id_item = 228764;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
