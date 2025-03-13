<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21214AdicaoEventoS1202 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        insert into habitacao.avaliacao values (4000112, 5, 'S-1202 - Remuneração RPPS', 'S-1202 - Remuneração RPPS', true, '1202-regime-proprio-previdencia-social', null, false);
        insert into recursoshumanos.esocialformulariotipo values (43, 'S-1202 - Remuneração RPPS');
        insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000112, 43);
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
        delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 43;
        delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 43;
        delete from habitacao.avaliacao where db101_sequencial = 4000112;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
