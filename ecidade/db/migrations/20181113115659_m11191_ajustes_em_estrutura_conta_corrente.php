<?php

use Classes\PostgresMigration;

class M11191AjustesEmEstruturaContaCorrente extends PostgresMigration
{
    public function up()
    {


        $this->criarDicionarioDadosConPlanoAtributos();

    }

    public function down()
    {


        $this->execute(<<<SQL_DOWN

alter table conplanoatributosaldo drop column c125_conplanosistema;
drop index conplanoatributosaldo_anousu_in;
drop index conplanoatributosaldo_mesusu_in;

delete from db_syssequencia where codsequencia = 1000783;
delete from db_syscadind where codind in (1008353, 1008354, 1008355);
delete from db_sysindices where codind in (1008353, 1008354, 1008355);
delete from db_sysforkey where codarq = 1010339;
delete from db_sysprikey where codarq = 1010339;
delete from db_sysarqcamp where codarq = 1010339;
delete from db_syscampo where codcam in (1010092, 1010093, 1010094, 1010095, 1010096, 1010097, 1010090);
delete from db_sysarqmod where codarq = 1010339;
delete from db_sysarquivo where codarq = 1010339;


alter table conplanoatributolancamentos drop column c124_conplanosistema;
drop index conplanoatributolancamentos_data_in;
drop index conplanoatributolancamentos_lancamento_in;

delete from db_syscadind where codind in (1008358, 1008357, 1008356);
delete from db_sysindices where codind in (1008358, 1008357, 1008356);
delete from db_sysforkey where codcam in (1010098);
delete from db_sysarqcamp where codcam = 1010098;
delete from db_syscampo where codcam = 1010098;

delete from db_syscadind where codind in (1008359);
delete from db_sysindices where codind in (1008359);
delete from db_sysforkey where codcam in (1010101);
delete from db_sysarqcamp where codcam = 1010101;
delete from db_syscampo where codcam = 1010101;

alter table conplanoatributosaldo drop column c125_instit;



SQL_DOWN
);
    }

    private function criarDicionarioDadosConPlanoAtributos()
    {

        $this->execute(
            <<<SQL_UP_ATRIBUTOS

alter table infocomplementarvalor alter column c123_valor type varchar;

insert into db_sysarquivo values (1010339, 'conplanoatributosaldo', 'Saldo do conta corrente por mês e ano', 'c125', '2018-11-13', 'conplanoatributosaldo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010339);
insert into db_syscampo values(1010092,'c125_anousu','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano');
insert into db_syscampo values(1010093,'c125_mesusu','int4','Mês','0', 'Mês',2,'f','f','f',1,'text','Mês');
insert into db_syscampo values(1010094,'c125_hashcontaatributos','varchar(200)','Hash de Atributos','', 'Hash de Atributos',200,'f','t','f',0,'text','Hash de Atributos');
insert into db_syscampo values(1010095,'c125_valor','float4','Valor Monetário','0', 'Valor Monetário',10,'f','f','f',4,'text','Valor Monetário');
insert into db_syscampo values(1010096,'c125_natureza','varchar(1)','Natureza que pode ser D (débito) ou C (crédito)','', 'Natureza do Saldo',1,'f','t','f',0,'text','Natureza do Saldo');
insert into db_syscampo values(1010097,'c125_tipo','int4','Define se o saldo é valor calculado ou implantado.','0', 'Tipo de Registro',10,'f','f','f',1,'text','Tipo de Registro');
insert into db_syscampo values(1010090,'c125_conplanosistema','int4','Sistema do Saldo .','0', 'Sistema do Saldo ',10,'f','f','f',1,'text','Sistema do Saldo ');
delete from db_sysarqcamp where codarq = 1010339;
insert into db_sysarqcamp values(1010339,1009650,1,0);
insert into db_sysarqcamp values(1010339,1010092,2,0);
insert into db_sysarqcamp values(1010339,1010093,3,0);
insert into db_sysarqcamp values(1010339,1010094,4,0);
insert into db_sysarqcamp values(1010339,1010095,5,0);
insert into db_sysarqcamp values(1010339,1010096,6,0);
insert into db_sysarqcamp values(1010339,1010097,7,0);
insert into db_sysarqcamp values(1010339,1010090,8,0);
delete from db_sysprikey where codarq = 1010339;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010339,1009650,1,1010094);
delete from db_sysforkey where codarq = 1010339 and referen = 0;
insert into db_sysforkey values(1010339,1010090,1,1010257,0);
insert into db_sysindices values(1008353,'conplanoatributosaldo_conplanosistema_in',1010339,'0');
insert into db_syscadind values(1008353,1010090,1);
insert into db_sysindices values(1008354,'conplanoatributosaldo_mesusu_in',1010339,'0');
insert into db_syscadind values(1008354,1010093,1);
insert into db_sysindices values(1008355,'conplanoatributosaldo_anousu_in',1010339,'0');
insert into db_syscadind values(1008355,1010092,1);
insert into db_syssequencia values(1000783, 'conplanoatributosaldo_c125_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000783 where codarq = 1010339 and codcam = 1009650;
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
            alter table conplanoatributosaldo disable trigger all;
alter table conplanoatributosaldo add column c125_conplanosistema integer default 1;
alter table conplanoatributosaldo add constraint conplanoatributosaldo_conplanosistema_fk foreign key (c125_conplanosistema) references contabilidade.conplanosistema NOT VALID;
--update conplanoatributosaldo set c125_conplanosistema = 1;
--alter table conplanoatributosaldo alter column c125_conplanosistema set not null;
alter table conplanoatributosaldo enable trigger all;
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
            
create index conplanoatributosaldo_conplanosistema_in on conplanoatributosaldo(c125_conplanosistema);
create index conplanoatributosaldo_anousu_in on conplanoatributosaldo(c125_anousu);
create index conplanoatributosaldo_mesusu_in on conplanoatributosaldo(c125_mesusu);
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
insert into db_syscampo values(1010098,'c124_conplanosistema','int4','Código do Sistema','0', 'Código do Sistema',10,'f','f','f',1,'text','Código do Sistema');
delete from db_sysarqcamp where codarq = 1010259;
insert into db_sysarqcamp values(1010259,1009618,1,1000716);
insert into db_sysarqcamp values(1010259,1009626,2,0);
insert into db_sysarqcamp values(1010259,1009619,3,0);
insert into db_sysarqcamp values(1010259,1009620,4,0);
insert into db_sysarqcamp values(1010259,1009621,5,0);
insert into db_sysarqcamp values(1010259,1009633,6,0);
insert into db_sysarqcamp values(1010259,1010098,7,0);
delete from db_sysforkey where codarq = 1010259 and referen = 0;
insert into db_sysforkey values(1010259,1010098,1,1010257,0);
insert into db_sysindices values(1008356,'conplanoatributolancamentos_lancamento_in',1010259,'0');
insert into db_syscadind values(1008356,1009626,1);
insert into db_sysindices values(1008357,'conplanoatributolancamentos_data_in',1010259,'0');
insert into db_syscadind values(1008357,1009633,1);
insert into db_sysindices values(1008358,'conplanoatributolancamentos_conplanosistema_in',1010259,'0');
insert into db_syscadind values(1008358,1010098,1);
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
            alter table conplanoatributolancamentos disable trigger all;
alter table conplanoatributolancamentos add column c124_conplanosistema integer default 1;
alter table conplanoatributolancamentos add constraint conplanoatributolancamentos_conplanosistema_fk foreign key (c124_conplanosistema) references contabilidade.conplanosistema not valid; 
create index conplanoatributolancamentos_conplanosistema_in on conplanoatributolancamentos(c124_conplanosistema);
create index conplanoatributolancamentos_lancamento_in on conplanoatributolancamentos(c124_lancamento);
create index conplanoatributolancamentos_data_in on conplanoatributolancamentos(c124_data);
alter table conplanoatributolancamentos enable trigger all;
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
insert into db_syscampo values(1010101,'c125_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
delete from db_sysarqcamp where codarq = 1010339;
insert into db_sysarqcamp values(1010339,1009650,1,1000783);
insert into db_sysarqcamp values(1010339,1010092,2,0);
insert into db_sysarqcamp values(1010339,1010093,3,0);
insert into db_sysarqcamp values(1010339,1010094,4,0);
insert into db_sysarqcamp values(1010339,1010095,5,0);
insert into db_sysarqcamp values(1010339,1010096,6,0);
insert into db_sysarqcamp values(1010339,1010097,7,0);
insert into db_sysarqcamp values(1010339,1010090,8,0);
insert into db_sysarqcamp values(1010339,1010101,9,0);
delete from db_sysforkey where codarq = 1010339 and referen = 0;
insert into db_sysforkey values(1010339,1010101,1,83,0);
insert into db_sysindices values(1008359,'conplanoatributosaldo_instit_in',1010339,'0');
insert into db_syscadind values(1008359,1010101,1);
SQL_UP_ATRIBUTOS
        );
        $this->execute(
            <<<SQL_UP_ATRIBUTOS
            alter table conplanoatributosaldo disable trigger all;
alter table conplanoatributosaldo add column c125_instit integer default null;
alter table conplanoatributosaldo add constraint conplanoatributosaldo_instit_fk foreign key (c125_instit) references configuracoes.db_config not valid;
alter table conplanoatributosaldo enable trigger all;
SQL_UP_ATRIBUTOS
        );
    }
}
