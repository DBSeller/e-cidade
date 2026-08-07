<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22311AcertoEmBase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            update protocolo.protprocessodocumento
            set  p01_data  = ( select p58_dtproc  from protocolo.protprocesso where p58_codproc  = p01_protprocesso)
            where
                 p01_data is null and p01_estorage  = true;
            update protocolo.protprocessodocumento  set  p01_documento = p01_nomedocumento::INTEGER,  p01_nomedocumento = p01_descricao where p01_estorage is true and  p01_documento is null;

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
