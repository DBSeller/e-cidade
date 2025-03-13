<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20866AdicionarSelecaoAssentconf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recursoshumanos.assentconf', function (Blueprint $table) {
            $table->integer('rh500_selecao')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recursoshumanos.assentconf', function (Blueprint $table) {
            $table->dropColumn(['rh500_selecao']);
        });
    }
}
