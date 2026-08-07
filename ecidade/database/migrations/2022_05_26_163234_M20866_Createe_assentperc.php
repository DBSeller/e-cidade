<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateeAssentperc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.assentperc', function (Blueprint $table) {
            $table->bigIncrements('rh501_sequencial');
            $table->integer('rh501_seqasentconf');
            $table->integer('rh501_ordem');
            $table->float('rh501_perc');
            $table->text('rh501_unidade');

            $table->foreign('rh501_seqasentconf')->references('rh500_sequencial')->on('assentconf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.assentperc');
    }
}
