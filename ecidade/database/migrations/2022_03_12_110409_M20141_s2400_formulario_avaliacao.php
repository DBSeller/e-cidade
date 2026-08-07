<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20141S2400FormularioAvaliacao extends Migration
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
insert into habitacao.avaliacao values (4000109, 5, 'S-2400 - Cadastro de Beneficiário - Entes Públicos - Início', 'S-2400 - Cadastro de Beneficiário - Entes Públicos - Início', true, 's2400-cadastro-beneficiario-entes-pulicos-inicio', null, false);
insert into recursoshumanos.esocialformulariotipo values (40, 'S-2400 - Cadastro de Beneficiário - Entes Públicos - Início');
insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000109, 40);
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
delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 40;
delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 40;
delete from habitacao.avaliacao where db101_sequencial = 4000109;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}

