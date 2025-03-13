<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21274ADICAOEVENTO2405 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upEvento();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 44;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 44;
            delete from habitacao.avaliacao where db101_sequencial = 4000113;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEvento()
    {
        $sql = <<<SQL
        SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
        insert into habitacao.avaliacao values (4000113, 5, 'S-2405 - Cadastro de Beneficiário - Entes Públicos - Alteração', 'S-2405 - Cadastro de Beneficiário - Entes Públicos - Alteração', true, 's2405-cadastro-beneficiario-entes-pulicos-alt', null, false);
        insert into recursoshumanos.esocialformulariotipo values (44, 'S-2405 - Cadastro de Beneficiário - Entes Públicos - Alteração');
        insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000113, 44);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
