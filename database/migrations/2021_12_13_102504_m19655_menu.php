<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19655Menu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228602 ,'[Ed 11] Anexo I - Demons. da Despesa com Pessoal' ,'[Ed 11] Anexo I - Demons. da Despesa com Pessoal' ,'pla2_abas_rgf.php?anexo=1' ,'1' ,'1' ,'[Ed 11] Anexo I - Demonstrativo da Despesa com Pessoal' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8113 ,228602 ,10 ,209 );
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
delete from db_menu where id_item_filho = 228602 AND modulo = 209;
delete from db_itensmenu where id_item = 228602;
SQL
        );
    }
}
