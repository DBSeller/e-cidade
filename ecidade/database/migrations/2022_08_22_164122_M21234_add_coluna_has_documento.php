<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21234AddColunaHasDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        ALTER TABLE protocolo.protprocessodocumento ADD COLUMN  p01_documento_hash  varchar(250);
        insert into db_syscampo values(1014453,'p01_documento_hash','varchar(250)','Hash para validação do documento com base no id','', 'Hash Documento',250,'t','f','f',0,'text','Hash Documento');
        insert into db_sysarqcamp values(3649,1014453,13,0);
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
        DELETE FROM db_sysarqcamp WHERE codcam =  1014453;
        DELETE FROM  db_syscampo WHERE codcam =  1014453;
        ALTER TABLE protocolo.protprocessodocumento DROP COLUMN  p01_documento_hash;

SQL
        );
    }
}
