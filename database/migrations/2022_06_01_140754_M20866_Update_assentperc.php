<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866UpdateAssentperc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recursoshumanos.assentperc', function($table) {
            $table->integer('rh501_valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recursoshumanos.assentperc', function($table) {
            $table->dropColumn('rh501_valor');
        });
    }
}
