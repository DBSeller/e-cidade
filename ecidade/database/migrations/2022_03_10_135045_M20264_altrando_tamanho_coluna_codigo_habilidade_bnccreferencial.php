<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20264AltrandoTamanhoColunaCodigoHabilidadeBnccreferencial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::connection()->getPdo()->exec(<<<SQL
        alter table bnccreferencial ALTER COLUMN ed168_codigohabilidade type character varying(20);
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
        DB::connection()->getPdo()->exec(<<<SQL
        alter table bnccreferencial ALTER COLUMN ed168_codigohabilidade type character varying(10);
SQL
        );
       
    }
}
