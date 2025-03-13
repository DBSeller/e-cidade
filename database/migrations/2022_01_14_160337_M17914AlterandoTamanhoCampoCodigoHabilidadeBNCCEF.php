<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M17914AlterandoTamanhoCampoCodigoHabilidadeBNCCEF extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_syscampo set nomecam = 'ed148_codigo', conteudo = 'varchar(20)' where codcam = 1010918;

ALTER TABLE bnccensinofundamental ALTER COLUMN ed148_codigo TYPE varchar(20);
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
update db_syscampo set nomecam = 'ed148_codigo', conteudo = 'varchar(8)' where codcam = 1010918;

ALTER TABLE bnccensinofundamental ALTER COLUMN ed148_codigo TYPE varchar(8);
SQL
        );
    }
}