<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21300AdicionaS2416Esocial extends Migration
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
            insert into habitacao.avaliacao values (4000116, 5, 'S-2416 - Cadastro de Benefício - Entes Públicos - Alteração', 'S-2416 - Cadastro de Benefício - Entes Públicos - Alteração', true, 's2416-alteracao-benefício', null, false);
            insert into recursoshumanos.esocialformulariotipo values (48, 'S-2416 - Cadastro de Benefício - Entes Públicos - Alteração');
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000116, 48);
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
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 48;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 48;
            delete from habitacao.avaliacao where db101_sequencial = 4000116;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
