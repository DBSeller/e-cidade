<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21494AjusteTipoBaseTabelasHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        update histmpsdisc
            set ed65_tipobase = 2
        where ed65_i_codigo in (
            select ed65_i_codigo from histmpsdisc
                join disciplina ON disciplina.ed12_i_codigo = histmpsdisc.ed65_i_disciplina
                join ensino ON ensino.ed10_i_codigo = disciplina.ed12_i_ensino
            where ensino.ed10_tipo = 1
        );

        update histmpsdiscfora
            set ed100_tipobase = 2
        where ed100_i_codigo in (
            select ed100_i_codigo
                from histmpsdiscfora
                join disciplina ON disciplina.ed12_i_codigo = histmpsdiscfora.ed100_i_disciplina
                    join ensino ON ensino.ed10_i_codigo = disciplina.ed12_i_ensino
            where ensino.ed10_tipo = 1
        );

        update db_syscampo set maiusculo = 'f' where codcam = 20368;
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
        DB::connection()->getPdo()->exec(<<<SQL
        update db_syscampo set maiusculo = 't' where codcam = 20368;
SQL
        );
    }
}
