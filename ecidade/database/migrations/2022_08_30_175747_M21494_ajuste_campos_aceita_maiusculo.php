<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21494AjusteCamposAceitaMaiusculo extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        update db_syscampo set maiusculo = 'f' where codcam = 20368;
        update db_syscampo set maiusculo = 'f' where codcam = 20370;
        update db_syscampo set maiusculo = 'f' where codcam = 1008748;
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
        update db_syscampo set maiusculo = 't' where codcam = 20368;
        update db_syscampo set maiusculo = 't' where codcam = 20370;
        update db_syscampo set maiusculo = 't' where codcam = 1008748;
        
SQL
        );
    }
}
