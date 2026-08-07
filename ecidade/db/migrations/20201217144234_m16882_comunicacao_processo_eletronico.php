<?php

use Classes\PostgresMigration;

class M16882ComunicacaoProcessoEletronico extends PostgresMigration
{
    // Processos Vinculados
    public function up()
    {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010638, 'processosvinculados', 'Armazena vínculo entre processo do e-cidade e processo da Ouvidoria.', 'p92', '2020-12-17', 'Processos Vinculados', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010638);

            insert into db_syscampo values(1011924,'p92_sequencial','int4','PK da tabela processosvinculados.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011925,'p92_processopai','int4','Processo do e-cidade que é pai de um processo filho. Esse processo filho pode ser processo aberto para enviar uma mensagem para a Ouvidoria ou uma mensagem enviada da Ouvidoria para o e-cidade.','0', 'Processo Pai',10,'f','f','f',1,'text','Processo Pai');
            insert into db_syscampo values(1011926,'p92_processofilho','int4','Processo referente a uma mensagem enviada do e-cidade para a ouvidoria ou da ouvidoria para o e-cidade.','0', 'Processo Filho',10,'f','f','f',1,'text','Processo Filho');
            insert into db_syscampo values(1011927,'p92_tipo','int4','Tipo de Documento do Processo.','0', 'Tipo de Documento do Processo',10,'f','f','f',1,'text','Tipo de Documento do Processo');
            
            insert into db_sysarqcamp values(1010638,1011924,1,0);
            insert into db_sysarqcamp values(1010638,1011925,2,0);
            insert into db_sysarqcamp values(1010638,1011926,3,0);
            insert into db_sysarqcamp values(1010638,1011927,4,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010638,1011924,1,1011924);
            insert into db_sysforkey values(1010638,1011925,1,403,0);
            insert into db_sysforkey values(1010638,1011926,1,403,0);
            insert into db_sysforkey values(1010638,1011927,1,1010611,0);
            insert into db_syssequencia values(1000982, 'processosvinculados_p92_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000982 where codarq = 1010638 and codcam = 1011924;

            create table protocolo.processosvinculados (
                p92_sequencial int not null,
                p92_processopai int not null,
                p92_processofilho int not null,
                p92_tipo int not null,
                CONSTRAINT processosvinculados_p92_sequencial_seq_pk PRIMARY KEY (p92_sequencial)
            );

            CREATE SEQUENCE protocolo.processosvinculados_p92_sequencial_seq
                            INCREMENT 1
                            MINVALUE 1 
                            MAXVALUE 9223372036854775807 
                            START 1
                            CACHE 1;

            ALTER TABLE protocolo.processosvinculados
                            ADD CONSTRAINT processosvinculados_p92_sequencial_seq_processofilho_fk 
                            FOREIGN KEY (p92_processofilho)
                            REFERENCES protocolo.protprocesso(p58_codproc);

            ALTER TABLE protocolo.processosvinculados
                            ADD CONSTRAINT processosvinculados_p92_sequencial_seq_processopai_fk 
                            FOREIGN KEY (p92_processopai)
                            REFERENCES protocolo.protprocesso(p58_codproc);
            
            ALTER TABLE protocolo.processosvinculados
                            ADD CONSTRAINT processosvinculados_p92_sequencial_seq_tipo_fk 
                            FOREIGN KEY (p92_tipo)
                            REFERENCES protocolo.prottipodocumentoprocesso(p91_sequencial);

            INSERT INTO protocolo.prottipodocumentoprocesso(p91_sequencial,p91_descricao,p91_sigla) VALUES (5,'Ouvidoria','OUV');

            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao) VALUES (2,'Resposta Cidadão');
            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao) VALUES (3,'Mensagem Cidadão');
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_syssequencia WHERE codsequencia = 1000982;
            DELETE FROM db_sysprikey WHERE codarq = 1010638;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010638;
            DELETE FROM db_sysforkey WHERE codarq = 1010638;
            DELETE FROM db_syscampo  WHERE codcam IN (1011924, 1011925, 1011926, 1011927);
            DELETE FROM db_sysarqmod WHERE codarq = 1010638;
            DELETE FROM db_sysarquivo WHERE codarq = 1010638;

            DELETE  FROM  protocolo.tipodespacho WHERE p100_sequencial = 2;
            DELETE  FROM  protocolo.tipodespacho WHERE p100_sequencial = 3;

            DROP SEQUENCE processosvinculados_p92_sequencial_seq;
            DROP TABLE processosvinculados;
            DELETE FROM protocolo.prottipodocumentoprocesso WHERE p91_sigla = 'OUV';
SQL;

        $this->execute($sql);
    }
}
