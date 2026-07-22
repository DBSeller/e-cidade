<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M22536AjusteVariavelPad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "update configuracoes.db_layoutcampos set db52_nome='cpf_dependente' where db52_nome = 'cpf_depentente'";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "update configuracoes.db_layoutcampos set db52_nome='cpf_depentente' where db52_nome = 'cpf_dependente'";
        DB::connection()->getPdo()->exec($sql);
    }
}