<?php

use Classes\PostgresMigration;

class M16124CriacaoTabelasOrdemServico extends PostgresMigration
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

            insert into db_sysarquivo values (1010591, 'ordemservico', 'Tabela que guarda uma ordem de serviço para criação de alvará de eventos', 'q168', '2020-06-29', 'Ordem de Serviço', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010591);
            insert into db_sysarquivo values (1010592, 'ordemservicofiscal', 'Tabela que faz ligação entre uma ordem de serviço e um fiscal', 'q169', '2020-06-29', 'Ordem de Serviço Fiscal', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010592);
            insert into db_syscampo values(1011618,'q168_codigo','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011620,'q168_inscricao','int8','Inscrição da ordem de serviço','0', 'Inscrição',1,'f','f','f',1,'text','Inscrição');
            insert into db_syscampo values(1011621,'q168_descricao','varchar(255)','Nome do evento','', 'Nome do evento',255,'f','t','f',0,'text','Nome do evento');
            insert into db_syscampo values(1011622,'q168_localizacao','varchar(255)','Localização do evento','', 'Localização',255,'f','t','f',0,'text','Localização');
            insert into db_syscampo values(1011623,'q168_dataemissao','date','Data de emissão da ordem de serviço','null', 'Data de Emissão',10,'f','f','f',1,'text','Data de Emissão');
            insert into db_syscampo values(1011624,'q168_datainicio','date','Data de Inicio do evento','null', 'Data de Inicio',10,'f','f','f',1,'text','Data Inicial');
            insert into db_syscampo values(1011625,'q168_datafim','date','Data de fim do evento','null', 'Data de Fim ',10,'f','f','f',1,'text','Data Final');
            insert into db_syscampo values(1011626,'q168_horainicio','varchar(8)','Hora inicial do evento','', 'Hora inicial',8,'f','t','f',0,'text','Hora inicial');
            insert into db_syscampo values(1011627,'q168_horafim','varchar(8)','Hora final do evento','', 'Hora final',8,'f','t','f',0,'text','Hora final');
            insert into db_syscampo values(1011628,'q169_codigo','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011629,'q169_ordemservico','int8','Ordem de serviço ','0', 'Orden de serviço',1,'f','f','f',1,'text','Orden de serviço');
            insert into db_syscampo values(1011630,'q169_fiscal','int8','Fiscal','0', 'Fiscal',1,'f','f','f',1,'text','Fiscal');
            insert into db_syscampo values(1011632,'q168_cgm','int8','CGM da ordem de serviço','0', 'CGM',1,'f','f','f',1,'text','CGM');
            insert into db_syscampo values(1011633,'q168_processo','int8','Processo do protocolo vinculado a Ordem de serviço','0', 'Processo',1,'f','f','f',1,'text','Processo');
            insert into db_syscampo values(1011675,'q168_processoexterno','varchar(255)','Processo que não foi gerado pelo módulo Protocolo do e-Cidade.','', 'Processo Externo',255,'t','t','f',0,'text','Processo Externo');
            insert into db_syscampo values(1011676,'q168_titularprocessoexterno','varchar(255)','Titular do processe que não foi gerado pelo módulo Protocolo do e-Cidade.','', 'Titular Processo Externo',255,'t','t','f',0,'text','Titular Processo Externo');
            insert into db_syscampo values(1011677,'q168_dataprocessoexterno','date','Data do processo externo.','null', 'Data Processo Externo',10,'t','f','f',1,'text','Data Processo Externo');

            delete from db_sysarqcamp where codarq = 1010591;
            insert into db_sysarqcamp values(1010591,1011618,1,0);
            insert into db_sysarqcamp values(1010591,1011633,2,0);
            insert into db_sysarqcamp values(1010591,1011632,3,0);
            insert into db_sysarqcamp values(1010591,1011620,4,0);
            insert into db_sysarqcamp values(1010591,1011621,5,0);
            insert into db_sysarqcamp values(1010591,1011622,6,0);
            insert into db_sysarqcamp values(1010591,1011623,7,0);
            insert into db_sysarqcamp values(1010591,1011624,8,0);
            insert into db_sysarqcamp values(1010591,1011625,9,0);
            insert into db_sysarqcamp values(1010591,1011626,10,0);
            insert into db_sysarqcamp values(1010591,1011627,11,0);
            insert into db_sysarqcamp values(1010591,1011675,12,0);
            insert into db_sysarqcamp values(1010591,1011676,13,0);
            insert into db_sysarqcamp values(1010591,1011677,14,0);
            delete from db_sysprikey where codarq = 1010591;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010591,1011618,1,1011618);
            delete from db_sysarqcamp where codarq = 1010592;
            insert into db_sysarqcamp values(1010592,1011628,1,0);
            insert into db_sysarqcamp values(1010592,1011629,2,0);
            insert into db_sysarqcamp values(1010592,1011630,3,0);
            delete from db_sysprikey where codarq = 1010592;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010592,1011628,1,1011628);

            update db_syscampo set nomecam = 'q168_processo', conteudo = 'int8', descricao = 'Processo do protocolo vinculado a Ordem de serviço', valorinicial = '0', rotulo = 'Processo', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Processo' where codcam = 1011633;
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codarq = 1010592;
            delete from db_sysprikey where codarq = 1010592;
            delete from db_sysprikey where codarq = 1010591;
            delete from db_sysarqcamp where codarq = 1010591;

            delete from db_syscampo where codcam in (1011618, 1011620, 1011621, 1011622, 1011623, 1011624, 1011625, 1011626, 1011627, 1011628, 1011629, 1011630, 1011632, 1011633, 1011675, 1011676, 1011677);

            delete from db_sysarqmod where codarq in (1010591, 1010592);
            delete from db_sysarquivo where codarq in (1010591, 1010592);

SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL

            CREATE SEQUENCE issqn.ordemservico_q168_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE issqn.ordemservico(
                q168_codigo      integer NOT NULL default nextval('issqn.ordemservico_q168_codigo_seq'),
                q168_processo    integer,
                q168_cgm         integer,
                q168_inscricao   integer,
                q168_descricao   varchar(255) NOT NULL,
                q168_localizacao varchar(255) NOT NULL,
                q168_dataemissao date NOT NULL default CURRENT_TIMESTAMP,
                q168_datainicio  date NOT NULL,
                q168_datafim     date NOT NULL,
                q168_horainicio  varchar(10) NOT NULL,
                q168_horafim     varchar(10) NOT NULL,
                q168_processoexterno varchar(255),
                q168_titularprocessoexterno varchar(255),
                q168_dataprocessoexterno date,
                CONSTRAINT ordemservico_sequ_pk PRIMARY KEY (q168_codigo)
            );

            ALTER TABLE issqn.ordemservico
                ADD CONSTRAINT ordemservico_processo_fk FOREIGN KEY (q168_processo)
                REFERENCES protocolo.protprocesso(p58_codproc),
                ADD CONSTRAINT ordemservico_cgm_fk FOREIGN KEY (q168_cgm)
                REFERENCES protocolo.cgm(z01_numcgm),
                ADD CONSTRAINT ordemservico_inscricao_fk FOREIGN KEY (q168_inscricao)
                REFERENCES issqn.issbase(q02_inscr);

            select configuracoes.fc_auditoria_cria_funcao('issqn.ordemservico');

            CREATE SEQUENCE issqn.ordemservico_q169_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE issqn.ordemservicofiscal(
                q169_codigo       integer NOT NULL default nextval('issqn.ordemservico_q169_codigo_seq'),
                q169_ordemservico integer NOT NULL,
                q169_fiscal       integer NOT NULL,
                CONSTRAINT ordemservicofiscal_sequ_pk PRIMARY KEY (q169_codigo)
            );

            ALTER TABLE issqn.ordemservicofiscal
                ADD CONSTRAINT ordemservicofiscal_ordemservico_fk FOREIGN KEY (q169_ordemservico)
                REFERENCES issqn.ordemservico(q168_codigo);

            ALTER TABLE issqn.ordemservicofiscal
                ADD CONSTRAINT ordemservicofiscal_fiscal_fk FOREIGN KEY (q169_fiscal)
                REFERENCES fiscal.cadfiscais(id_usuario);

            select configuracoes.fc_auditoria_cria_funcao('issqn.ordemservicofiscal');

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            select configuracoes.fc_auditoria_remove_funcao('issqn.ordemservicofiscal');
            select configuracoes.fc_auditoria_remove_funcao('issqn.ordemservico');

            DROP TABLE issqn.ordemservicofiscal;
            DROP TABLE issqn.ordemservico;

            DROP SEQUENCE issqn.ordemservico_q169_codigo_seq;
            DROP SEQUENCE issqn.ordemservico_q168_codigo_seq;
SQL
        );
    }
}