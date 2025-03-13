<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21629 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_syscampo values(1014449,'r52_observacao','text','Observação','', 'Observação',1,'t','t','f',0,'text','Observação');
insert into db_sysarqcamp values(570,1014449,30,0);

alter table pessoal.pensao add column r52_observacao text;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
alter table pessoal.pensao drop column r52_observacao;

delete from db_sysarqcamp where codcam = 1014449;
delete from db_syscampo where codcam = 1014449;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
