<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateConcessaoassent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.concessaoassent', function (Blueprint $table) {
            $table->bigIncrements('rh505_sequencial');
            $table->integer('rh505_concessaocalculo');
            $table->integer('rh505_codigo');
            $table->integer('rh505_anousu');
            $table->integer('rh505_mesusu');
            $table->date('rh505_data');

            $table->foreign('rh505_concessaocalculo')->references('rh504_sequencial')->on('concessaocalculo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.concessaoassent');
    }
}
