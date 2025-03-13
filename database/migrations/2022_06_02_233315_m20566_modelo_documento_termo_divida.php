<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20566ModeloDocumentoTermoDivida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->tipoDocTermo(); 
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

    private function tipoDocTermo() 
    {
        DB::table('db_tipodoc')->insert(['db08_codigo' => 5029, 'db08_descr' => 'TERMO INSCRIÇÃO EM DÍVIDA']);
    }
}
