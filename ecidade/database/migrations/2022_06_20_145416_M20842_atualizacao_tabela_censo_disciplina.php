<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20842AtualizacaoTabelaCensoDisciplina extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into escola.censodisciplina (ed265_i_codigo, ed265_c_descr)
values  ( 33, 'Projeto de vida');
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
delete from escola.censodisciplina where ed265_i_codigo = 33;
SQL
        );
    }
}
