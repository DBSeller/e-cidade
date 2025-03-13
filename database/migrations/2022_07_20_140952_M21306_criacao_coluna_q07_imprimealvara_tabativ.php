<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21306CriacaoColunaQ07ImprimealvaraTabativ extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->atualizaEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->retornaEstrutura();
    }

    public function atualizaEstrutura()
    {
        DB::statement("alter table issqn.tabativ add column q07_imprimealvara varchar(10) default 'Sim';");
        DB::statement("insert into db_syscampo values(1014378,'q07_imprimealvara','varchar(10)','Imprime Alvará','Sim', 'Imprime Alvará',10,'f','t','f',0,'text','Imprime Alvará');");
        DB::statement("insert into db_sysarqcamp values ( 67 ,1014378 ,13 ,0 );");
    }

    public function retornaEstrutura()
    {
        DB::statement("alter table issqn.tabativ drop column q07_imprimealvara;");
        DB::table('db_sysarqcamp')->where('codcam', 1014378)->delete();
        DB::table('db_syscampo')->where('codcam', 1014378)->delete();
    }
}
