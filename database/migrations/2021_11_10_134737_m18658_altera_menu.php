<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18658AlteraMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
update db_itensmenu set funcao = 'pla2_abas_rreo.php?anexo=4' where id_item = 228475;
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
        DB::statement(<<<SQL
update db_itensmenu set funcao = 'con2_lrfrecdesprpps0001.php?dfiscal=true' where id_item = 228475;
SQL
        );
    }
}
