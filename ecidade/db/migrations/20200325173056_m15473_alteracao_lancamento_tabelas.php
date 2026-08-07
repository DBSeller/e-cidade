<?php

use Classes\PostgresMigration;

class M15473AlteracaoLancamentoTabelas extends PostgresMigration
{
    public function up() {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011162,'c135_sequencial','int4','código','0', 'código',10,'f','f','f',1,'text','código');
insert into db_syscampo values(1011163,'c135_codlaninclusao','int4','código de lançamento original','0', 'código de lançamento inclusao',10,'f','f','f',1,'text','código de lançamento inclusao');
insert into db_syscampo values(1011164,'c135_codlanestorno','int4','código de lançamento de estorno','0', 'código de lançamento de estorno',10,'f','f','f',1,'text','código de lançamento de estorno');
insert into db_syscampo values(1011165,'c135_codlannovo','int4','código de lançamento novo','0', 'código de lançamento novo',10,'f','f','f',1,'text','código de lançamento novo');

delete from db_syscampodep where codcam = 1011163;
delete from db_syscampodef where codcam = 1011163;
insert into db_sysarquivo values (1010544, 'conlancamretificacao', 'tabela que armazena os lançamento envolvidos em uma retificacao', 'c135', '2020-03-25', 'conlancamretificacao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010544);
delete from db_sysarqcamp where codarq = 1010544;
insert into db_sysarqcamp values(1010544,1011162,1,0);
insert into db_sysarqcamp values(1010544,1011163,2,0);
insert into db_sysarqcamp values(1010544,1011164,3,0);
insert into db_sysarqcamp values(1010544,1011165,4,0);
delete from db_sysprikey where codarq = 1010544;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010544,1011162,1,1011163);
delete from db_sysforkey where codarq = 1010544 and referen = 0;
insert into db_sysforkey values(1010544,1011163,1,760,0);
delete from db_sysforkey where codarq = 1010544 and referen = 0;
insert into db_sysforkey values(1010544,1011164,1,760,0);
delete from db_sysforkey where codarq = 1010544 and referen = 0;
insert into db_sysforkey values(1010544,1011165,1,760,0);
insert into db_sysindices values(1008564,'conlancamretificacao_codlaninclusao_in',1010544,'0');
insert into db_syscadind values(1008564,1011163,1);
insert into db_sysindices values(1008565,'conlancamretificacao_codlanestorno_in',1010544,'0');
insert into db_syscadind values(1008565,1011164,1);
insert into db_sysindices values(1008566,'conlancamretificacao_codlannovo_in',1010544,'0');
insert into db_syscadind values(1008566,1011165,1);
insert into db_syssequencia values(1000896, 'conlancamretificacao_c135_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000896 where codarq = 1010544 and codcam = 1011162;

insert into conhistdoc values (3001, 'Estorno Lançamento Genérico', 3001);
insert into conhistdoc values (5122, 'ESTORNO AJUSTE DE SALDO CONTÁBIL PATRIMONIAL', 3001);
insert into conhistdoc values (8066, 'ESTORNO DÍVIDA ATIVA DE CURTO/LONGO PRAZO', 3001);
delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (3000, 512, 806);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 3000, 3001);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 512, 5122);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 806, 8066);

drop table if exists conlancamretificacao;
create table conlancamretificacao (
    c135_sequencial serial not null primary key,
    c135_codlaninclusao integer not null,
    c135_codlanestorno integer not null,
    c135_codlannovo integer not null
);


create index conlancamretificacao_codlaninclusao_in on conlancamretificacao(c135_codlaninclusao);
create index conlancamretificacao_codlanestorno_in on conlancamretificacao(c135_codlanestorno);
create index conlancamretificacao_codlannovo_in on conlancamretificacao(c135_codlannovo);

ALTER TABLE conlancamretificacao ADD CONSTRAINT conlancamretificacao_codlaninclusao_fk FOREIGN KEY (c135_codlaninclusao) REFERENCES conlancam;
ALTER TABLE conlancamretificacao ADD CONSTRAINT conlancamretificacao_codlanestorno_fk FOREIGN KEY (c135_codlanestorno) REFERENCES conlancam;
ALTER TABLE conlancamretificacao ADD CONSTRAINT conlancamretificacao_codlannovo_fk FOREIGN KEY (c135_codlannovo) REFERENCES conlancam;

SQL_UP
);
    }

    public function down() {

        $this->execute(<<<SQL_DOWN

delete from db_syssequencia where codsequencia = 1000896;
delete from db_syscadind where codcam in (1011162,1011163, 1011164, 1011165);
delete from db_sysindices where codarq = 1010544;
delete from db_sysforkey where codarq = 1010544;
delete from db_sysprikey where codarq = 1010544;
delete from db_sysarqcamp where codarq = 1010544;
delete from db_sysarqmod where codarq = 1010544;
delete from db_sysarquivo where codarq = 1010544;
delete from db_syscampo where codcam in (1011162,1011163, 1011164, 1011165);


delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (3000, 512, 806);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 3000, null);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 512, null);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 806, null);
delete from conhistdoc where c53_coddoc in (3001, 5122, 8066);

drop table if exists conlancamretificacao;

SQL_DOWN
        );
    }
}
