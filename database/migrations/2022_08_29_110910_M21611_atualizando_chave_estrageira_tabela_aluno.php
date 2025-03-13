<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M21611AtualizandoChaveEstrageiraTabelaAluno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::connection()->getPdo()->exec(<<<SQL
        
delete from db_sysforkey where codarq = 1010051 and referen = 1010051;
insert into db_sysforkey values(1010051,18010,1,3183,0);

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
        //
        DB::connection()->getPdo()->exec(<<<SQL

delete from db_sysforkey where codarq = 1010051 and referen = 3183;
insert into db_sysforkey values(1010051,18010,1,1010051,0);

SQL
        );
    }
}
