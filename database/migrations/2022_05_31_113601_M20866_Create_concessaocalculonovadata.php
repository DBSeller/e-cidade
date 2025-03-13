<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateConcessaocalculonovadata extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.concessaocalculonovadata', function (Blueprint $table) {
            $table->bigIncrements('rh506_sequencial');
            $table->integer('rh506_concessaocalculo');
            $table->date('rh506_datanova')->nullable();
            
            $table->foreign('rh506_concessaocalculo')->references('rh504_sequencial')->on('concessaocalculo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.concessaocalculonovadata');
    }
}
