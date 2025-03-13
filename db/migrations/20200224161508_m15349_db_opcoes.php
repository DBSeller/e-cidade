<?php

use Classes\PostgresMigration;

class M15349DbOpcoes extends PostgresMigration
{

    public function up (){

        $sql = <<<SQL
insert into db_sysarquivo values (1010527, 'db_opcoes', 'Cadastro de opções/parametros', 'db153', '2020-02-24', 'Cadastro de opções/parametros', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (7,1010527);
insert into db_syscampo values(1011062,'db153_sequencial','int4','Codigo sequencial','0', 'Codigo sequencial',4,'f','f','f',1,'text','Codigo sequencial');
insert into db_syscampo values(1011063,'db153_ano','int4','Ano da opção','0', 'Ano',8,'f','f','f',1,'text','Ano');
insert into db_syscampo values(1011064,'db153_instit','int4','Código da Instituição','0', 'Código da Instituição',2,'t','f','f',0,'text','Código da Instituição');
insert into db_syscampodep values(1011064,'449');
insert into db_syscampo values(1011065,'db153_nome','text','Nome da opção','', 'Nome da opção',1,'f','t','f',0,'text','Nome da opção');
insert into db_syscampo values(1011066,'db153_valor','text','Valor da opção','', 'Valor da opção',1,'f','t','f',0,'text','Valor da opção');
insert into db_sysarqarq  values(0,1010527);
insert into db_sysarqcamp values(1010527,1011062,1,0);
insert into db_sysarqcamp values(1010527,1011063,2,0);
insert into db_sysarqcamp values(1010527,1011064,3,0);
insert into db_sysarqcamp values(1010527,1011065,4,0);
insert into db_sysarqcamp values(1010527,1011066,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010527,1011062,1,1011065);
insert into db_sysforkey  values(1010527,1011064,1,83,0);
insert into db_sysindices values(1008530,'db_opcoes_instit_in',1010527,'0');
insert into db_syscadind  values(1008530,1011064,1);
insert into db_sysindices values(1008531,'db_opcoes_nome_in',1010527,'0');
insert into db_syscadind  values(1008531,1011065,1);
insert into db_sysindices values(1008532,'db_opcoes_ano_in',1010527,'0');
insert into db_syscadind  values(1008532,1011063,1);
insert into db_sysindices values(1008533,'db_opcoes_ano_instit_nome_in',1010527,'1');
insert into db_syscadind  values(1008533,1011063,1);
insert into db_syscadind  values(1008533,1011064,2);
insert into db_syscadind  values(1008533,1011065,3);
insert into db_syssequencia values(1000881, 'db_opcoes_db153_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000881 where codarq = 1010527 and codcam = 1011062;
SQL;
        $this->execute($sql);

        $this->execute($this->criarTabela());

    }

    public function down (){

        $sql = <<<SQL

create table if not exists w_deletar_dd as select * from db_sysarqcamp  where codarq = 1010527;
delete from db_sysarqarq  where codarq = 1010527;
delete from db_sysarqcamp where codarq = 1010527;
delete from db_sysprikey  where codarq = 1010527;
delete from db_sysforkey  where codarq = 1010527;
delete from db_sysarqmod  where codarq = 1010527;
delete from db_sysindices  where codarq = 1010527;
delete from db_sysarquivo  where codarq = 1010527;
delete from db_syssequencia  where nomesequencia = 'db_opcoes_db153_sequencial_seq';

delete from db_syscadind where codcam in (select codcam from w_deletar_dd);
delete from db_syscampodep where codcam in (select codcam from w_deletar_dd);
delete from db_syscampo   where codcam in (select codcam from w_deletar_dd);
drop table w_deletar_dd;

DROP TABLE IF EXISTS db_opcoes CASCADE;
DROP SEQUENCE IF EXISTS db_opcoes_db153_sequencial_seq;

SQL;
        $this->execute($sql);
    }

    private function criarTabela(){

        $sql = <<<SQL
CREATE SEQUENCE configuracoes.db_opcoes_db153_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE configuracoes.db_opcoes(
db153_sequencial		int4 default 0,
db153_instit		int4 ,
db153_ano		int4 ,
db153_nome		text,
db153_valor		text,
CONSTRAINT db_opcoes_sequ_pk PRIMARY KEY (db153_sequencial));

ALTER TABLE configuracoes.db_opcoes
ADD CONSTRAINT db_opcoes_instit_fk FOREIGN KEY (db153_instit)
REFERENCES configuracoes.db_config;

CREATE  INDEX db_opcoes_instit_in ON configuracoes.db_opcoes(db153_instit);
CREATE  INDEX db_opcoes_nome_in ON configuracoes.db_opcoes(db153_nome);
CREATE  INDEX db_opcoes_ano_in ON configuracoes.db_opcoes(db153_ano);
CREATE UNIQUE INDEX db_opcoes_ano_instit_nome_in ON configuracoes.db_opcoes(db153_ano,db153_instit,db153_nome);
SQL;

        return $sql;


    }
}
