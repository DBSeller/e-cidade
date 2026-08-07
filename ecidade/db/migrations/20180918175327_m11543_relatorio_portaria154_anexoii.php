<?php

use Classes\PostgresMigration;

class M11543RelatorioPortaria154Anexoii extends PostgresMigration
{
 
    public function up()
    {
        $this->criaMenu();
        $this->criaEstrutura();
        $this->criaTabela();
    }

    public function down()
    {
        $this->deletaMenu();
        $this->deletaEstrutura();
        $this->deletaTabela();
    }

    public function criaMenu() {
        $sql = <<<SQL

        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10584 ,'Portaria 154' ,'Portaria 154' ,'' ,'1' ,'1' ,'Portaria 154' ,'true' );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10584 ,475 ,2323 );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10585 ,'Anexo II' ,'Anexo II' ,'portaria154anexo2_001.php' ,'1' ,'1' ,'Anexo II' ,'true' );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10584 ,10585 ,2 ,2323 );
SQL;
        $this->execute($sql);
    }

    public function deletaMenu() {
        $sql = <<<SQL
            delete from db_itensmenu where id_item in (10584, 10585);
            delete from db_menu where id_item_filho = 10584 AND modulo = 2323;
            delete from db_menu where id_item_filho = 10585 AND modulo = 2323;
SQL;
        $this->execute($sql);
    }

    public function criaEstrutura() {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010326, 'servidorrelatorioarquivogenerico', 'servidorrelatorioarquivogenerico', 'rh217', '2018-09-26', 'Servidor Relatório Arquivo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (29,1010326);
            insert into db_syscampo values(1009997,'rh217_regist','int4','Código da Matrícula do servidor','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
            insert into db_syscampo values(1009998,'rh217_anousu','int4','Exercicio','0', 'Exercicio',10,'t','f','f',1,'text','Exercicio');
            insert into db_syscampo values(1009999,'rh217_mesusu','int4','Mês do exercicio','0', 'Mês',10,'t','f','f',1,'text','Mês');
            update db_syscampo set nomecam = 'rh217_anousu', conteudo = 'int4', descricao = 'Exercicio', valorinicial = '0', rotulo = 'Exercicio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Exercicio' where codcam = 1009998;
            delete from db_syscampodep where codcam = 1009998;
            delete from db_syscampodef where codcam = 1009998;
            update db_syscampo set nomecam = 'rh217_anousu', conteudo = 'int4', descricao = 'Exercicio', valorinicial = '0', rotulo = 'Exercicio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Exercicio' where codcam = 1009998;
            delete from db_syscampodep where codcam = 1009998;
            delete from db_syscampodef where codcam = 1009998;
            insert into db_syscampo values(1010000,'rh217_arquivorelatorio','text','Arquivo ou Relatório das informações do servidor.','', 'ArquivoRelatório',1,'f','f','f',0,'text','ArquivoRelatório');
            insert into db_syscampo values(1010001,'rh217_informacao','text','Informação do servidor.','', 'Informação',1,'f','f','f',0,'text','Informação');
            delete from db_sysarqcamp where codarq = 1010326;
            insert into db_sysarqcamp values(1010326,1009997,1,0);
            insert into db_sysarqcamp values(1010326,1009998,2,0);
            insert into db_sysarqcamp values(1010326,1009999,3,0);
            insert into db_sysarqcamp values(1010326,1010000,4,0);
            insert into db_sysarqcamp values(1010326,1010001,5,0);
            delete from db_sysprikey where codarq = 1010326;
            insert into db_syscampo values(1010002,'rh217_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            delete from db_sysarqcamp where codarq = 1010326;
            insert into db_sysarqcamp values(1010326,1010002,1,0);
            insert into db_sysarqcamp values(1010326,1009997,2,0);
            insert into db_sysarqcamp values(1010326,1009998,3,0);
            insert into db_sysarqcamp values(1010326,1009999,4,0);
            insert into db_sysarqcamp values(1010326,1010000,5,0);
            insert into db_sysarqcamp values(1010326,1010001,6,0);
            delete from db_sysprikey where codarq = 1010326;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1010002,1,1010002);
            -- insert into db_syssequencia values(1000771, 'servidorrelatorioarquivogenerico_rh217_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            -- update db_sysarqcamp set codsequencia = 1000771 where codarq = 1010326 and codcam = 1010002;

            delete from db_sysarqcamp where codarq = 1010326;
            insert into db_sysarqcamp values(1010326,1009997,1,0);
            insert into db_sysarqcamp values(1010326,1009998,2,0);
            insert into db_sysarqcamp values(1010326,1009999,3,0);
            insert into db_sysarqcamp values(1010326,1010000,4,0);
            insert into db_sysarqcamp values(1010326,1010001,5,0);
            delete from db_sysprikey where codarq = 1010326;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1009997,1,1009997);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1009998,2,1009997);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1009999,3,1009997);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1010000,4,1009997);
            delete from db_syscampodef where codcam = 1010002;
            delete from db_syscampodep where codcam = 1010002;
            delete from db_syscampo where codcam = 1010002;
SQL;
        $this->execute($sql);
    }

    public function deletaEstrutura() {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 1010326;
            delete from db_syssequencia where  codsequencia = 1000771;
            delete from db_sysprikey where codarq = 1010326;
            delete from db_sysarqcamp where codarq = 1010326;
            delete from db_syscampo where codcam = 1010002;
            delete from db_syscampo where codcam = 1010001;
            delete from db_syscampo where codcam = 1010000;
            delete from db_syscampodef where codcam = 1009998;
            delete from db_syscampodep where codcam = 1009998;
            delete from db_syscampo where codcam = 1009999;
            delete from db_syscampo where codcam = 1009998;
            delete from db_syscampo where codcam = 1009997;            
            delete from db_sysarqmod where codarq = 1010326; 
            delete from db_sysarquivo where codarq = 1010326;

        -- insert into db_sysarqcamp values(1010326,1010001,6,0);
        -- insert into db_sysarqcamp values(1010326,1010000,5,0);
        -- insert into db_sysarqcamp values(1010326,1009999,4,0);
        -- insert into db_sysarqcamp values(1010326,1009998,3,0);
        -- insert into db_sysarqcamp values(1010326,1009997,2,0);
        -- insert into db_sysarqcamp values(1010326,1010002,1,1000771);
        -- delete from db_sysarqcamp where codarq = 1010326;
        -- update db_sysarqcamp set codsequencia = 1000771 where codarq = 1010326 and codcam = 1010002;
        -- insert into db_syssequencia values(1000771, 'servidorrelatorioarquivogenerico_rh217_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        -- insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010326,1010002,1,1010002);
        -- delete from db_sysprikey where codarq = 1010326;
        -- insert into db_sysarqcamp values(1010326,1010001,6,0);
        -- insert into db_sysarqcamp values(1010326,1010000,5,0);
        -- insert into db_sysarqcamp values(1010326,1009999,4,0);
        -- insert into db_sysarqcamp values(1010326,1009998,3,0);
        -- insert into db_sysarqcamp values(1010326,1009997,2,0);
        -- insert into db_sysarqcamp values(1010326,1010002,1,0);
        -- delete from db_sysarqcamp where codarq = 1010326;
        -- insert into db_syscampo values(1010002,'rh217_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
        -- delete from db_sysprikey where codarq = 1010326;
        -- insert into db_sysarqcamp values(1010326,1010001,5,0);
        -- insert into db_sysarqcamp values(1010326,1010000,4,0);
        -- insert into db_sysarqcamp values(1010326,1009999,3,0);
        -- insert into db_sysarqcamp values(1010326,1009998,2,0);
        -- insert into db_sysarqcamp values(1010326,1009997,1,0);
        -- delete from db_sysarqcamp where codarq = 1010326;
        -- insert into db_syscampo values(1010001,'rh217_informacao','text','Informação do servidor.','', 'Informação',1,'f','f','f',0,'text','Informação');
        -- insert into db_syscampo values(1010000,'rh217_arquivorelatorio','text','Arquivo ou Relatório das informações do servidor.','', 'ArquivoRelatório',1,'f','f','f',0,'text','ArquivoRelatório');
        -- delete from db_syscampodef where codcam = 1009998;
        -- delete from db_syscampodep where codcam = 1009998;
        -- update db_syscampo set nomecam = 'rh217_anousu', conteudo = 'int4', descricao = 'Exercicio', valorinicial = '0', rotulo = 'Exercicio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Exercicio' where codcam = 1009998;
        -- delete from db_syscampodef where codcam = 1009998;
        -- delete from db_syscampodep where codcam = 1009998;
        -- update db_syscampo set nomecam = 'rh217_anousu', conteudo = 'int4', descricao = 'Exercicio', valorinicial = '0', rotulo = 'Exercicio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Exercicio' where codcam = 1009998;
        -- insert into db_syscampo values(1009999,'rh217_mesusu','int4','Mês do exercicio','0', 'Mês',10,'t','f','f',1,'text','Mês');
        -- insert into db_syscampo values(1009998,'rh217_anousu','int4','Exercicio','0', 'Exercicio',10,'t','f','f',1,'text','Exercicio');
        -- insert into db_syscampo values(1009997,'rh217_regist','int4','Código da Matrícula do servidor','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
        -- insert into db_sysarqmod values (29,1010326);
        -- insert into db_sysarquivo values (1010326, 'servidorrelatorioarquivogenerico', 'servidorrelatorioarquivogenerico', 'rh217', '2018-09-26', 'Servidor Relatório Arquivo', 0, 'f', 'f', 'f', 'f' );
SQL;
        $this->execute($sql);
    }

    public function criaTabela() {
        $sql = <<<SQL

            -- TABELAS E ESTRUTURA

            -- Módulo: recursoshumanos
CREATE TABLE recursoshumanos.servidorrelatorioarquivogenerico(
rh217_regist        int4 NOT NULL default 0,
rh217_anousu        int4 NOT NULL default 0,
rh217_mesusu        int4  default 0,
rh217_arquivorelatorio      text NOT NULL ,
rh217_informacao        text ,
CONSTRAINT servidorrelatorioarquivogenerico_regi_ae_me_arqu_pk PRIMARY KEY (rh217_regist,rh217_anousu,rh217_mesusu,rh217_arquivorelatorio));


SQL;
        $this->execute($sql);
    }

    public function deletaTabela() {
        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.servidorrelatorioarquivogenerico CASCADE;
SQL;
        $this->execute($sql);
    }

}
