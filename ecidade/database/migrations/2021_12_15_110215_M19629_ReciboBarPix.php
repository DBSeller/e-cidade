<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629ReciboBarPix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caixa.recibobarpix', function (Blueprint $table) {
            $table->integer('k00_numpre')->nullable(false);
            $table->integer('k00_numpar')->default(0);
            $table->string('k00_codbar', 100)->nullable(false);
            $table->timestamp('k00_criacaosolicitacao')->nullable();
            $table->string('k00_estadosolicitacao', 50)->nullable();
            $table->string('k00_conciliacaosolicitante', 100);
            $table->integer('k00_numeroversaosolicitacaopagamento')->nullable();
            $table->string('k00_linkqrcode', 100)->nullable(false);
            $table->string('k00_qrcode', 300)->nullable(false);
            $table->primary(['k00_numpre', 'k00_numpar','k00_codbar']);
            $table->index(['k00_numpre', 'k00_numpar']);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caixa.recibobarpix');
    }
}
