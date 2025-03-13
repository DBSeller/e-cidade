<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21179AlteraEstruturaCfpess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into configuracoes.db_syscampo values(1014231,'r11_filtralotacaousuario','bool','Utiliza permissão de Lotação','f', 'Utiliza permissão de Lotação',1,'f','f','f',5,'text','Utiliza permissão de Lotação');
insert into configuracoes.db_syscampodef values(1014231,'f','NÃO');
insert into configuracoes.db_syscampodef values(1014231,'t','SIM');
insert into configuracoes.db_sysarqcamp values(536,1014231,105,0);        

alter table pessoal.cfpess add column r11_filtralotacaousuario boolean default false;
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
delete from configuracoes.db_syscampodef where codcam = 1014231;
delete from configuracoes.db_sysarqcamp where codcam = 1014231;
delete from configuracoes.db_syscampo  where codcam = 1014231;        
alter table pessoal.cfpess drop column r11_filtralotacaousuario;
SQL;
        
        DB::connection()->getPdo()->exec($sql);
    }
}
