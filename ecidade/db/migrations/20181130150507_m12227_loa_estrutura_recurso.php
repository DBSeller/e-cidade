<?php

use Classes\PostgresMigration;

class M12227LoaEstruturaRecurso extends PostgresMigration
{

    public function up()
    {
        $this->execute(
            <<<SQL_UP

insert into db_syscampo values(1010114,'o15_loaidentificadoruso','int4','Identificador de Uso','0', 'Identificador de Uso',1,'t','f','f',1,'text','Identificador de Uso');
insert into db_syscampo values(1010116,'o15_loatipo','int4','Tipo','0', 'Tipo',1,'t','f','f',1,'text','Tipo');
insert into db_syscampo values(1010117,'o15_loagrupo','int4','Grupo','0', 'Grupo',1,'t','f','f',1,'text','Grupo');
insert into db_syscampo values(1010118,'o15_loaespecificacao','varchar(2)','Especificação','', 'Especificação',2,'t','t','f',0,'text','Especificação');

insert into db_sysarqcamp values(749,1010114,9,0);
insert into db_sysarqcamp values(749,1010116,10,0);
insert into db_sysarqcamp values(749,1010117,11,0);
insert into db_sysarqcamp values(749,1010118,12,0);

insert into db_sysindices values(1008360,'orctiporec_loaidentificadoruso_in',749,'0');
insert into db_syscadind values(1008360,1010114,1);
insert into db_sysindices values(1008361,'orctiporec_loatipo_in',749,'0');
insert into db_syscadind values(1008361,1010116,1);
insert into db_sysindices values(1008362,'orctiporec_loagrupo_in',749,'0');
insert into db_syscadind values(1008362,1010117,1);
insert into db_sysindices values(1008363,'orctiporec_loaespecificacao_in',749,'0');
insert into db_syscadind values(1008363,1010118,1);

alter table orctiporec add column o15_loaidentificadoruso integer default null;
alter table orctiporec add column o15_loatipo integer default null;
alter table orctiporec add column o15_loagrupo integer default null;
alter table orctiporec add column o15_loaespecificacao varchar(2) default null;

create index orctiporec_loaidentificadoruso_in on orctiporec (o15_loaidentificadoruso); 
create index orctiporec_loatipo_in on orctiporec (o15_loatipo); 
create index orctiporec_loagrupo_in on orctiporec (o15_loagrupo); 
create index orctiporec_loaespecificacao_in on orctiporec (o15_loaespecificacao); 


SQL_UP
);
    }

    public function down()
    {

        $this->execute(
            <<<SQL_DOWN
            
delete from db_syscadind where codcam in (1010118, 1010117, 1010116, 1010114);
delete from db_sysindices where codind in (1008363, 1008362, 1008361, 1008360);
delete from db_sysarqcamp where codcam in (1010118, 1010117, 1010116, 1010114);
delete from db_syscampo where codcam in (1010118, 1010117, 1010116, 1010114);

alter table orctiporec drop column o15_loaidentificadoruso;
alter table orctiporec drop column o15_loatipo;
alter table orctiporec drop column o15_loagrupo;
alter table orctiporec drop column o15_loaespecificacao;

SQL_DOWN
        );

    }

}
