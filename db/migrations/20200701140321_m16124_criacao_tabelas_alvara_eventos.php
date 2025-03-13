<?php

use Classes\PostgresMigration;

class M16124CriacaoTabelasAlvaraEventos extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_sysarquivo values (1010593, 'alvaraevento', 'Tabela que guarda um alvará de eventos', 'q170', '2020-07-01', 'Alvará de Eventos', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010593);
            insert into db_sysarquivo values (1010594, 'mensagempadraoalvaraevento', 'Tabela que salva uma mensagem padrão para o alvará de eventos', 'q171', '2020-07-01', 'Mensagem Padrão', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010594);
            insert into db_syscampo values(1011634,'q170_codigo','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011678,'q170_tipoalvara','int8','Tipo de alvará do alvará de eventos','0', 'Tipo de Alvará',1,'f','f','f',1,'text','Tipo de Alvará');
            insert into db_syscampo values(1011635,'q170_ordemservico','int8','Ordem de serviço do alvara de eventos','0', 'Ordem de Serviço',1,'f','f','f',1,'text','Ordem de Serviço');
            insert into db_syscampo values(1011636,'q170_certidaobombeiro','varchar(50)','Certidão de bombeiros do alvará de eventos','', 'Certidão de bombeiros',50,'f','t','f',0,'text','Certidão de bombeiros');
            insert into db_syscampo values(1011637,'q170_dataemissao','date','Data de emissão do alvará de eventos','null', 'Data de Emissão',10,'f','f','f',1,'text','Data de Emissão');
            insert into db_syscampo values(1011679,'q170_estimativapublico','int8','Estimativa de público do evento','0', 'Estimativa de público',1,'f','f','f',1,'text','Estimativa de público');
            insert into db_syscampo values(1011638,'q170_observacao','text','Observação do alvará de eventos','', 'Observação',1,'f','t','f',0,'text','Observação');
            insert into db_syscampo values(1011639,'q171_codigo','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011640,'q171_descricao','varchar(100)','Descrição da mensagem padrão','', 'Descrição',100,'f','t','f',0,'text','Descrição');
            insert into db_syscampo values(1011641,'q171_mensagem','text','Corpo da mensagem padrão','', 'Mensagem',1,'f','t','f',0,'text','Mensagem');
            delete from db_sysarqcamp where codarq = 1010593;
            insert into db_sysarqcamp values(1010593,1011634,1,0);
            insert into db_sysarqcamp values(1010593,1011678,2,0);
            insert into db_sysarqcamp values(1010593,1011635,3,0);
            insert into db_sysarqcamp values(1010593,1011636,4,0);
            insert into db_sysarqcamp values(1010593,1011637,5,0);
            insert into db_sysarqcamp values(1010593,1011679,6,0);
            insert into db_sysarqcamp values(1010593,1011638,7,0);
            delete from db_sysprikey where codarq = 1010593;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010593,1011634,1,1011634);
            delete from db_sysarqcamp where codarq = 1010594;
            insert into db_sysarqcamp values(1010594,1011639,1,0);
            insert into db_sysarqcamp values(1010594,1011640,2,0);
            insert into db_sysarqcamp values(1010594,1011641,3,0);
            delete from db_sysprikey where codarq = 1010594;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010594,1011639,1,1011639);

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysprikey where codarq in (1010593, 1010594);
            delete from db_sysarqcamp where codarq in (1010593, 1010594);
            delete from db_syscampo where codcam in (1011634, 1011635, 1011636, 1011637, 1011638, 1011639, 1011640, 1011641, 1011678, 1011679);
            delete from db_sysarqmod where codarq in (1010593, 1010594);
            delete from db_sysarquivo where codarq in (1010593, 1010594);
SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL

            CREATE SEQUENCE issqn.alvaraevento_q170_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE issqn.alvaraevento(
                q170_codigo            integer NOT NULL default nextval('issqn.alvaraevento_q170_codigo_seq'),
                q170_tipoalvara        integer NOT NULL,
                q170_ordemservico      integer NOT NULL,
                q170_certidaobombeiro  varchar(50) NOT NULL,
                q170_dataemissao       date NOT NULL default CURRENT_TIMESTAMP,
                q170_estimativapublico integer,
                q170_observacao        text,
                CONSTRAINT alvaraevento_sequ_pk PRIMARY KEY (q170_codigo)
            );

            ALTER TABLE issqn.alvaraevento
                ADD CONSTRAINT alvaraevento_ordemservico_fk FOREIGN KEY (q170_ordemservico)
                REFERENCES issqn.ordemservico(q168_codigo),
                ADD CONSTRAINT alvaraevento_isstipoalvara_fk FOREIGN KEY (q170_tipoalvara)
                REFERENCES issqn.isstipoalvara(q98_sequencial);

            select configuracoes.fc_auditoria_cria_funcao('issqn.alvaraevento');

            CREATE SEQUENCE issqn.mensagempadraoalvaraevento_q171_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE issqn.mensagempadraoalvaraevento(
                q171_codigo    integer NOT NULL default nextval('issqn.mensagempadraoalvaraevento_q171_codigo_seq'),
                q171_descricao varchar(100) NOT NULL,
                q171_mensagem  text NOT NULL,
                CONSTRAINT mensagempadraoalvaraevento_sequ_pk PRIMARY KEY (q171_codigo)
            );

            select configuracoes.fc_auditoria_cria_funcao('issqn.mensagempadraoalvaraevento');

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            select configuracoes.fc_auditoria_remove_funcao('issqn.alvaraevento');
            select configuracoes.fc_auditoria_remove_funcao('issqn.mensagempadraoalvaraevento');

            DROP TABLE issqn.alvaraevento;
            DROP TABLE issqn.mensagempadraoalvaraevento;

            DROP SEQUENCE issqn.alvaraevento_q170_codigo_seq;
            DROP SEQUENCE issqn.mensagempadraoalvaraevento_q171_codigo_seq;
SQL
        );
    }
}
