<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21349AdicionaS2420Esocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        update recursoshumanos.esocialformulariotipo set rh209_descricao = 'S-1207 - Benefícios - Entes Públicos'  where rh209_sequencial = 46;
        insert into habitacao.avaliacao values (4000115, 5, 'S-2420 - Cadastro de Benefício - Entes Públicos - Término', 'S-2420 - ', true, 's2420-beneficios-termino', null, false);
        insert into recursoshumanos.esocialformulariotipo values (46, 'S-2420 - Cadastro de Benefício - Entes Públicos - Término');
        insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.0', 4000115, 46);
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
        delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 46;
        delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 46;
        delete from habitacao.avaliacao where db101_sequencial = 4000115;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
