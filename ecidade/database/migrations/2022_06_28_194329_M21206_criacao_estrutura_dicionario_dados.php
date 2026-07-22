<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21206CriacaoEstruturaDicionarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
--
-- dicionario
--
insert into db_sysarquivo values (1010948 , 'rhlotavincrubrica' , 'Vinculo entre lotação e rubrica' , 'rh239' , '2022-06-29' , 'Vinculo entre lotação e rubrica' ,0 , 'f' , 't', 't', 't');
insert into db_sysarqmod values (28,1010948);

insert into db_syscampo values(1014232,'rh239_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014233,'rh239_rhlota','int4','Lotação','0', 'Lotação',10,'f','f','f',1,'text','Lotação');
insert into db_syscampo values(1014234,'rh239_rhrubricas','char(4)','Rubrica','0', 'Rubrica',10,'f','f','f',1,'text','Rubrica');
insert into db_syscampo values(1014235,'rh239_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');

insert into db_sysarqcamp values(1010948,1014232,1,0);
insert into db_sysarqcamp values(1010948,1014233,2,0);
insert into db_sysarqcamp values(1010948,1014234,3,0);
insert into db_sysarqcamp values(1010948,1014235,4,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010948,1014232,1,1014233);

insert into db_sysindices values(1008788,'rhlotavincrubrica_rhlota_in',1010948,'0');
insert into db_syscadind values(1008788,1014233,1);

insert into db_sysindices values(1008789,'rhlotavincrubrica_rhrubricas_in',1010948,'0');
insert into db_syscadind values(1008789,1014234,1);

insert into db_sysindices values(1008790,'rhlotavincrubrica_rhlota_rhrubricas_instit_unique_in',1010948,'1');
insert into db_syscadind values(1008790,1014233,1);
insert into db_syscadind values(1008790,1014234,2);
insert into db_syscadind values(1008790,1014235,3);

insert into db_sysforkey values(1010948,1014233,1,894,0);
insert into db_sysforkey values(1010948,1014234,1,1177,0);
insert into db_sysforkey values(1010948,1014235,1,83,0);

insert into db_syssequencia values(1001073, 'rhlotavincrubrica_rh239_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001073 where codarq = 1010948 and codcam = 1014232;

insert into db_syscampo values(1014236,'r11_utilizarhlotavincrubrica','bool','Utiliza vinculo da lotação com rubricas','f', 'Utiliza vinculo da lotação com rubricas',1,'f','f','f',5,'text','Utiliza vinculo da lotação com rubricas');
insert into db_syscampodef values(1014236,'t','Sim');
insert into db_syscampodef values(1014236,'f','Não');

insert into db_sysarqcamp values(536,1014236,106,0);

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                 values ( 228681 ,'Vinculo Lotação/Rubrica' ,'Vinculo Lotação/Rubrica' ,'pes1_rhlotavincrubrica001.php' ,'1' ,'1' ,'Vinculo entre Lotação e Rubrica' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3517 ,228681 ,4 ,952 );

--
-- estrutura
--
create table pessoal.rhlotavincrubrica(rh239_sequencial int primary key,
    rh239_rhlota int not null,
    rh239_rhrubricas char(4) not null,
    rh239_instituicao int not null);

create index rhlotavincrubrica_rhlota_in on pessoal.rhlotavincrubrica(rh239_rhlota);
create index rhlotavincrubrica_rhrubricas_in on pessoal.rhlotavincrubrica(rh239_rhrubricas);
create unique index rhlotavincrubrica_rhlota_rhrubricas_instit_unique_in on pessoal.rhlotavincrubrica(rh239_rhlota,rh239_rhrubricas,rh239_instituicao);

create sequence pessoal.rhlotavincrubrica_rh239_sequencial_seq;

alter table pessoal.rhlotavincrubrica add constraint rhlotavincrubrica_rh239_rhlota_fk FOREIGN KEY (rh239_rhlota) REFERENCES rhlota(r70_codigo);
alter table pessoal.rhlotavincrubrica add constraint rhlotavincrubrica_rh239_rhrubricas_fk FOREIGN KEY (rh239_rhrubricas,rh239_instituicao) REFERENCES rhrubricas(rh27_rubric, rh27_instit);
alter table pessoal.rhlotavincrubrica add constraint rhlotavincrubrica_rh239_instituicao_fk FOREIGN KEY (rh239_instituicao) REFERENCES db_config(codigo);

alter table cfpess add column r11_utilizarhlotavincrubrica boolean default false;
 
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
--
-- dicionario
--
delete from db_syssequencia where codsequencia = 1001073;
delete from db_sysprikey where codarq = 1010948;
delete from db_sysforkey where codarq = 1010948;
delete from db_syscadind where codind in (1008788,1008789,1008790);
delete from db_sysindices where codarq = 1010948;
delete from db_sysarqcamp where codarq = 1010948;
delete from db_syscampodef where codcam in (1014232,1014233,1014234,1014235);
delete from db_syscampo where codcam in (1014232,1014233,1014234,1014235);
delete from db_sysarqmod where codarq = 1010948;
delete from db_sysarquivo where codarq = 1010948;

delete from db_sysarqcamp where codcam = 1014236;
delete from db_syscampodef where codcam = 1014236;
delete from db_syscampo where codcam = 1014236;

delete from db_menu where id_item_filho = 228681;
delete from db_itensmenu where id_item = 228681;
--
-- estrutura
--
drop table pessoal.rhlotavincrubrica;
drop sequence pessoal.rhlotavincrubrica_rh239_sequencial_seq;

alter table cfpess drop column r11_utilizarhlotavincrubrica;

SQL;
        
        DB::connection()->getPdo()->exec($sql);
    }
}
