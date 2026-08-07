<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20485ADICAOEVENTO2231 extends Migration
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
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 42;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 42;
            delete from habitacao.avaliacao where db101_sequencial = 4000111;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEvento()
    {
        $sql = <<<SQL
        SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
        insert into habitacao.avaliacao values (4000111, 5, 'S-2231 - Cessão/Exercício em Outro Órgão', 'S-2231 - Cessão/Exercício em Outro Órgão', true, 's2231-cessao-exercicio-em-outro-orgao', null, false);
        insert into recursoshumanos.esocialformulariotipo values (42, 'S-2231 - Cessão/Exercício em Outro Órgão');
        insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000111, 42);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
