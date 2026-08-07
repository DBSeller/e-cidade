<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19555Menu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228598 ,'[Ed 11] Anexo III - Dem. Receita Corrente Líquida' ,'[Ed 11] Anexo III - Dem. Receita Corrente Líquida' ,'pla2_abas_rreo.php?anexo=3' ,'1' ,'1' ,'[Ed 11] Anexo III - Dem. Receita Corrente Líquida' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8033 ,228598 ,20 ,209 );
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
delete from db_menu where id_item_filho = 228598 AND modulo = 209;
delete from db_itensmenu where id_item = 228598;
SQL
        );
    }
}
