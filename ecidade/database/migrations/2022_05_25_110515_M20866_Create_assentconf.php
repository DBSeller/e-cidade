<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20866CreateAssentconf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.assentconf', function (Blueprint $table) {
            $table->bigIncrements('rh500_sequencial');
            $table->integer('rh500_assentamento');
            $table->date('rh500_datalimite')->nullable();
            $table->integer('rh500_condede');
            $table->integer('rh500_naoconcede')->nullable();
            
            $table->foreign('rh500_assentamento')->references('h12_codigo')->on('tipoasse');
            $table->foreign('rh500_condede')->references('h12_codigo')->on('tipoasse');
            $table->foreign('rh500_naoconcede')->references('h12_codigo')->on('tipoasse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.assentconf');
    }
}
