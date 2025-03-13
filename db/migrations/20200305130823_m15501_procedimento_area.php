<?php

use Classes\PostgresMigration;

class M15501ProcedimentoArea extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    public function dicionario()
    {
        $this->execute("
        insert into db_sysarquivo
        values (1010533, 'areaprocedimento', 'Procedimento de avaliação da área de conhecimento', 'ed157', '2020-03-05', 'Procedimento da área', 0, 'f', 'f', 'f', 'f' ),
               (1010534, 'areaprocedimentoavaliacao', 'Avaliações do procedimento por área de conhecimento', 'ed158', '2020-03-05', 'Avaliação da área', 0, 'f', 'f', 'f', 'f' ),
               (1010535, 'areaprocedimentoresultado', 'Resultado do procedimento por área de conhecimento', 'ed159', '2020-03-05', 'Resultado do procedimento', 0, 'f', 'f', 'f', 'f' ),
               (1010536, 'areaprocedimentocomposicaoresultado', 'Elementos de avaliação que compõe o resultado do procedimento por área.', 'ed160', '2020-03-05', 'Composição do Resultado', 0, 'f', 'f', 'f', 'f' );
        ");

        $this->execute("
        insert into db_sysarqmod
        values (1008004,1010533),
               (1008004,1010534),
               (1008004,1010535),
               (1008004,1010536);
        ");

        $this->execute("
        insert into db_syscampo
        values (1011091,'ed157_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011092,'ed157_procedimento','int4','Procedimento de avaliação vínculado','0', 'Procedimento',10,'f','f','f',1,'text','Procedimento'),
               (1011093,'ed158_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011094,'ed158_areaprocedimento','int4','Procedimento da área','0', 'Procedimento da área',10,'f','f','f',1,'text','Procedimento da área'),
               (1011095,'ed158_formaavaliacao','int4','Forma de avaliação do elemento','0', 'Forma de avaliação',10,'f','f','f',1,'text','Forma de avaliação'),
               (1011096,'ed158_periodoavaliacao','int4','Período de Avaliação','0', 'Período de Avaliação',10,'f','f','f',1,'text','Período de Avaliação'),
               (1011097,'ed158_tipo','char(1)','Tipo do elemento de avaliação que compõe a nota do período. Pode ser uma avaliação ou um resultado do procedimento vinculado. Valores aceitos: A - Avaliação R - Resultado','', 'Tipo do Elemento',1,'f','t','f',0,'text','Tipo do Elemento'),
               (1011098,'ed158_ordem_elemento','int4','Ordem do Elemento que gera o resultado. Pode ser uma avaliação ou um resultado do procedimento vinculado. ','0', 'Ordem do Elemento',10,'f','f','f',1,'text','Ordem do Elemento'),
               (1011099,'ed158_formaobtencao','char(10)','Forma de cálculo do elemento. Exemplo: MA - Média Aritimética','', 'Forma de Cálculo',10,'f','t','f',0,'text','Forma de Cálculo'),
               (1011100,'ed158_peso','int4','O peso é utilizado apenas em avaliações onde a forma de cálculo é Média Ponderada (MO)','0', 'Peso da Avaliação',10,'f','f','f',1,'text','Peso da Avaliação'),
               (1011101,'ed158_ordem','int4','Ordem do elemento no procedimento','0', 'Ordem do Elemento',10,'f','f','f',1,'text','Ordem do Elemento'),
               (1011102,'ed159_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011103,'ed159_areaprocedimento','int4','Procedimento da área','0', 'Procedimento da área',10,'f','f','f',1,'text','Procedimento da área'),
               (1011104,'ed159_formaavaliacao','int4','Forma de avaliação do resultado','0', 'Forma de avaliação',10,'f','f','f',1,'text','Forma de avaliação'),
               (1011105,'ed159_resultado','int4','Resultado do procedimento','0', 'Resultado do procedimento',10,'f','f','f',1,'text','Resultado do procedimento'),
               (1011106,'ed159_formaobtencao','varchar(2)','Forma de cálculo do elemento. Exemplo: MA - Média Aritimética','', 'Forma de Cálculo',2,'f','t','f',0,'text','Forma de Cálculo'),
               (1011107,'ed159_peso','int4','O peso é utilizado apenas em avaliações onde a forma de cálculo é Média Ponderada (MO)','1', 'Peso da Avaliação',10,'f','f','f',1,'text','Peso da Avaliação'),
               (1011108,'ed159_ordem','int4','Ordem do elemento no procedimento','0', 'Ordem do Elemento',10,'f','f','f',1,'text','Ordem do Elemento'),
               (1011109,'ed160_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011110,'ed160_areaprocedimentoresultado','int4','Resultado do procedimento','0', 'Resultado do procedimento',10,'f','f','f',1,'text','Resultado do procedimento'),
               (1011111,'ed160_areaprocedimentoavaliacao','int4','Avaliação do procedimento','0', 'Avaliação do procedimento',10,'f','f','f',1,'text','Avaliação do procedimento');
        ");

        $this->execute("
        insert into db_sysarqcamp
        values (1010533,1011091,1,0),
               (1010533,1011092,2,0),
               (1010534,1011093,1,0),
               (1010534,1011094,2,0),
               (1010534,1011095,3,0),
               (1010534,1011096,4,0),
               (1010534,1011097,5,0),
               (1010534,1011098,6,0),
               (1010534,1011099,7,0),
               (1010534,1011100,8,0),
               (1010534,1011101,9,0),
               (1010535,1011102,1,0),
               (1010535,1011103,2,0),
               (1010535,1011104,3,0),
               (1010535,1011105,4,0),
               (1010535,1011106,5,0),
               (1010535,1011107,6,0),
               (1010535,1011108,7,0),
               (1010536,1011109,1,0),
               (1010536,1011110,2,0),
               (1010536,1011111,3,0);
        ");

        $this->execute("
        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values (1010533,1011091,1,1011091),
               (1010534,1011093,1,1011093),
               (1010535,1011102,1,1011102),
               (1010536,1011109,1,1011109);
        ");

        $this->execute("
        insert into db_sysforkey
        values (1010533,1011092,1,1010074,0),
               (1010534,1011094,1,1010533,0),
               (1010534,1011095,1,1010071,0),
               (1010534,1011096,1,1010056,0),
               (1010535,1011103,1,1010533,0),
               (1010535,1011104,1,1010071,0),
               (1010535,1011105,1,1010077,0),
               (1010536,1011110,1,1010535,0),
               (1010536,1011111,1,1010534,0);
       ");

        $this->execute("
        insert into db_sysindices
        values (1008540,'areaprocedimento_procedimento_in',1010533,'1'),
               (1008541,'areaprocedimentoavaliacao_areaprocedimento_in',1010534,'0'),
               (1008542,'areaprocedimentoavaliacao_formaavaliacao_in',1010534,'0'),
               (1008543,'areaprocedimentoavaliacao_periodoavaliacao_in',1010534,'0'),
               (1008544,'areaprocedimentoresultado_areaprocedimento_in',1010535,'0'),
               (1008545,'areaprocedimentoresultado_formaavaliacao_in',1010535,'0'),
               (1008546,'areaprocedimentoresultado_resultado_in',1010535,'0'),
               (1008547,'areaprocedimentocomposicaoresultado_areaprocedimentoresultado_in',1010536,'0'),
               (1008548,'areaprocedimentocomposicaoresultado_areaprocedimentoavaliacao_in',1010536,'0');
        ");

        $this->execute("
        insert into db_syscadind
        values (1008540,1011092,1),
               (1008541,1011094,1),
               (1008542,1011095,1),
               (1008543,1011096,1),
               (1008544,1011103,1),
               (1008545,1011104,1),
               (1008546,1011105,1),
               (1008547,1011110,1),
               (1008548,1011111,1);
        ");

        $this->execute("
        insert into db_syssequencia
        values (1000883, 'areaprocedimento_ed157_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000884, 'areaprocedimentoavaliacao_ed158_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000885, 'areaprocedimentoresultado_ed159_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000886, 'areaprocedimentocomposicaoresultado_ed160_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
        ");

        $this->execute("
        update db_sysarqcamp set codsequencia = 1000883 where codarq = 1010533 and codcam = 1011091;
        update db_sysarqcamp set codsequencia = 1000884 where codarq = 1010534 and codcam = 1011093;
        update db_sysarqcamp set codsequencia = 1000885 where codarq = 1010535 and codcam = 1011102;
        update db_sysarqcamp set codsequencia = 1000886 where codarq = 1010536 and codcam = 1011109;
        ");
    }

    private function dicionarioDown()
    {
        $this->execute("
        delete from db_sysforkey where codarq in (1010533, 1010534, 1010535, 1010536);
        delete from db_sysprikey where codarq in (1010533, 1010534, 1010535, 1010536);
        delete from db_sysarqcamp where codarq in (1010533, 1010534, 1010535, 1010536);
        delete from db_sysarqmod where codarq in (1010533, 1010534, 1010535, 1010536);
        delete from db_syscampo where codcam in (1011091, 1011092, 1011093, 1011094, 1011095, 1011096, 1011097, 1011098, 1011099, 1011100, 1011101, 1011102, 1011103, 1011104, 1011105, 1011106, 1011107, 1011108, 1011109, 1011110, 1011111);
        delete from db_syscadind where codind in (1008540, 1008541, 1008542, 1008543, 1008544, 1008545, 1008546, 1008547, 1008548);
        delete from db_sysindices where codind in (1008540, 1008541, 1008542, 1008543, 1008544, 1008545, 1008546, 1008547, 1008548);
        delete from db_syssequencia where codsequencia in (1000883, 1000884, 1000885, 1000886);
        delete from db_sysarquivo where codarq in (1010533, 1010534, 1010535, 1010536);
        ");
    }

    private function estruturaDown()
    {
        $this->execute("
        drop table if exists escola.areaprocedimento cascade;
        drop table if exists escola.areaprocedimentoavaliacao cascade;
        drop table if exists escola.areaprocedimentocomposicaoresultado cascade;
        drop table if exists escola.areaprocedimentoresultado cascade;
        ");

        $this->execute("
        drop sequence if exists escola.areaprocedimento_ed157_codigo_seq;
        drop sequence if exists escola.areaprocedimentoavaliacao_ed158_codigo_seq;
        drop sequence if exists escola.areaprocedimentocomposicaoresultado_ed160_codigo_seq;
        drop sequence if exists escola.areaprocedimentoresultado_ed159_codigo_seq;
        ");
    }

    private function estrutura()
    {
        $this->execute("
        create sequence escola.areaprocedimento_ed157_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        create sequence escola.areaprocedimentoavaliacao_ed158_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        create sequence escola.areaprocedimentocomposicaoresultado_ed160_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        create sequence escola.areaprocedimentoresultado_ed159_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute("
        CREATE TABLE escola.areaprocedimento(
            ed157_codigo int4 not null,
            ed157_procedimento int4 not null,
            CONSTRAINT areaprocedimento_codi_pk PRIMARY KEY (ed157_codigo)
        );

        CREATE TABLE escola.areaprocedimentoavaliacao(
            ed158_codigo int4 not null,
            ed158_areaprocedimento int4 not null,
            ed158_formaavaliacao int4 not null,
            ed158_periodoavaliacao int4 not null,
            ed158_tipo varchar(1) not null,
            ed158_ordem_elemento int4 not null,
            ed158_formaobtencao char(2) not null,
            ed158_peso int4 not null,
            ed158_ordem int4 not null,
            CONSTRAINT areaprocedimentoavaliacao_codi_pk PRIMARY KEY (ed158_codigo)
        );

        CREATE TABLE escola.areaprocedimentoresultado(
            ed159_codigo int4 not null,
            ed159_areaprocedimento int4 not null,
            ed159_formaavaliacao int4 not null,
            ed159_resultado int4 not null,
            ed159_formaobtencao varchar(2) not null,
            ed159_peso int4 not null,
            ed159_ordem int4 not null,
            CONSTRAINT areaprocedimentoresultado_codi_pk PRIMARY KEY (ed159_codigo)
        );

        CREATE TABLE escola.areaprocedimentocomposicaoresultado(
            ed160_codigo int4 not null,
            ed160_areaprocedimentoresultado int4 not null,
            ed160_areaprocedimentoavaliacao int4 not null,
            CONSTRAINT areaprocedimentocomposicaoresultado_codi_pk PRIMARY KEY (ed160_codigo)
        );
        ");

        $this->execute("
        alter table escola.areaprocedimento add constraint areaprocedimento_procedimento_fk foreign key (ed157_procedimento) references procedimento;
        alter table escola.areaprocedimentoavaliacao add constraint areaprocedimentoavaliacao_areaprocedimento_fk foreign key (ed158_areaprocedimento) references areaprocedimento;
        alter table escola.areaprocedimentoavaliacao add constraint areaprocedimentoavaliacao_formaavaliacao_fk foreign key (ed158_formaavaliacao) references formaavaliacao;
        alter table escola.areaprocedimentoavaliacao add constraint areaprocedimentoavaliacao_periodoavaliacao_fk foreign key (ed158_periodoavaliacao) references periodoavaliacao;
        alter table escola.areaprocedimentocomposicaoresultado add constraint areaprocedimentocomposicaoresultado_areaprocedimentoavaliacao_fk foreign key (ed160_areaprocedimentoavaliacao) references areaprocedimentoavaliacao;
        alter table escola.areaprocedimentocomposicaoresultado add constraint areaprocedimentocomposicaoresultado_areaprocedimentoresultado_fk foreign key (ed160_areaprocedimentoresultado) references areaprocedimentoresultado;
        alter table escola.areaprocedimentoresultado add constraint areaprocedimentoresultado_areaprocedimento_fk foreign key (ed159_areaprocedimento) references areaprocedimento;
        alter table escola.areaprocedimentoresultado add constraint areaprocedimentoresultado_formaavaliacao_fk foreign key (ed159_formaavaliacao) references formaavaliacao;
        alter table escola.areaprocedimentoresultado add constraint areaprocedimentoresultado_resultado_fk foreign key (ed159_resultado) references resultado;
        ");

        $this->execute("
        create unique index areaprocedimento_procedimento_in ON escola.areaprocedimento(ed157_procedimento);
        create index areaprocedimentoavaliacao_areaprocedimento_in ON escola.areaprocedimentoavaliacao(ed158_areaprocedimento);
        create index areaprocedimentoavaliacao_formaavaliacao_in ON escola.areaprocedimentoavaliacao(ed158_formaavaliacao);
        create index areaprocedimentoavaliacao_periodoavaliacao_in ON escola.areaprocedimentoavaliacao(ed158_periodoavaliacao);
        create index areaprocedimentocomposicaoresultado_areaprocedimentoresultado_in ON escola.areaprocedimentocomposicaoresultado(ed160_areaprocedimentoresultado);
        create index areaprocedimentocomposicaoresultado_areaprocedimentoavaliacao_in ON escola.areaprocedimentocomposicaoresultado(ed160_areaprocedimentoavaliacao);
        create index areaprocedimentoresultado_areaprocedimento_in ON escola.areaprocedimentoresultado(ed159_areaprocedimento);
        create index areaprocedimentoresultado_formaavaliacao_in ON escola.areaprocedimentoresultado(ed159_formaavaliacao);
        create index areaprocedimentoresultado_resultado_in ON escola.areaprocedimentoresultado(ed159_resultado);
        ");
    }
}
