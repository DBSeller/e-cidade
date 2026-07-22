<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21883AdicionaColunaParitbi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_syscampo values(1014485,'it24_emiteguiaquitacao','bool','Parâmetro para habilitar impressão da guia de quitação','f', 'Emite Guia de Quitação',1,'f','f','f',5,'text','Emite Guia de Quitação');
        insert into db_syscampodef values(1014485,'false','');
        insert into db_sysarqcamp values(2362,1014485,21,0);
        alter table paritbi add column it24_emiteguiaquitacao boolean not null default 'f'; 
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
        delete 
        from db_syscampodef 
        where codcam =1014485;
        
        delete 
          from db_sysarqcamp 
         where codarq = 2362 
           and codcam = 1014485;
        
        delete 
          from db_syscampo
         where codcam = 1014485;
        
        alter table paritbi 
         drop column it24_emiteguiaquitacao;
SQL
);         
    }
}