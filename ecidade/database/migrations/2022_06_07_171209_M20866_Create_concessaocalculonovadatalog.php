<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateConcessaocalculonovadatalog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.concessaocalculonovadatalog', function (Blueprint $table) {
            $table->bigIncrements('rh508_sequencial');
            $table->integer('rh508_concessaocalculo');
            $table->integer('rh508_codigo');

            $table->foreign('rh508_concessaocalculo')->references('rh504_sequencial')->on('concessaocalculo');
            $table->foreign('rh508_codigo')->references('h16_codigo')->on('assenta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.concessaocalculonovadatalog');
    }
}
