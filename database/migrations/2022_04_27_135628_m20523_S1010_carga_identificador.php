<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20523S1010CargaIdentificador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            update habitacao.avaliacaopergunta set db103_somenteleitura = 't' where db103_sequencial = 3000940;
            update habitacao.avaliacaopergunta set db103_camposql = 'identificador', db103_somenteleitura = 't' where db103_sequencial = 3000941;
        ";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "
            update habitacao.avaliacaopergunta set db103_somenteleitura = 'f' where db103_sequencial = 3000940;
            update habitacao.avaliacaopergunta set db103_camposql = '', db103_somenteleitura = 'f' where db103_sequencial = 3000941;
        ";
        DB::connection()->getPdo()->exec($sql);
    }
}
