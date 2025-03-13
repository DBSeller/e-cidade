<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21257AlteradoVersaoEfdreinf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        UPDATE esocial.efdreinfversao SET efd01_versao = '1.5.1' WHERE efd01_sequencial = 1;
        UPDATE esocial.efdreinfversaoformulario SET efd03_versao = '1.5.1' WHERE efd03_sequencial BETWEEN 1 AND 13;
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

        UPDATE esocial.efdreinfversao SET efd01_versao = '1.4' WHERE efd01_sequencial = 1;
        UPDATE esocial.efdreinfversaoformulario SET efd03_versao = '1.4' WHERE efd03_sequencial BETWEEN 1 AND 13;
SQL
        );
    }
}
