<?php

use Classes\PostgresMigration;

class M15764CampoControleInternoRequisicao extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_syscampo values(1011257,'la49_numerocontroleinterno','bool','Em alguns clientes há requisições com números definidos pelos próprios clientes. Esse campo serve para ativar ou desativar o uso deste número interno para se referir a uma requisição.','false', 'Número de Controle Interno da Requisição',1,'f','f','f',5,'text','Número de Controle Interno da Requisição');
            insert into db_sysarqcamp values(2909,1011257,9,0);
            insert into db_sysarquivo values (1010560, 'numerocontroleinternorequisicao', 'vincula requisição com número de controle interno da requisição do cliente.', 'la65', '2020-05-06', 'Número de Controle Interno da Requisição', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (67,1010560);
            delete from db_sysarqarq where codarq = 1010560;
            insert into db_sysarqarq values(0,1010560);
            insert into db_syscampo values(1011260,'la65_sequencial','int4','chave primária da tabela.','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011261,'la65_numero','int4','Número de Controle Interno da Requisição.','0', 'Número de Controle Interno da Requisição',10,'f','f','f',1,'text','Número de Controle Interno da Requisição');
            insert into db_syscampo values(1011262,'la65_ano','int4','Ano cujo número de controle interno se refere.','0', 'Ano do Número de Controle Interno',4,'f','f','f',1,'text','Ano do Número de Controle Interno');
            insert into db_syscampo values(1011263,'la65_requisicao','int4','Chave estrangeira referente a requisição.','0', 'Requisição',10,'f','f','f',1,'text','Requisição');
            delete from db_sysarqcamp where codarq = 1010560;
            insert into db_sysarqcamp values(1010560,1011260,1,0);
            insert into db_sysarqcamp values(1010560,1011261,2,0);
            insert into db_sysarqcamp values(1010560,1011262,3,0);
            insert into db_sysarqcamp values(1010560,1011263,4,0);
            delete from db_sysprikey where codarq = 1010560;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010560,1011260,1,1011260);
            delete from db_sysforkey where codarq = 1010560 and referen = 0;
            insert into db_sysforkey values(1010560,1011263,1,2773,0);
            insert into db_sysindices values(1008567,'numerocontroleinternorequisicao_in',1010560,'1');
            insert into db_syscadind values(1008567,1011261,2);
            insert into db_syscadind values(1008567,1011263,3);
            insert into db_syscadind values(1008567,1011262,4);

            CREATE SEQUENCE laboratorio.numerocontroleinternorequisicao_la65_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            create table if not exists laboratorio.numerocontroleinternorequisicao (
                la65_sequencial int4 not null default nextval('numerocontroleinternorequisicao_la65_sequencial_seq'),
                la65_numero int4 not null,
                la65_ano int4 not null,
                la65_requisicao int4 not null,
                constraint numerocontroleinternorequisicao_sequ_pk primary key (la65_sequencial),
                constraint numerocontroleinternorequisicao_sequ_fk foreign key (la65_requisicao) references lab_requisicao(la22_i_codigo)
            );
            create index numerocontroleinternorequisicao_in ON numerocontroleinternorequisicao(la65_numero, la65_requisicao, la65_ano);

            alter table laboratorio.lab_parametros add column la49_numerocontroleinterno boolean not null default false;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_sysarqcamp where codcam = 1011257;
            delete from db_sysarqcamp where codarq = 1010560;
            delete from db_sysprikey where codarq = 1010560;
            delete from db_sysforkey where codarq = 1010560 and referen = 0;
            delete from db_sysforkey where codarq = 1010560;
            delete from db_sysforkey where codcam = 1011263;
            delete from db_syscadind where codind = 1008567;
            delete from db_sysindices where codind = 1008567;
            delete from db_syscampo where codcam = 1011257;
            delete from db_sysarqmod where codarq = 1010560;
            delete from db_sysarquivo where codarq = 1010560;
            delete from db_sysarqarq where codarq = 1010560;
            delete from db_syscampo where codcam = 1011260;
            delete from db_syscampo where codcam = 1011261;
            delete from db_syscampo where codcam = 1011262;
            delete from db_syscampo where codcam = 1011263;
            drop table if exists laboratorio.numerocontroleinternorequisicao;
            alter table laboratorio.lab_parametros drop column if exists la49_numerocontroleinterno;
            drop sequence if exists laboratorio.numerocontroleinternorequisicao_la65_sequencial_seq;
        ";

        $this->execute($sql);
    }
}
