<?php

use Classes\PostgresMigration;

class M10324SiconfiInformacoesComplementaresLancamentos extends PostgresMigration
{
    public function up()
    {
        $this->incluirMenu();
        $this->criaTabela();
        $this->upDicionario();
    }

    public function down()
    {
        $this->removerMenu();
        $this->downDicionario();
        $this->removeTabela();
    }

    private function upDicionario()
    {
        $this->execute(
<<<SQL
            insert into db_sysarquivo values (1010266, 'conlancaminfocomplementarvalor', 'Vínculo de informações complementares dos lançamentos', 'c126', '2018-03-07', 'Vínculo de informações complementares dos lançamen', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (32,1010266);
            insert into db_syscampo values(1009655,'c126_sequencial','int4','Sequencial da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009656,'c126_codlan','int4','Código do lançamento','0', 'Código do lançamento',10,'f','f','f',1,'text','Código do lançamento');
            insert into db_syscampo values(1009657,'c126_reduz','int4','Reduzido da conta','0', 'Reduzido da conta',10,'f','f','f',1,'text','Reduzido da conta');
            insert into db_syscampo values(1009658,'c126_infocomplementar','int4','Código da Informação complementar','0', 'Código da Informação complementar',10,'f','f','f',1,'text','Código da Informação complementar');
            insert into db_syscampo values(1009659,'c126_valor','varchar(20)','Valor da informação complementar','', 'Valor da informação complementar',20,'f','t','f',0,'text','Valor da informação complementar');
            insert into db_syscampo values(1009660,'c126_tiposistema','int4','Tipo do sistema do sistema que o registro faz referencia (SICONFI etc)','0', '',10,'f','f','f',1,'text','Tipo do sistema');
            insert into db_sysarqcamp values(1010266,1009655,1,0);
            insert into db_sysarqcamp values(1010266,1009656,2,0);
            insert into db_sysarqcamp values(1010266,1009657,3,0);
            insert into db_sysarqcamp values(1010266,1009658,4,0);
            insert into db_sysarqcamp values(1010266,1009659,5,0);
            insert into db_sysarqcamp values(1010266,1009660,6,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010266,1009655,1,1009655);
            insert into db_sysforkey values(1010266,1009656,1,760,0);
            insert into db_sysforkey values(1010266,1009658,1,1010256,0);
            insert into db_sysforkey values(1010266,1009660,1,1010257,0);
            insert into db_sysindices values(1008259,'conlancaminfocomplementarvalor_c126_codlan_in',1010266,'0');
            insert into db_syscadind values(1008259,1009656,1);
            insert into db_sysindices values(1008260,'conlancaminfocomplementarvalor_c126_infocomplementar_in',1010266,'0');
            insert into db_syscadind values(1008260,1009658,1);
            insert into db_sysindices values(1008261,'conlancaminfocomplementarvalor_c126_tiposistema_in',1010266,'0');
            insert into db_syscadind values(1008261,1009660,1);
            insert into db_syssequencia values(1000721, 'conlancaminfocomplementarvalor_c126_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000721 where codarq = 1010266 and codcam = 1009655;
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(
<<<SQL
            delete from db_syscadind where codcam in (1009656, 1009658, 1009660);
            delete from db_sysindices where codarq = 1010266;
            delete from db_sysforkey where codarq = 1010266;
            delete from db_sysprikey where codarq = 1010266;
            delete from db_sysarqcamp where codarq = 1010266;
            delete from db_syssequencia where codsequencia = 1000721;
            delete from db_syscampo where codcam in (1009655,1009656,1009657,1009658,1009659,1009660);
            delete from db_sysarqmod where codarq = 1010266;
            delete from db_sysarquivo where codarq = 1010266;
SQL
        );
    }

    private function incluirMenu()
    {
        $this->execute(
<<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10512 ,'Manutenção' ,'Manutenção' ,'' ,'1' ,'1' ,'Submenu para manutenção de dados relativos a Matriz de Saldo Contábil' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10496 ,10512 ,5 ,209 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10513 ,'Informação Complementar para Lançamentos' ,'Informação Complementar para Lançamentos' ,'con4_informacaocomplementarlancamento001.php' ,'1' ,'1' ,'Tela para criação de vínculo de valores de informações complementares a lançamentos' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10512 ,10513 ,1 ,209 );
SQL
        );
    }

    private function removerMenu()
    {
        $this->execute(
<<<SQL
            delete from db_menu where id_item_filho = 10512 AND modulo = 209;
            delete from db_itensmenu where id_item = 10512;
            delete from db_menu where id_item_filho = 10513 AND modulo = 209;
            delete from db_itensmenu where id_item = 10513;
SQL
        );
    }

    private function criaTabela()
    {
        $this->execute(
<<<SQL
            CREATE SEQUENCE contabilidade.conlancaminfocomplementarvalor_c126_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE contabilidade.conlancaminfocomplementarvalor (
                c126_sequencial       int4 NOT NULL default 0,
                c126_codlan           int4 NOT NULL,
                c126_reduz            int4 NOT NULL,
                c126_infocomplementar int4 NOT NULL,
                c126_tiposistema      int4 NOT NULL,
                c126_valor            varchar(20) NOT NULL,
                CONSTRAINT conlancaminfocomplementarvalor_sequ_pk PRIMARY KEY (c126_sequencial));

            ALTER TABLE contabilidade.conlancaminfocomplementarvalor
            ADD CONSTRAINT conlancaminfocomplementarvalor_codlan_fk FOREIGN KEY (c126_codlan)
            REFERENCES conlancam(c70_codlan);

            ALTER TABLE contabilidade.conlancaminfocomplementarvalor
            ADD CONSTRAINT conlancaminfocomplementarvalor_infocomplementar_fk FOREIGN KEY (c126_infocomplementar)
            REFERENCES conplanoinfocomplementar(c121_sequencial);

            ALTER TABLE contabilidade.conlancaminfocomplementarvalor
            ADD CONSTRAINT conlancaminfocomplementarvalor_tiposistema_fk FOREIGN KEY (c126_tiposistema)
            REFERENCES conplanosistema(c122_sequencial);

            CREATE INDEX conlancaminfocomplementarvalor_c126_codlan_in ON conlancaminfocomplementarvalor(c126_codlan);
            CREATE INDEX conlancaminfocomplementarvalor_c126_infocomplementar_in ON conlancaminfocomplementarvalor(c126_infocomplementar);
SQL
        );
    }

    private function removeTabela()
    {
        $this->execute(
<<<SQL
            DROP TABLE IF EXISTS conlancaminfocomplementarvalor CASCADE;
            DROP SEQUENCE IF EXISTS conlancaminfocomplementarvalor_c126_sequencial_seq;
SQL
        );
    }
}
