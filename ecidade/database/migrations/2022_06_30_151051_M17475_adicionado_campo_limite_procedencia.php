<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M17475AdicionadoCampoLimiteProcedencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

       
        select fc_executa_ddl('alter table divida.proced 
          add column v03_limite date;');

        insert into db_syscampo 
        values(1014238,'v03_limite','date','Data Limite da procedência','null', 'Data Limite',10,'f','f','f',1,'text','Data Limite');
        insert into db_sysarqcamp 
        values(93,1014238,8,0);
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
        
        alter table divida.proced 
         drop column v03_limite;
         
        delete 
          from db_sysarqcamp
         where codcam = 1014238;

        delete 
          from db_syscampo
         where codcam = 1014238;
SQL
        );

    }
}
