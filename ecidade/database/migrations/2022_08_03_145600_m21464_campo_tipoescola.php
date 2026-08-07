<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21464CampoTipoescola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        INSERT into db_syscampo values(1014417,'ed18_i_tipoescola','int4','Define se é uma escola (1) ou de uso interno (2)','1', 'Tipo',10,'f','f','f',1,'text','Tipo'); 
        INSERT into db_sysarqcamp values(1010031,1014417,40,0);
        ALTER TABLE escola.escola ADD column ed18_i_tipoescola int4 NOT NULL DEFAULT 1;
SQL;
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
        ALTER TABLE escola.escola DROP COLUMN ed18_i_tipoescola;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010031 and codcam = 1014417 and seqarq = 40;
        DELETE FROM db_syscampo WHERE codcam = 1014417; 
SQL;
        DB::unprepared($sql);
    }
}
