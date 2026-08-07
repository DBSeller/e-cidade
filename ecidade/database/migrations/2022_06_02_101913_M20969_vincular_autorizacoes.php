<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20969VincularAutorizacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL

         insert into empautorizacaoautorizada
           select nextval('empautorizacaoautorizada_id_seq'),
                  e54_autori
             from empautoriza
        left join empautorizacaoautorizada on empautoriza_id = e54_autori
            where empautoriza_id is null;
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
        return;
    }
}
