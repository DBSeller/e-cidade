<?php

use Classes\PostgresMigration;

class M15349Orcparamreltemplate extends PostgresMigration
{
    public function up (){

        $sql = <<<SQL
insert into db_sysarquivo values (1010528, 'orcparamreltemplate', 'Template relatório', 'o163', '2020-02-25', 'Template relatório', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010528);
insert into db_syscampo values(1011067,'o163_sequecial','int4','Codigo sequencial','0', 'Codigo sequencial',8,'f','f','f',1,'text','Codigo sequencial');
insert into db_syscampo values(1011068,'o163_orcparamrel','int4','Código Relatório','0', 'Código Relatório',8,'f','f','f',1,'text','Código Relatório');
insert into db_syscampodep values(1011068,'5705');
insert into db_syscampo values(1011069,'o163_template','oid','Template relatório','', 'Template relatório',1,'f','f','f',1,'text','Template relatório');
insert into db_sysarqcamp values(1010528,1011067,1,0);
insert into db_sysarqcamp values(1010528,1011068,2,0);
insert into db_sysarqcamp values(1010528,1011069,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010528,1011067,1,1011068);
insert into db_sysforkey values(1010528,1011068,1,901,0);
insert into db_sysindices values(1008534,'orcparamreltemplate_orcparamrel_in',1010528,'0');
insert into db_syscadind values(1008534,1011068,1);
insert into db_sysindices values(1008536,'orcparamreltemplate_orcparamrel_unique_in',1010528,'1');
insert into db_syscadind values(1008536,1011068,1);
insert into db_syssequencia values(1000882, 'orcparamreltemplate_o163_sequecial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000882 where codarq = 1010528 and codcam = 1011067;

SQL;

        $this->execute($sql);

        $this->execute($this->criaTabela());
    }

    public function down (){

        $sql = <<<SQL

        create table if not exists w_deletar_dd_15349 as select * from db_sysarqcamp  where codarq = 1010528;
        delete from db_sysarqarq  where codarq = 1010528;
        delete from db_sysarqcamp where codarq = 1010528;
        delete from db_sysprikey  where codarq = 1010528;
        delete from db_sysforkey  where codarq = 1010528;
        delete from db_sysarqmod  where codarq = 1010528;
        delete from db_sysindices  where codarq = 1010528;
        delete from db_sysarquivo  where codarq = 1010528;
        delete from db_syssequencia  where nomesequencia = 'orcparamreltemplate_o163_sequecial_seq';
        delete from db_syscadind where codcam in (select codcam from w_deletar_dd_15349);
        delete from db_syscampodep where codcam in (select codcam from w_deletar_dd_15349);
        delete from db_syscampo   where codcam in (select codcam from w_deletar_dd_15349);
        drop table w_deletar_dd_15349;

        DROP TABLE IF EXISTS orcparamreltemplate CASCADE;
        DROP SEQUENCE IF EXISTS orcparamreltemplate_o163_sequecial_seq;

SQL;
        $this->execute($sql);

    }

    private function criaTabela(){

        $sql = <<<SQL
CREATE SEQUENCE orcamento.orcparamreltemplate_o163_sequecial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE orcamento.orcparamreltemplate(
o163_sequecial		int4 default 0,
o163_orcparamrel	int4 default 0,
o163_template		oid,
CONSTRAINT orcparamreltemplate_sequ_pk PRIMARY KEY (o163_sequecial));

ALTER TABLE orcamento.orcparamreltemplate
ADD CONSTRAINT orcparamreltemplate_orcparamrel_fk FOREIGN KEY (o163_orcparamrel)
REFERENCES orcamento.orcparamrel;

CREATE  INDEX orcparamreltemplate_orcparamrel_in ON orcamento.orcparamreltemplate(o163_orcparamrel);
CREATE UNIQUE INDEX orcparamreltemplate_orcparamrel_unique_in ON orcamento.orcparamreltemplate(o163_orcparamrel);

SQL;

        return $sql;
    }
}
