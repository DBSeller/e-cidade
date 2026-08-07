<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateConcessaocalculo extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.concessaocalculo', function (Blueprint $table) {
            $table->bigIncrements('rh504_sequencial');
            $table->integer('rh504_regist');
            $table->integer('rh504_seqassentconf');
            $table->integer('rh504_seqassentperc');
            $table->date('rh504_dtproc');
            $table->date('rh504_data');

            $table->foreign('rh504_regist')->references('rh01_regist')->on('rhpessoal');
            $table->foreign('rh504_seqassentconf')->references('rh500_sequencial')->on('assentconf');
            $table->foreign('rh504_seqassentperc')->references('rh501_sequencial')->on('assentperc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.concessaocalculo');
    }
}
