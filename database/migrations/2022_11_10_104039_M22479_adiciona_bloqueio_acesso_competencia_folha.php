<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22479AdicionaBloqueioAcessoCompetenciaFolha extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_syscampo values(1014596,'r11_bloqueiocompetenciaaberta','bool','Bloqueia acesso de competências em aberto. Se SIM não será possivel acesso a informações de competências da folha que não estão fechadas.','f', 'Bloqueia competências em aberto',1,'f','f','f',5,'text','Bloqueia competências em aberto');
insert into db_syscampodef values(1014596,'f','Não');
insert into db_syscampodef values(1014596,'t','Sim');
insert into db_sysarqcamp values(536,1014596,110,0);
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228798 ,'Liberar acesso competências em aberto dos resumos da folha' ,'Liberar acesso competências em aberto dos resumos da folha' ,'' ,'1' ,'1' ,'Liberar acesso competências em aberto dos resumos da folha' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3516 ,228798 ,18 ,952 );

alter table cfpess add column r11_bloqueiocompetenciaaberta boolean default false;
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
delete from db_sysarqcamp  where codcam = 1014596;
delete from db_syscampodef where codcam = 1014596;
delete from db_syscampo where codcam = 1014596;

delete from db_menu where id_item_filho = 228798;
delete from db_itensmenu where id_item = 228798;

alter table cfpess drop column r11_bloqueiocompetenciaaberta;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
