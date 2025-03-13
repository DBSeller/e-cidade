<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20346DeletaUniqueIndexFarControlemed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP INDEX IF EXISTS far_controlemed_fa10_medic_controle_in;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("CREATE UNIQUE INDEX far_controlemed_fa10_medic_controle_in ON farmacia.far_controlemed(fa10_i_medicamento, fa10_i_controle);");
    }
}
