<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateConcessaocalculolog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.concessaocalculolog', function (Blueprint $table) {
            $table->bigIncrements('rh507_sequencial');
            $table->integer('rh507_concessaocalculo');
            $table->integer('rh507_assent');

            $table->foreign('rh507_concessaocalculo')->references('rh504_sequencial')->on('concessaocalculo');
            $table->foreign('rh507_assent')->references('h16_codigo')->on('assenta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.concessaocalculolog');
    }
}
