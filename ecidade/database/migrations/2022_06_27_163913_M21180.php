<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21180 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        
insert into db_sysarquivo values (1010947, 'folhafiltros', 'Filtros do processamento da folha de pagamento', 'r39', '2022-06-27', 'Filtros do processamento da folha de pagamento', 0, 'f', 't', 't', 't' );
insert into db_sysarqmod values (28,1010947);

insert into db_syscampo values(1014228,'r39_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
insert into db_syscampo values(1014229,'r39_filtros','text','Filtros','', 'Filtros',10,'t','t','f',0,'text','Filtros');
insert into db_syscampo values(1014230,'r39_processamento','date','Dt. Processamento','null', 'Dt. Processamento',10,'f','f','f',3,'text','Dt. Processamento');

insert into db_sysarqcamp values(1010947,1014228,1,0);
insert into db_sysarqcamp values(1010947,1014229,2,0);
insert into db_sysarqcamp values(1010947,1014230,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010947,1014228,1,1014228);

create table pessoal.folhafiltros (r39_instituicao int4, 
                                   r39_filtros text, 
                                   r39_processamento date not null, 
                                   primary key (r39_instituicao));
    
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

delete from db_sysprikey where codarq = 1010947;
delete from db_sysarqcamp where codarq = 1010947;
delete from db_syscampo where codcam in (1014228, 1014229, 1014230);
delete from db_sysarqmod where codarq = 1010947;
delete from db_sysarquivo where codarq = 1010947;

drop table pessoal.folhafiltros;

SQL;
        
        DB::connection()->getPdo()->exec($sql);
        
    }
}
