<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20866CreateAssentconceconfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.assentconcedeconf', function (Blueprint $table) {
            $table->bigIncrements('rh503_sequencial');
            $table->integer('rh503_seqassentconf');
            $table->integer('rh503_codigo');
            $table->integer('rh503_acao');
            $table->integer('rh503_tipo');
            $table->text('rh503_formula');
            $table->text('rh503_condicao');

            $table->foreign('rh503_seqassentconf')->references('rh500_sequencial')->on('assentconf');
            $table->foreign('rh503_codigo')->references('h12_codigo')->on('tipoasse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('recursoshumanos.assentconcedeconf');
    }
}
