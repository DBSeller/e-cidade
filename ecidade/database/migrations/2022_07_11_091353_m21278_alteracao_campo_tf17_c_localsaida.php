<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21278AlteracaoCampoTf17CLocalsaida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            alter table tfd.tfd_agendasaida ALTER COLUMN tf17_c_localsaida type character varying;
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
            alter table tfd.tfd_agendasaida ALTER COLUMN tf17_c_localsaida type character varying(50);
SQL
        );
    }
}
