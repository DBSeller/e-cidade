<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21260InclusaoS1207 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        insert into habitacao.avaliacao values (4000114, 5, 'S-1207 - Benefícios - Entes Públicos', 'S-1207 - Benefícios - Entes Públicos', true, '1207-beneficios-entes-publicos', null, false);
        insert into recursoshumanos.esocialformulariotipo values (45, 'S-1207 - Remuneração RPPS');
        insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000114, 45);
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
        delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 45;
        delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 45;
        delete from habitacao.avaliacao where db101_sequencial = 4000114;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
