<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20685AlteracaoSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

alter table public.slipretencaoreceitas set SCHEMA caixa;
alter table public.slipplacaixarec set SCHEMA caixa;
alter table public.slipoperacaoextra set SCHEMA caixa;

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

alter table caixa.slipretencaoreceitas set SCHEMA public;
alter table caixa.slipplacaixarec set SCHEMA public;
alter table caixa.slipoperacaoextra set SCHEMA public;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
