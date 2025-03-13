<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629CreateDbBancoPix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("configuracoes.db_bancos_pix", function(Blueprint $table)
        {
            $table->bigIncrements("db90_codban_pix");
            $table->string("db90_codban", 10)->unique();
            $table->integer("db90_tipo_ambiente")->nullable();
            $table->string("db90_login")->nullable();
            $table->string("db90_senha")->nullable();
            $table->string("db90_chave_api")->nullable();
            $table->string("db90_chave_pix")->nullable();
            $table->string("db90_numconv")->nullable();
            $table->boolean("db90_cnpj_municipio")->default(false);
            $table->string("db90_cnpj", 15)->nullable();
            $table->timestamps();

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
        Schema::drop("configuracoes.db_bancos_pix");
    }
}
