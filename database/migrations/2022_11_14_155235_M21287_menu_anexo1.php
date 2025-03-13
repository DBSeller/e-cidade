<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21287MenuAnexo1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228810 ,'Anexo I - Balanço Orçamentário' ,'Anexo I - Balanço Orçamentário ' ,'pla2_abas_rreo.php?anexo=1' ,'1' ,'1' ,'Anexo I - Balanço Orçamentário ' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values (228634, 228810, 1, 209);
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
delete from db_menu where id_item_filho = 228810 AND modulo = 209;
delete from db_itensmenu where id_item = 228810;
SQL
        );
    }
}
