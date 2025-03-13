<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M22722AtualizandoRegistro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        update escola.censoorgemissrg 
        set ed132_c_descr = 'DEPARTAMENTO ESTADUAL DE TRANSITO'
        where ed132_i_codigo = 83;

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
        update escola.censoorgemissrg 
        set ed132_c_descr = 'CONSELHO REGIONAL DE ECONOMIA'
        where ed132_i_codigo = 83;
SQL
);
    }
}
