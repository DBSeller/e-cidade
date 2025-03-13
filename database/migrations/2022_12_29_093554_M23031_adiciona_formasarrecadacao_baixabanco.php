<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M23031AdicionaFormasarrecadacaoBaixabanco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            insert into formaarrecadacao
            select nextval('formaarrecadacao_k178_sequencial_seq'),
                   '8',
                   'Cartão/Multibanco com fatura/guia de arrecadação'
             where not exists (select 1 from formaarrecadacao where k178_codigo = '8');

            insert into formaarrecadacao
            select nextval('formaarrecadacao_k178_sequencial_seq'),
                   '9',
                   'PIX com fatura/guia de arrecadação'
             where not exists (select 1 from formaarrecadacao where k178_codigo = '9');

            insert into formaarrecadacao
            select nextval('formaarrecadacao_k178_sequencial_seq'),
                   'h',
                   'Cartão/Muitibanco sem fatura/guia de arrecadação'
             where not exists (select 1 from formaarrecadacao where k178_codigo = 'h');

            insert into formaarrecadacao
            select nextval('formaarrecadacao_k178_sequencial_seq'),
                   'i',
                   'PIX sem fatura/guia de arrecadação'
             where not exists (select 1 from formaarrecadacao where k178_codigo = 'i');

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

        delete from formaarrecadacao
              where k178_codigo in ('8', '9', 'h', 'i')
                and not exists (select 1 from disbancotarifa where k179_formaarrecadacao = k178_sequencial);

        select setval('formaarrecadacao_k178_sequencial_seq', (select max(k178_sequencial)::int from formaarrecadacao));
SQL
        );
    }
}
