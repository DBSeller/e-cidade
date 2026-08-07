<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20733AdicionaNovoTipoPosicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into acordoposicaotipo values (9, 'Alteração ou Cessão de Contratado');
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
        delete from acordoposicaotipo where ac27_sequencial = 9;
SQL
        );
    }
}
