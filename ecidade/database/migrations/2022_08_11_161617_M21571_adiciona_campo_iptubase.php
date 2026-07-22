<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21571AdicionaCampoIptubase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::connection()->getPdo()->exec(<<<SQL

      insert into db_syscampo values(1014442,'j01_unidade','int8','Número da unidade no lote','1', 'Unidade',10,'t','f','f',1,'text','Unidade');
      
      insert into db_sysarqcamp values(27,1014442,18,0);

      alter table cadastro.iptubase 
        add column j01_unidade integer not null default 1;
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
          from db_sysarqcamp 
         where codcam = 1014442;

        delete 
          from db_syscampo 
         where codcam = 1014442;

        alter table cadastro.iptubase 
          drop column j01_unidade;  
         
SQL
    );
    }
}
