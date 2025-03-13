<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22253MelhoriaCampoRacaCor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
            UPDATE cgs_und
            SET z01_c_raca = null
            WHERE z01_c_raca NOT ILIKE '%BRANCA%'
                AND z01_c_raca NOT ILIKE '%AMARELA%'
                AND z01_c_raca NOT ILIKE '%PRETA%'
                AND z01_c_raca NOT ILIKE '%IND%' --EQUIVALE A INDÍGENA
                AND z01_c_raca NOT ILIKE '%PARDA%';
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
