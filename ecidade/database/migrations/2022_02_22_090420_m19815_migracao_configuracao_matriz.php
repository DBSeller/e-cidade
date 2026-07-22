<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19815MigracaoConfiguracaoMatriz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from conplanoatributos where c120_anousu = 2022 and c120_infocomplementar = 50;
update conplanoatributos set c120_infocomplementar = 60 where c120_anousu = 2022 and c120_infocomplementar = 53;
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
    }
}
