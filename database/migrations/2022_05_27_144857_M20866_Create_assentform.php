<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateAssentform extends Migration
{
    public function up()
    {
        Schema::create('recursoshumanos.assentform', function (Blueprint $table) {
            $table->bigIncrements('rh502_sequencial');
            $table->integer('rh502_codigo');
            $table->integer('rh502_seqassentconf');
            $table->text('rh502_condicao');
            $table->text('rh502_resultado');
            $table->text('rh502_operador');
            $table->text('rh502_multiplicador');

            $table->foreign('rh502_seqassentconf')->references('rh500_sequencial')->on('assentconf');
            $table->foreign('rh502_codigo')->references('h12_codigo')->on('tipoasse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursoshumanos.assentform');
    }
}
