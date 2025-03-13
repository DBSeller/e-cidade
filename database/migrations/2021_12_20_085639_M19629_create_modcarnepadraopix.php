<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19629CreateModcarnepadraopix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("caixa.modcarnepadraopix", function(Blueprint $table){
            $table->bigIncrements("k48_sequencial_pix");
            $table->integer("k48_sequencial");
            $table->boolean("k48_ammpix");
            $table->timestamps();

            $table->foreign("k48_sequencial")
                ->references("k48_sequencial")
                ->on("caixa.modcarnepadrao");
        });

        $sql = "
        insert into
            cadmodcarne
        values
            (
                109,
                'MODELO PIX CARNE',
                'MODELO PIX CARNE',
                0,
                0,
                '',
                1
            );

        insert into
            cadmodcarne
        values
            (
                110,
                'MODELO PIX RECIBO',
                'MODELO PIX RECIBO',
                0,
                0,
                '',
                1
            );";

        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::drop("caixa.modcarnepadraopix");
        $sql = "delete from cadmodcarne where k47_sequencial in (109,110);";
        DB::connection()->getPdo()->exec($sql);
    }
}
