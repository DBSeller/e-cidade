<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21480AlteradoTamanhoCampoTabelaCertidao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table certidao 
        alter column p50_resultadowebservice 
         type varchar(50);
        
        update db_syscampo
           set conteudo = 'varchar(50)',
               tamanho  = 50
        where codcam = 1014383;
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
        alter table certidao 
        alter column p50_resultadowebservice 
         type varchar(20);
        
        update db_syscampo
           set conteudo = 'varchar(20)',
               tamanho  = 20
        where codcam = 1014383;
SQL
);
    }
}
