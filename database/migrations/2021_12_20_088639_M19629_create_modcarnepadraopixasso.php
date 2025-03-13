<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629CreateModcarnepadraopixasso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("caixa.modcarnepadraopixasso", function(Blueprint $table){
            $table->bigIncrements("sequencial");
            $table->string("db90_codban", 10);
            $table->integer("k48_sequencial");
            $table->timestamps();

            $table->foreign("k48_sequencial")
                ->references("k48_sequencial")
                ->on("caixa.modcarnepadrao");

            $table->foreign("db90_codban")
                ->references("db90_codban")
                ->on("configuracoes.db_bancos");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::drop("caixa.modcarnepadraopixasso");
    }
}
