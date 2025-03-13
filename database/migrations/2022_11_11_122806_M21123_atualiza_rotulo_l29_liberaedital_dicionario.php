<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21123AtualizaRotuloL29LiberaeditalDicionario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            UPDATE db_syscampo SET rotulo = 'Libera edital' WHERE codcam = 9414;
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
            UPDATE db_syscampo SET rotulo = 'l29_liberaedital' WHERE codcam = 9414;
SQL
        );
    }
}
