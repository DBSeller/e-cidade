<?php

use Classes\PostgresMigration;

class M9919InformacoesComplementaresNovo extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDicionarioDados();
        $this->upDDL();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDicionarioDados();
        $this->downDDL();
    }

    private function upMenu()
    {
        $this->execute(<<<MENU
          update db_itensmenu set libcliente = 't' where id_item = 10497;
MENU
);
    }

    private function downMenu()
    {
        $this->execute(<<<MENU
          update db_itensmenu set libcliente = 'f' where id_item = 10497;
MENU
);
    }

    private function upDicionarioDados()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010255, 'conplanoatributos', 'Cadastro das informações complementares das contas do plano orçamentário', 'c120', '2018-01-24', 'conplanoatributos', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010256, 'conplanoinfocomplementar', 'Informação complementar das contas do plano de contas contábil', 'c121', '2018-01-24', 'Informação comeplementar', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010257, 'conplanosistema', 'Sistema que utiliza as informações complementares no e-cidade', 'c122', '2018-01-24', 'Sistema', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010258, 'infocomplementarvalor', 'Cadastros dos valores das informações complementares da tabela conplanoatributos.', 'c123', '2018-01-25', 'Info complementar valor', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010259, 'conplanoatributolancamentos', 'Lançamentos realizados para as contas e suas respectivas informações complementares', 'c124', '2018-01-25', 'Lançamentos', 0, 'f', 'f', 'f', 'f' );


            insert into db_sysarqmod values (32,1010255);
            insert into db_sysarqmod values (32,1010256);
            insert into db_sysarqmod values (32,1010257);
            insert into db_sysarqmod values (32,1010258);
            insert into db_sysarqmod values (32,1010259);


            insert into db_syscampo values(1009610,'c120_sequencial','int4','Sequencial do campo','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009611,'c120_conplano','int4','Código da Conplano','0', 'Código Conplano',10,'f','f','f',1,'text','Código Conplano');
            insert into db_syscampo values(1009612,'c120_anousu','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1009613,'c120_infocomplementar','int4','Código da informação complementar','0', 'Código informação complementar',10,'f','f','f',1,'text','Código informação complementar');
            insert into db_syscampo values(1009614,'c120_conplanosistema','int4','conplanosistema','0', 'conplanosistema',10,'f','f','f',1,'text','conplanosistema');

            insert into db_syscampo values(1009615,'c121_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009616,'c121_sigla','varchar(5)','Sigla','', 'Sigla',5,'f','f','f',0,'text','Sigla');
            insert into db_syscampo values(1009617,'c121_descricao','varchar(45)','Descrição','', 'Descrição',45,'f','t','f',0,'text','Descrição');

            insert into db_syscampo values(1009608,'c122_sequencial','int4','Sequencial do campo','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009609,'c122_descricao','varchar(20)','Descrição do campo','', 'Descrição',20,'f','t','f',0,'text','Descrição');

            insert into db_syscampo values(1009618,'c124_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009626,'c124_lancamento','int4','Código do lançamento (conlancam).','0', 'Código lançamento',10,'f','f','f',1,'text','Código lançamento');
            insert into db_syscampo values(1009619,'c124_natureza','char(1)','Natureza do valor D ou C (Débito ou Crédito).','', 'Natureza',1,'f','t','f',0,'text','Natureza');
            insert into db_syscampo values(1009620,'c124_tipo','varchar(40)','Tipo do lançamento realizado','', 'Tipo',40,'f','t','f',0,'text','Tipo');
            insert into db_syscampo values(1009621,'c124_valor','float8','Valor do lançamento','0', 'Valor',15,'f','f','f',4,'text','Valor');
            insert into db_syscampo values(1009633,'c124_data','date','Data do lançamento','null','Data',10,'f','f','f',1,'text','Data');

            insert into db_syscampo values(1009622,'c123_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009623,'c123_conplanoatributos','int4','Chave estrangeira da tabela conplanoatributos','0', 'conplanoatributos',10,'f','f','f',1,'text','conplanoatributos');
            insert into db_syscampo values(1009624,'c123_conplanoatributolancamentos','int4','Chave estrangeira da tabela conplanoatributolancamentos','0', 'conplanoatributolancamentos',10,'f','f','f',1,'text','conplanoatributolancamentos');
            insert into db_syscampo values(1009625,'c123_valor','varchar(20)','Numero ou valor da informação complementar no e-cidade.','', 'Valor da informação complementar',20,'f','t','f',0,'text','Valor da informação complementar');
            insert into db_syscampo values(1009631,'c123_reduzido','int4','Conta Reduzida','0', 'Reduzido',10,'f','f','f',1,'text','Reduzido');


            insert into db_sysarqcamp values(1010257,1009608,1,0);
            insert into db_sysarqcamp values(1010257,1009609,2,0);

            insert into db_sysarqcamp values(1010255,1009610,1,0);
            insert into db_sysarqcamp values(1010255,1009612,2,0);
            insert into db_sysarqcamp values(1010255,1009611,3,0);
            insert into db_sysarqcamp values(1010255,1009613,4,0);
            insert into db_sysarqcamp values(1010255,1009614,5,0);

            insert into db_sysarqcamp values(1010256,1009615,1,0);
            insert into db_sysarqcamp values(1010256,1009616,2,0);
            insert into db_sysarqcamp values(1010256,1009617,3,0);

            insert into db_sysarqcamp values(1010259,1009618,1,0);
            insert into db_sysarqcamp values(1010259,1009626,2,0);
            insert into db_sysarqcamp values(1010259,1009619,3,0);
            insert into db_sysarqcamp values(1010259,1009620,4,0);
            insert into db_sysarqcamp values(1010259,1009621,5,0);
            insert into db_sysarqcamp values(1010259,1009633,6,0);

            insert into db_sysarqcamp values(1010258,1009622,1,0);
            insert into db_sysarqcamp values(1010258,1009623,2,0);
            insert into db_sysarqcamp values(1010258,1009624,3,0);
            insert into db_sysarqcamp values(1010258,1009625,4,0);
            insert into db_sysarqcamp values(1010258,1009631,5,0);


            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010257,1009608,1,1009608);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010255,1009610,1,1009610);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010256,1009615,1,1009615);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010259,1009618,1,1009618);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010258,1009622,1,1009622);


            insert into db_syssequencia values(1000713, 'conplanosistema_c122_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_syssequencia values(1000714, 'conplanoatributos_c120_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_syssequencia values(1000715, 'conplanoinfocomplementar_c121_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_syssequencia values(1000716, 'conplanoatributolancamentos_c124_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_syssequencia values(1000717, 'infocomplementarvalor_c123_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysindices values(1008249,'conplanosistema_c122_sequencial_in',1010257,'1');
            insert into db_sysindices values(1008250,'conplanoatributos_c120_sequencial_in',1010255,'1');
            insert into db_sysindices values(1008251,'conplanoinfocomplementar_c121_sequencial_in',1010256,'0');
            insert into db_sysindices values(1008252,'conplanoatributolancamentos_c124_sequencial_in',1010259,'1');
            insert into db_sysindices values(1008253,'infocomplementarvalor_c123_sequencia_in',1010258,'1');


            insert into db_syscadind values(1008249,1009608,1);
            insert into db_syscadind values(1008250,1009610,1);
            insert into db_syscadind values(1008251,1009615,1);
            insert into db_syscadind values(1008252,1009618,1);
            insert into db_syscadind values(1008253,1009622,1);

            update db_sysarqcamp set codsequencia = 1000713 where codarq = 1010257 and codcam = 1009608;
            update db_sysarqcamp set codsequencia = 1000714 where codarq = 1010255 and codcam = 1009610;
            update db_sysarqcamp set codsequencia = 1000715 where codarq = 1010256 and codcam = 1009615;
            update db_sysarqcamp set codsequencia = 1000716 where codarq = 1010259 and codcam = 1009618;
            update db_sysarqcamp set codsequencia = 1000717 where codarq = 1010258 and codcam = 1009622;


            insert into db_sysforkey values(1010255, 1009614, 1, 1010257);
            insert into db_sysforkey values(1010255, 1009613, 1, 1010256);
            insert into db_sysforkey values(1010255, 1009611, 1,     774);
            insert into db_sysforkey values(1010255, 1009612, 2,     774);
            insert into db_sysforkey values(1010258,1009623,1,1010255,0);
            insert into db_sysforkey values(1010258,1009624,2,1010259,0);
            insert into db_sysforkey values(1010259,1009626,1,760,0);
SQL
        );
    }

    private function downDicionarioDados()
    {
        $this->execute(<<<SQL
            delete from db_sysforkey where codarq in (1010255, 1010258, 1010259);
            delete from db_syscadind where codind in (1008249, 1008250, 1008251, 1008252, 1008253);
            delete from db_sysindices where codind in (1008249, 1008250, 1008251, 1008252, 1008253);
            delete from db_syssequencia where codsequencia in (1000713, 1000714, 1000715, 1000716, 1000717);
            delete from db_sysprikey where codarq in (1010257, 1010255, 1010256, 1010259, 1010258);
            delete from db_sysarqcamp where codarq in (1010255, 1010256, 1010257, 1010258, 1010259);      
            delete from db_sysarqcamp where codarq in (1010255, 1010256, 1010257, 1010258, 1010259);
            delete from db_syscampo where codcam in (1009608, 1009609, 1009610, 1009611, 1009612, 1009613, 1009614, 1009615, 1009616, 1009617, 1009618, 1009619, 1009620, 1009621, 1009622, 1009623, 1009624, 1009625, 1009626, 1009631, 1009633);
            delete from db_sysarqmod where codarq in (1010255, 1010256, 1010257, 1010258, 1010259);
            delete from db_sysarquivo where codarq in (1010255, 1010256, 1010257, 1010258, 1010259);
SQL
        );
    }

    private function upDDL()
    {
        $this->execute(<<<SQL
            -- Criando  sequences
            CREATE SEQUENCE contabilidade.conplanoatributos_c120_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE contabilidade.conplanoinfocomplementar_c121_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE contabilidade.conplanosistema_c122_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE contabilidade.conplanoatributolancamentos_c124_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE contabilidade.infocomplementarvalor_c123_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE contabilidade.conplanoatributosaldo_c125_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            CREATE TABLE contabilidade.conplanoatributosaldo(
            c125_sequencial            int4 NOT NULL default 0,
            c125_anousu                int4 NOT NULL default 0,
            c125_mesusu                int4 NOT NULL default 0,
            c125_hashcontaatributos    varchar NOT NULL,
            c125_valor                 numeric NOT NULL default 0,
            c125_natureza              char(1) NOT NULL,
            c125_tipo                  int4 NOT NULL default 0,
            CONSTRAINT conplanoatributosaldo_sequ_pk PRIMARY KEY (c125_sequencial));

            CREATE TABLE contabilidade.conplanoatributos(
            c120_sequencial     int4 NOT NULL default 0,
            c120_anousu     int4 NOT NULL default 0,
            c120_conplano       int4 NOT NULL default 0,
            c120_infocomplementar       int4 NOT NULL default 0,
            c120_conplanosistema        int4 default 0,
            CONSTRAINT conplanoatributos_sequ_pk PRIMARY KEY (c120_sequencial));

            CREATE TABLE contabilidade.conplanoinfocomplementar(
            c121_sequencial     int4 NOT NULL default 0,
            c121_sigla      varchar(5) NOT NULL ,
            c121_descricao      varchar(45) ,
            CONSTRAINT conplanoinfocomplementar_sequ_pk PRIMARY KEY (c121_sequencial));

            CREATE TABLE contabilidade.conplanosistema(
            c122_sequencial     int4 NOT NULL default 0,
            c122_descricao      varchar(20) ,
            CONSTRAINT conplanosistema_sequ_pk PRIMARY KEY (c122_sequencial));

            CREATE TABLE contabilidade.conplanoatributolancamentos(
            c124_sequencial     int4 NOT NULL default 0,
            c124_lancamento     int4 default 0,
            c124_natureza       char(1) NOT NULL ,
            c124_tipo       varchar(40) NOT NULL ,
            c124_valor      float8 default 0,
            c124_data       date default NULL,
            CONSTRAINT conplanoatributolancamentos_sequ_pk PRIMARY KEY (c124_sequencial));

            CREATE TABLE contabilidade.infocomplementarvalor(
            c123_sequencial     int4 NOT NULL default 0,
            c123_conplanoatributos      int4 NOT NULL default 0,
            c123_conplanoatributolancamentos        int4 NOT NULL default 0,
            c123_valor      varchar(20) NOT NULL ,
            c123_reduzido       int4 default 0,
            CONSTRAINT infocomplementarvalor_sequ_pk PRIMARY KEY (c123_sequencial));


            -- CHAVE ESTRANGEIRA
            ALTER TABLE contabilidade.conplanoatributos
            ADD CONSTRAINT conplanoatributos_conplanosistema_fk FOREIGN KEY (c120_conplanosistema)
            REFERENCES conplanosistema;

            ALTER TABLE contabilidade.conplanoatributos
            ADD CONSTRAINT conplanoatributos_infocomplementar_fk FOREIGN KEY (c120_infocomplementar)
            REFERENCES conplanoinfocomplementar;

            ALTER TABLE contabilidade.conplanoatributos
            ADD CONSTRAINT conplanoatributos_conplano_ae_fk FOREIGN KEY (c120_conplano,c120_anousu)
            REFERENCES conplano;

            ALTER TABLE contabilidade.infocomplementarvalor
            ADD CONSTRAINT infocomplementarvalor_conplanoatributolancamentos_fk FOREIGN KEY (c123_conplanoatributolancamentos)
            REFERENCES contabilidade.conplanoatributolancamentos;

            ALTER TABLE contabilidade.infocomplementarvalor
            ADD CONSTRAINT infocomplementarvalor_conplanoatributos_fk FOREIGN KEY (c123_conplanoatributos)
            REFERENCES contabilidade.conplanoatributos;

            ALTER TABLE contabilidade.conplanoatributolancamentos
            ADD CONSTRAINT conplanoatributolancamentos_lancamento_fk FOREIGN KEY (c124_lancamento)
            REFERENCES contabilidade.conlancam;

            -- INDICES
            CREATE UNIQUE INDEX conplanoatributos_c120_sequencial_in ON contabilidade.conplanoatributos(c120_sequencial);
            CREATE UNIQUE INDEX conpanoinfocomplementar_c121_sequencial_in ON contabilidade.conplanoinfocomplementar(c121_sequencial);
            CREATE UNIQUE INDEX conplanosistema_c122_sequencial_in ON contabilidade.conplanosistema(c122_sequencial);
            CREATE UNIQUE INDEX conplanoatributolancamentos_c124_sequencial_in ON contabilidade.conplanoatributolancamentos(c124_sequencial);
            CREATE UNIQUE INDEX infocomplementarvalor_c123_sequencia_in ON contabilidade.infocomplementarvalor(c123_sequencial);
SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS contabilidade.infocomplementarvalor;
            DROP TABLE IF EXISTS contabilidade.conplanoatributolancamentos;
            DROP TABLE IF EXISTS contabilidade.conplanoatributos;
            DROP TABLE IF EXISTS contabilidade.conplanoinfocomplementar;
            DROP TABLE IF EXISTS contabilidade.conplanosistema;
            DROP TABLE IF EXISTS contabilidade.conplanoatributosaldo;

            -- DROP SEQUENCES
            DROP SEQUENCE IF EXISTS conplanoatributos_c120_sequencial_seq;
            DROP SEQUENCE IF EXISTS conplanoinfocomplementar_c121_sequencial_seq;
            DROP SEQUENCE IF EXISTS conplanosistema_c122_sequencial_seq;
            DROP SEQUENCE IF EXISTS conplanoatributolancamentos_c124_sequencial_seq;
            DROP SEQUENCE IF EXISTS infocomplementarvalor_c123_sequencial_seq;
            DROP SEQUENCE IF EXISTS conplanoatributosaldo_c125_sequencial_seq;
SQL
        );
    }


}

