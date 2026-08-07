<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20178S2410S10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into habitacao.avaliacao values (4000110, 5, 'S-2410 - Cadastro de Beneficio - Entes Públicos - Início', 'S-2410 - Cadastro de Beneficio - Entes Públicos - Início', true, 's2410-cadastro-beneficio-entes-publicos-inicio', null, false);
            insert into recursoshumanos.esocialformulariotipo values (41, 'S-2410 - Cadastro de Beneficio - Entes Públicos - Início');
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000110, 41);
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
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 41;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 41;
            delete from habitacao.avaliacao where db101_sequencial = 4000110;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
