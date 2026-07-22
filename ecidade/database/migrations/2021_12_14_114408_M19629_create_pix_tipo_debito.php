<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629CreatePixTipoDebito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("caixa.arretipopix", function (Blueprint $table) {
            $table->bigIncrements("codtipopix");
            $table->integer("k00_tipo")->unique();
            $table->boolean("modsistema")->default(false);
            $table->boolean("moddbpref")->default(false);
            $table->date("dtini")->nullable();
            $table->date("dtfim")->nullable();
            $table->double("valorinicial", 8, 2)->nullable();
            $table->double("valorfinal", 8, 2)->nullable();
            $table->integer("qtdemissao")->nullable();
            $table->timestamps();

            $table->foreign("k00_tipo")->references("k00_tipo")->on("caixa.arretipo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("caixa.arretipopix");
    }
}
