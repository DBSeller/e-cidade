<?php

use Classes\PostgresMigration;

class M11191VinculoContaCorrente extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL
CREATE SEQUENCE conplanosistemaatributos_c129_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE contabilidade.conplanosistemaatributos(
c129_sequencial       int8 default 0,
c129_conplanosistema  int8 DEFAULT 0,
c129_conplanoinfocomplementar int8 DEFAULT 0,
c129_ordem int8,
CONSTRAINT conplanosistemaatributos_sequ_pk PRIMARY KEY (c129_sequencial));

ALTER TABLE conplanosistemaatributos
ADD CONSTRAINT conplanosistemaatributos_conplanosistema_fk FOREIGN KEY (c129_conplanosistema)
REFERENCES conplanosistema;
ALTER TABLE conplanosistemaatributos
ADD CONSTRAINT conplanosistemaatributos_conplanoinfocomplementar_fk FOREIGN KEY (c129_conplanoinfocomplementar)
REFERENCES conplanoinfocomplementar;
CREATE  INDEX conplanosistemaatributos_conplanosistema_in ON conplanosistemaatributos(c129_conplanosistema);
CREATE  INDEX conplanosistemaatributos_conplanoinfocomplementar_in ON conplanosistemaatributos(c129_conplanoinfocomplementar);
alter table conplanosistema add c122_tipo integer;
update conplanosistema set c122_tipo = 1;
alter table conplanosistema alter c122_descricao type varchar;
insert into db_syssequencia values(1000782, 'conplanosistemaatributos_c129_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
insert into db_syscampo values(1010079,'c122_tipo','int4','Tipo de sistema. 1 - SICONF, 2 - Conta Corrente','0', 'Tipo de sistema',10,'f','f','f',1,'text','Tipo de sistema');
insert into db_syscampodef values(1010079,'1','Siconf');
insert into db_syscampodef values(1010079,'2','Conta Corrente');
insert into db_sysarqcamp values(1010257,1010079,3,0);
insert into db_syscampo values (1010083, 'c129_sequencial',         'int4', 'Sequencial',     '0', 'Sequencial',                    10,'f','f','f', 1, 'text', 'Sequencial');
insert into db_syscampo values (1010085, 'c129_conplanosistema',    'int4', 'Código do Sistema',     '0', 'Código do Sistema',      10,'f','f','f', 1, 'text', 'Código do Sistema');
insert into db_syscampo values (1010086,'c129_conplanoinfocomplementar','int4','Informações Complementar','0', 'Informação Complementar',10,'f','f','f',1,'text','Informação Complementar');
insert into db_syscampo values(1010089,'c129_ordem','int4','Ordem de execução','0', 'Ordem',10,'f','f','f',1,'text','Ordem');
insert into db_syscampodep values(1010086,'1009615');
insert into db_sysarquivo  values (1010338, 'conplanosistemaatributos', 'Atributos do sistema de conta (conta corrente)', 'c129', '2018-11-09', 'Atributos do sistema de conta', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod   values (32,1010338);
insert into db_sysarqcamp values(1010338,1010083,2,0);
insert into db_sysarqcamp values(1010338,1010085,2,0);
insert into db_sysarqcamp values(1010338,1010086,3,0);
insert into db_sysarqcamp values(1010338,1010089,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010338,1010083,1,1010085);
insert into db_sysforkey values(1010338,1010086,1,1010256,0);
insert into db_sysforkey values(1010338,1010085,1,1010257,0);
insert into db_sysindices values(1008351, 'conplanosistemaatributos_conplanosistema_in',         1010338, 0);
insert into db_sysindices values(1008352, 'conplanosistemaatributos_conplanoinfocomplementar_in',1010338, 0);
SQL;
        $this->execute($sSql);

    }

    public function down()
    {

        $sSql = <<<SQL
delete from db_sysarqcamp where codarq = 1010338;
delete from db_sysprikey  where codarq = 1010338;
delete from db_sysforkey  where codarq = 1010338;
delete from db_sysarqmod  where codarq = 1010338;
delete from db_sysindices where codind = 1008351;
delete from db_sysindices where codind = 1008352;
delete from db_sysarquivo where codarq = 1010338;

delete from db_sysarqcamp   where codcam in (1010083, 1010085, 1010086, 1010079, 1010089);
delete from db_syscampodef  where codcam in (1010083, 1010085, 1010086, 1010079, 1010089);
delete from db_syscampodep  where codcam in (1010083, 1010085, 1010086, 1010079, 1010089);
delete from db_syscampo     where codcam in (1010083, 1010085, 1010086, 1010079, 1010089);
delete from db_syssequencia where codsequencia = 1000782;

alter table conplanosistema drop column c122_tipo; --
alter table conplanosistema alter c122_descricao type varchar(20); --

drop sequence conplanosistemaatributos_c129_sequencial_seq;
drop table conplanosistemaatributos;
SQL;
        $this->execute($sSql);

    }
}
