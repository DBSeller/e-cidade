<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20045AdicionaS2240 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            insert into habitacao.avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 4000108 ,5 ,'S-2240 - Condições Ambientais do Trabalho - Agentes Nocivos S1.0' ,'s2200-Condicao-Ambiental-Trabalho-S10' ,'S-2240 - Condições Ambientais do Trabalho - Agentes Nocivos' ,'true' ,'' ,'true' );

        -- Vinculos com layout
            insert into recursoshumanos.esocialformulariotipo values(39, 'S-2240 - Condições Ambientais do Trabalho');
            insert into recursoshumanos.esocialversaoformulario values(89, 'S1.0', 4000108, 39);
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
            -- Vinculos com layout
            delete from recursoshumanos.esocialversaoformulario where rh211_sequencial = 89;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 39;
            -- Formulario
            delete from habitacao.avaliacao where db101_sequencial = 4000108;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
