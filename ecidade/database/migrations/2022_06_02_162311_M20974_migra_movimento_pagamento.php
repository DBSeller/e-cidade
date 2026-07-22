<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20974MigraMovimentoPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into caixa.corempagemovpagamento
  select nextval('corempagemovpagamento_k12_sequencial_seq') as k12_sequencial,
         corempagemov.k12_id,
         corempagemov.k12_data,
         corempagemov.k12_autent,
         corempagemov.k12_codmov
  from corempagemov
       left join corempagemovpagamento
              on corempagemovpagamento.k12_id = corempagemov.k12_id
             and corempagemovpagamento.k12_data = corempagemov.k12_data
             and corempagemovpagamento.k12_autent = corempagemov.k12_autent
             and corempagemovpagamento.k12_codmov = corempagemov.k12_codmov
  where corempagemovpagamento.k12_id is null ;
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
delete from caixa.corempagemovpagamento;
SQL
        );
    }
}
