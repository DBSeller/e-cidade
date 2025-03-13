<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21514AtualizacaoLayoutcamposLicitaconApendiceb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_layoutcampos set db52_tamanho = 10 where db52_nome = 'CD_TIPO_FUNDAMENTACAO' and db52_layoutlinha = 10290;

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
update db_layoutcampos set db52_tamanho = 8 where db52_nome = 'CD_TIPO_FUNDAMENTACAO' and db52_layoutlinha = 10290;

SQL
        );
    }
}
