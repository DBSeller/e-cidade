<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21354AcertoTipoBaseAbaDisciplinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            update basemps 
                set ed34_tipobase = 2 
            where ed34_i_codigo in(
                select basemps.ed34_i_codigo
                    from basemps
                    join serie ON serie.ed11_i_codigo = basemps.ed34_i_serie
                    join ensino ON ensino.ed10_i_codigo = serie.ed11_i_ensino
                where ensino.ed10_tipo = 1
            );

            update regencia
                set ed59_tipobase = 2
            where ed59_i_codigo in(
                select ed59_i_codigo
                    from regencia
                    join serie ON serie.ed11_i_codigo = regencia.ed59_i_serie
                    join ensino ON ensino.ed10_i_codigo = serie.ed11_i_ensino
                where ensino.ed10_tipo = 1
            );

            update censoativcompl set ed133_c_descr = upper(ed133_c_descr);
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
        //
    }
}
