<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22780AlterandoTipoEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            UPDATE liclicitatipoempresa SET l32_descricao = 'Normal' WHERE l32_sequencial = 1;
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
            UPDATE liclicitatipoempresa SET l32_descricao = 'NORMAL' WHERE l32_sequencial = 1;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
