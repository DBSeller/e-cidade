<?php

use Classes\PostgresMigration;

class M15862TabelaLigacaoLancamentoComplemento extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011287,'o201_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011288,'o201_codlan','int4','Código do Lançamento','0', 'Código do Lançamento',10,'f','f','f',1,'text','Código do Lançamento');
insert into db_syscampo values(1011289,'o201_complemento','int4','Complemento da Fonte de Recurso','0', 'Complemento da Fonte de Recurso',10,'f','f','f',1,'text','Complemento da Fonte de Recurso');
insert into db_sysarquivo values (1010564, 'conlancamcomplementorecurso', 'conlancamcomplementorecurso', 'o201', '2020-05-21', 'conlancamcomplementorecurso', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010564);
delete from db_sysarqcamp where codarq = 1010564;
insert into db_sysarqcamp values(1010564,1011287,1,0);
insert into db_sysarqcamp values(1010564,1011288,2,0);
insert into db_sysarqcamp values(1010564,1011289,3,0);
insert into db_syssequencia values(1000912, 'conlancamcomplementorecurso_o201_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000912 where codarq = 1010564 and codcam = 1011287;
delete from db_sysforkey where codarq = 1010564 and referen = 0;
insert into db_sysforkey values(1010564,1011288,1,760,0);
delete from db_sysforkey where codarq = 1010564 and referen = 0;
insert into db_sysforkey values(1010564,1011289,1,1010561,0);
delete from db_sysprikey where codarq = 1010564;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010564,1011287,1,1011288);
insert into db_sysindices values(1008571,'conlancamcomplementorecurso_codlan_in',1010564,'0');
insert into db_syscadind values(1008571,1011288,1);
insert into db_sysindices values(1008572,'conlancamcomplementorecurso_complemento_in',1010564,'0');
insert into db_syscadind values(1008572,1011289,1);


create table contabilidade.conlancamcomplementorecurso (
    o201_sequencial serial primary key not null,
    o201_codlan integer not null,
    o201_complemento integer not null
);
create index conlancamcomplementorecurso_codlan_in on conlancamcomplementorecurso(o201_codlan);
create index conlancamcomplementorecurso_complemento_in on conlancamcomplementorecurso(o201_complemento);

alter table conlancamcomplementorecurso add constraint conlancamcomplementorecurso_codlan_fk foreign key (o201_codlan) references conlancam;
alter table conlancamcomplementorecurso add constraint conlancamcomplementorecurso_complemento_fk foreign key (o201_complemento) references complementofonterecurso;

SQL_UP
);

    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop table if exists contabilidade.conlancamcomplementorecurso;
delete from db_sysforkey where codarq = 1010564;
delete from db_sysindices where codarq = 1010564;
delete from db_syscadind where codcam in (1011287,1011288,1011289);
delete from db_sysprikey where codarq = 1010564;
delete from db_syssequencia where codsequencia = 1000912;
delete from db_sysarqcamp where codarq = 1010564;
delete from db_sysarqmod where codarq = 1010564;
delete from db_sysarquivo where codarq = 1010564;
delete from db_syscampo where codcam in (1011287,1011288,1011289);



SQL_DOWN
);
    }
}
