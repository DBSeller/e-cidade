<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20335ModimprimeCarneCampinaGrande extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            INSERT INTO cadmodcarne
            (
                k47_sequencial,
                k47_descr,
                k47_tipoconvenio
            )
            VALUES
            (
                108,
                'CARNE COBRANCA REGISTRADA MOD 2',
                2
            );
SQL
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE 
              FROM cadmodcarne 
             WHERE k47_sequencial = 108;
SQL
        );
    }
}
