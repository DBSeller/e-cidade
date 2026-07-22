<?php

use Classes\PostgresMigration;

class M6826QualificacaoCadastral extends PostgresMigration
{
    public function up() 
    {
        $this->upDicionario();
        $this->upTabelas();
        $this->upMenu();
    }

    public function down() 
    {
        $this->downDicionario();
        $this->downTabelas();
        $this->downMenu();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL_UP_DICIONARIO
            INSERT INTO db_sysarquivo VALUES (1010282, 'importacaoqualificacaocadastral', 'Qualificação cadastral dos servidores.', 'eso11', '2018-05-28', 'Qualificação Cadastral', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (81,1010282);
            INSERT INTO db_syscampo VALUES(1009743,'eso11_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES(1009744,'eso11_data','date','Data de importação do arquivo','null', 'Data',10,'f','f','f',1,'text','Data');
            INSERT INTO db_syscampo VALUES(1009745,'eso11_instituicao','int4','Vínculo da importação com a instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            INSERT INTO db_syscampo VALUES(1009746,'eso11_nomearquivo','varchar(255)','Nome do arquivo importado.','', 'Nome do arquivo',255,'f','t','f',0,'text','Nome do arquivo');
            INSERT INTO db_syscampo VALUES(1009747,'eso11_processado','bool','Se o arquivo importado foi processado ou rejeitado.','true', 'Processado',1,'f','f','f',5,'text','Processado');
            INSERT INTO db_syscampo VALUES(1009748,'eso11_arquivo','oid','Arquivo de retorno no formato TXT importado, com os dados da validação.','', 'Arquivo importado',1,'f','f','f',1,'text','Arquivo importado');
            INSERT INTO db_sysarqcamp VALUES(1010282,1009743,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010282,1009744,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010282,1009745,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010282,1009746,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010282,1009747,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010282,1009748,6,0);            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010282,1009743,1,1009746);
            INSERT INTO db_sysforkey VALUES(1010282,1009745,1,83,0);
            INSERT INTO db_sysindices VALUES(1008280,'importacaoqualificacaocadastral_eso11_instituicao_in',1010282,'0');
            INSERT INTO db_syscadind VALUES(1008280,1009745,1);
            INSERT INTO db_syssequencia VALUES(1000733, 'importacaoqualificacaocadastral_eso11_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000733 WHERE codarq = 1010282 AND codcam = 1009743;
SQL_UP_DICIONARIO
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL_DOWN_DICIONARIO
            DELETE FROM db_syscadind WHERE codcam IN (1009745);
            DELETE FROM db_sysindices WHERE codarq = 1010282;
            DELETE FROM db_sysforkey WHERE codarq = 1010282;
            DELETE FROM db_sysprikey WHERE codarq = 1010282;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010282;
            DELETE FROM db_syssequencia WHERE codsequencia = 1000733;
            DELETE FROM db_syscampo WHERE codcam IN (1009743,1009744,1009745,1009746,1009747,1009748);
            DELETE FROM db_sysarqmod WHERE codarq = 1010282;
            DELETE FROM db_sysarquivo WHERE codarq = 1010282;
SQL_DOWN_DICIONARIO
        );
    }

    private function upTabelas()
    {
        $this->execute(<<<SQL_UP_TABELAS
            CREATE SEQUENCE esocial.importacaoqualificacaocadastral_eso11_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE esocial.importacaoqualificacaocadastral(
                eso11_sequencial  int4 NOT NULL,
                eso11_data        timestamp,
                eso11_instituicao int4 NOT NULL,
                eso11_nomearquivo varchar(255) NOT NULL,
                eso11_processado  bool NOT NULL default 'true',
                eso11_arquivo     oid NOT NULL,
                CONSTRAINT importacaoqualificacaocadastral_sequ_pk PRIMARY KEY (eso11_sequencial));

            ALTER TABLE esocial.importacaoqualificacaocadastral
                ADD CONSTRAINT importacaoqualificacaocadastral_instituicao_fk FOREIGN KEY (eso11_instituicao)
                REFERENCES db_config;

            CREATE INDEX importacaoqualificacaocadastral_eso11_instituicao_in ON esocial.importacaoqualificacaocadastral(eso11_instituicao);
SQL_UP_TABELAS
            );
    }

    private function downTabelas()
    {
        $this->execute(<<<SQL_DOWN_TABELAS
            DROP TABLE IF EXISTS esocial.importacaoqualificacaocadastral CASCADE;
            DROP SEQUENCE IF EXISTS esocial.importacaoqualificacaocadastral_eso11_sequencial_seq;
SQL_DOWN_TABELAS
            );
    }

    private function upMenu()
    {
        $this->execute(<<<SQL_UP_MENU
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10520 ,'Qualificação Cadastral' ,'Qualificação cadastral dos servidores' ,'' ,'1' ,'1' ,'Qualificação cadastral dos servidores.' ,'true' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 32 ,10520 ,497 ,10216 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10521 ,'Geração' ,'Gera o arquivo com os dados servidores' ,'eso4_geracaoqualificacaocadastral001.php' ,'1' ,'1' ,'Gera o arquivo com os dados servidores para ser importado no eSocial.' ,'true' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10520 ,10521 ,1 ,10216 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10522 ,'Importação' ,'Importa o arquivo de retorno do eSocial.' ,'eso4_importacaoqualificacaocadastral001.php' ,'1' ,'1' ,'Importa o arquivo de retorno do eSocial.' ,'true' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10520 ,10522 ,2 ,10216 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10523 ,'Qualificação Cadastral' ,'Relatório com o retorno da qualificação cadastral do eSocial.' ,'eso3_qualificacaocadastral001.php' ,'1' ,'1' ,'Relatório com o retorno da qualificação cadastral do eSocial.' ,'true' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,10523 ,472 ,10216 );
SQL_UP_MENU
        );
    }

    private function downMenu()
    {
        $this->execute(<<<SQL_DOWN_MENU
            DELETE FROM db_menu where id_item_filho IN (10520, 10521, 10522, 10523) AND modulo = 10216;
            DELETE FROM db_itensmenu where id_item IN (10520, 10521, 10522, 10523);
SQL_DOWN_MENU
        );
    }


}
