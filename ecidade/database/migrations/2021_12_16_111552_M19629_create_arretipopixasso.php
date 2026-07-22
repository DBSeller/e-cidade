<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629CreateArretipopixasso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("caixa.arretipopixasso", function(Blueprint $table)
        {
            $table->bigIncrements("sequencial");
            $table->string("db90_codban", 10);
            $table->integer("k00_tipo");
            $table->timestamps();

            $table->foreign("k00_tipo")
                ->references("k00_tipo")
                ->on("caixa.arretipo");

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
        Schema::drop("caixa.arretipopixasso");
    }
}
