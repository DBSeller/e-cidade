<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21210AjusteExercicioPlano extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update planejamento.programaestrategico set pl9_orcprograma = 2022 where pl9_orcprograma = 2021;
update planejamento.iniciativaprojativ set pl12_anoorcamento = 2022 where pl12_anoorcamento = 2021;
update planejamento.detalhamentoiniciativa set pl20_anoorcamento = 2022 where pl20_anoorcamento = 2021;
update planejamento.estimativareceita set anoorcamento = 2022 where anoorcamento = 2021;
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

    }
}
