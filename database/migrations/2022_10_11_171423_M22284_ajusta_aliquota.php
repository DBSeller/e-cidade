<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22284AjustaAliquota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
   
            update issgsanexosfaixasimpostosfaixas set q164_aliquotatotal = 9 where q164_sequencial = 8;
      
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
      
        return true;    

    }
}
