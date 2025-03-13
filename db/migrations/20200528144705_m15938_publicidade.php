<?php

use Classes\PostgresMigration;

class M15938Publicidade extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->menu();
        $this->estrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downMenu();
        $this->downEstrutura();
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo values (1010572, 'publicidadesigapfiscal', 'Armazena as informações de publicidade do sigap fiscal', 'c136', '2020-05-28', 'Publicidade Sigap Fiscal', 0, 'f', 'f', 'f', 'f' );

            insert into db_sysarqmod values (32,1010572);

            insert into db_syscampo
            values (1011320,'c136_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1011321,'c136_ano','int4','Ano','0', 'Ano',10,'f','f','f',1,'text','Ano'),
                   (1011322,'c136_tipo_relatorio','int4','Tipo do Relatório 01 - Relatório Resumido de Execução Orçamentária; 02 - Relatório de Gestão Fiscal','0', 'Tipo do Relatório',10,'f','f','f',1,'text','Tipo do Relatório'),
                   (1011323,'c136_data_publicacao','date','Data da Publicação','null', 'Data da Publicação',10,'f','f','f',1,'text','Data da Publicação'),
                   (1011324,'c136_meio_comunicacao','int4','Código do meio de comunicação','0', 'Meio de Comunicação',10,'f','f','f',1,'text','Meio de Comunicação'),
                   (1011325,'c136_periodo','int4','Período de referência','0', 'Período',10,'f','f','f',1,'text','Período'),
                   (1011326,'c136_link','text','Link da Transparência','', 'Link da Transparência',1,'f','t','f',0,'text','Link da Transparência'),
                   (1011327,'c136_local_publicacao','text','Local de Publicação','', 'Local de Publicação',1,'f','t','f',0,'text','Local de Publicação'),
                   (1011328,'c136_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','f','f',0,'text','Descrição'),
                   (1011334,'c136_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');

            insert into db_sysarqcamp
            values (1010572,1011320,1,0),
                   (1010572,1011321,2,0),
                   (1010572,1011328,3,0),
                   (1010572,1011323,4,0),
                   (1010572,1011322,5,0),
                   (1010572,1011324,6,0),
                   (1010572,1011325,7,0),
                   (1010572,1011326,8,0),
                   (1010572,1011327,9,0),
                   (1010572,1011334,10,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010572,1011320,1,1011328);

            insert into db_sysforkey
            values (1010572,1011324,1,3151,0),
                   (1010572,1011325,1,2480,0),
                   (1010572,1011334,1,83,0);

            insert into db_sysindices
            values (1008577,'publicidadesigapfiscal_meio_comunicacao_in',1010572,'0'),
                   (1008578,'publicidadesigapfiscal_periodo_in',1010572,'0'),
                   (1008579,'publicidadesigapfiscal_instituicao_in',1010572,'0');

            insert into db_syscadind
            values (1008577,1011324,1),
                   (1008578,1011325,1),
                   (1008579,1011334,1);

            insert into db_syssequencia values(1000918, 'publicidadesigapfiscal_c136_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000918 where codarq = 1010572 and codcam = 1011320;
        ");
    }

    private function menu()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228260 ,'Publicidade SIGAP Fiscal' ,'Publicidade SIGAP Fiscal' ,'con4_publicidade001.php' ,'1' ,'1' ,'Cadastra a forma de comunicação dos arquivos do SIGAP' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8467 ,228260 ,8 ,209 );
        ");
    }

    private function downDicionario()
    {
        $this->execute("
            delete from db_sysprikey where codarq = 1010572;
            delete from db_sysforkey where codarq = 1010572;
            delete from db_syssequencia where codsequencia = 1000918;
            delete from db_syscadind where codind in (1008577, 1008578, 1008579);
            delete from db_sysindices where codind in (1008577, 1008578, 1008579);
            delete from db_sysarqcamp where codarq = 1010572;
            delete from db_syscampo where codcam in (1011320, 1011321, 1011322, 1011323, 1011324, 1011325, 1011326, 1011327, 1011328, 1011334);
            delete from db_sysarqmod where codarq = 1010572;
            delete from db_sysarquivo where codarq = 1010572;
        ");
    }

    private function downMenu()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228260 AND modulo = 209;
            delete from db_itensmenu where id_item = 228260;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            CREATE SEQUENCE publicidadesigapfiscal_c136_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

            CREATE TABLE contabilidade.publicidadesigapfiscal(
                c136_codigo int4 not null,
                c136_ano int4 not null,
                c136_descricao varchar(255) not null,
                c136_tipo_relatorio int4 not null,
                c136_data_publicacao date not null,
                c136_meio_comunicacao int4 not null,
                c136_periodo int4 not null,
                c136_instituicao int4 not null,
                c136_link text,
                c136_local_publicacao text,
                CONSTRAINT publicidadesigapfiscal_codi_pk PRIMARY KEY (c136_codigo)
            );

            ALTER TABLE contabilidade.publicidadesigapfiscal ADD CONSTRAINT publicidadesigapfiscal_comunicacao_fk FOREIGN KEY (c136_meio_comunicacao) REFERENCES contabilidade.meiocomunicacaosigap;
            ALTER TABLE contabilidade.publicidadesigapfiscal ADD CONSTRAINT publicidadesigapfiscal_periodo_fk FOREIGN KEY (c136_periodo) REFERENCES periodo;
            ALTER TABLE publicidadesigapfiscal ADD CONSTRAINT publicidadesigapfiscal_instituicao_fk FOREIGN KEY (c136_instituicao) REFERENCES db_config;

            CREATE INDEX publicidadesigapfiscal_meio_comunicacao_in ON contabilidade.publicidadesigapfiscal(c136_meio_comunicacao);
            CREATE INDEX publicidadesigapfiscal_periodo_in ON contabilidade.publicidadesigapfiscal(c136_periodo);
            CREATE INDEX publicidadesigapfiscal_instituicao_in ON publicidadesigapfiscal(c136_instituicao);
        ");

        $this->execute("
        insert into contabilidade.meiocomunicacaosigap
        values (10, '10', 'Diário da AROM', 'RO'),
               (11, '11', 'DOe - ALE/RO', 'RO'),
               (12, '12', 'DOe - TCE/RO', 'RO'),
               (13, '13', 'Diário Oficial do Município', 'RO');
        ");
    }

    private function downEstrutura()
    {
        $this->execute("
            DROP TABLE IF EXISTS contabilidade.publicidadesigapfiscal CASCADE;
            DROP SEQUENCE IF EXISTS publicidadesigapfiscal_c136_codigo_seq;
        ");

        $this->execute("delete from contabilidade.meiocomunicacaosigap where c49_sequencial >= 10;");
    }
}
