<?php

use Classes\PostgresMigration;

class M15767AlteracaoEstruturalBncc extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();

        $this->estrutura();
        $this->correcaoDados();
        $this->migraEstruturaBNCC();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function correcaoDados()
    {
        $this->execute("
            update escola.bnccensinofundamental set ed148_etapa = replace(ed148_etapa, ';', ',');
        ");
    }

    private function migraEstruturaBNCC()
    {
        $this->execute("
            insert into escola.bncceducacaoinfantiloriginal
             select ed147_sequencial,
                      ed147_disciplina,
                      ed147_faixa_etaria,
                      ed147_codigo,
                      ed147_habilidade
                 from escola.bncceducacaoinfantil;

            insert into escola.bnccensinofundamentaloriginal
             select ed148_sequencial,
                      ed148_disciplina,
                      ed148_etapa,
                      ed148_codigo,
                      ed148_unidade_tematica,
                      ed148_objeto_conhecimento,
                      ed148_habilidade
                 from escola.bnccensinofundamental;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            ALTER TABLE sec_parametros ADD COLUMN ed290_bncc int4 NOT NULL DEFAULT 1;
        ");

        $this->execute("
            CREATE SEQUENCE escola.bncceducacaoinfantiloriginal_ed167_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE escola.bnccensinofundamentaloriginal_ed166_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE escola.bnccreferencial_ed168_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE escola.diario_classe_bncc_habilidade_referencial_ed169_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute("
            CREATE TABLE escola.bncceducacaoinfantiloriginal(
                ed167_sequencial int4 not null,
                ed167_disciplina varchar(100) not null,
                ed167_faixa_etaria varchar(100) not null,
                ed167_codigo varchar(8) not null,
                ed167_habilidade text not null,
            CONSTRAINT bncceducacaoinfantiloriginal_sequ_pk PRIMARY KEY (ed167_sequencial));

            CREATE TABLE escola.bnccensinofundamentaloriginal(
                ed166_sequencial int4 not null,
                ed166_disciplina varchar(100) not null,
                ed166_etapa varchar(100) not null,
                ed166_codigo varchar(8) not null,
                ed166_unidade_tematica varchar(150) not null,
                ed166_objeto_conhecimento text not null,
                ed166_habilidade text not null,
            CONSTRAINT bnccensinofundamentaloriginal_sequ_pk PRIMARY KEY (ed166_sequencial));

            CREATE TABLE escola.bnccreferencial(
                ed168_codigo int4 not null,
                ed168_ensino varchar(2) not null,
                ed168_etapa varchar(100),
                ed168_codigohabilidade varchar(10) not null,
                ed168_codigoreferencial varchar(20),
                ed168_habilidade text not null,
                ed168_ano int4 not null,
            CONSTRAINT bnccreferencial_codi_pk PRIMARY KEY (ed168_codigo));

            CREATE TABLE escola.diario_classe_bncc_habilidade_referencial(
                ed169_codigo int4 not null,
                ed169_diario_classe_bncc_habilidade int4 not null,
                ed169_bnccreferencial int4 not null,
            CONSTRAINT diario_classe_bncc_habilidade_referencial_codi_pk PRIMARY KEY (ed169_codigo));
        ");

        $this->execute("
            ALTER TABLE diario_classe_bncc_habilidade_referencial
            ADD CONSTRAINT diario_classe_bncc_habilidade_referencial_classe_fk FOREIGN KEY (ed169_diario_classe_bncc_habilidade)
            REFERENCES diario_classe_bncc_habilidade ON DELETE CASCADE;

            ALTER TABLE diario_classe_bncc_habilidade_referencial
            ADD CONSTRAINT diario_classe_bncc_habilidade_referencial_bnccreferencial_fk FOREIGN KEY (ed169_bnccreferencial)
            REFERENCES bnccreferencial ON DELETE CASCADE;
        ");


        $this->execute("
            ALTER TABLE escola.bncceducacaoinfantil ADD COLUMN ed147_ano int4 NOT NULL DEFAULT 2020;
            ALTER TABLE escola.bnccensinofundamental ADD COLUMN ed148_ano int4 NOT NULL DEFAULT 2020;
        ");


        $this->execute("
            CREATE INDEX bncceducacaoinfantil_ano_in ON bncceducacaoinfantil(ed147_ano);
            CREATE INDEX bncceducacaoinfantiloriginal_disciplina_in ON bncceducacaoinfantiloriginal(ed167_disciplina);
            CREATE INDEX bncceducacaoinfantiloriginal_faixa_etaria_in ON bncceducacaoinfantiloriginal(ed167_faixa_etaria);
            CREATE INDEX bncceducacaoinfantiloriginal_codigo_in ON bncceducacaoinfantiloriginal(ed167_codigo);
            CREATE INDEX bnccensinofundamental_ano_in ON bnccensinofundamental(ed148_ano);
            CREATE INDEX bnccensinofundamentaloriginal_codigo_in ON bnccensinofundamentaloriginal(ed166_codigo);
            CREATE INDEX bnccensinofundamentaloriginal_unidade_tematica_in ON bnccensinofundamentaloriginal(ed166_unidade_tematica);
            CREATE INDEX bnccensinofundamentaloriginal_objeto_conhecimento_in ON bnccensinofundamentaloriginal(ed166_objeto_conhecimento);
            CREATE INDEX bnccensinofundamentaloriginal_etapa_in ON bnccensinofundamentaloriginal(ed166_etapa);
            CREATE INDEX bnccensinofundamentaloriginal_disciplina_in ON bnccensinofundamentaloriginal(ed166_disciplina);
            CREATE INDEX bnccreferencial_ensino_in ON bnccreferencial(ed168_ensino);
            CREATE INDEX bnccreferencial_etapa_in ON bnccreferencial(ed168_etapa);
            CREATE INDEX bnccreferencial_codigohabilidade_in ON bnccreferencial(ed168_codigohabilidade);
            CREATE INDEX bnccreferencial_codigoreferencial_in ON bnccreferencial(ed168_codigoreferencial);
            CREATE INDEX bnccreferencial_ano_in ON bnccreferencial(ed168_ano);
            CREATE INDEX diarioclassebncchabilidadereferencial_bnccreferencia_in ON diario_classe_bncc_habilidade_referencial(ed169_bnccreferencial);
        ");
    }

    private function estruturaDown()
    {
        $this->execute("
            DROP TABLE IF EXISTS escola.bncceducacaoinfantiloriginal CASCADE;
            DROP TABLE IF EXISTS escola.bnccensinofundamentaloriginal CASCADE;
            DROP TABLE IF EXISTS escola.bnccreferencial CASCADE;
            DROP TABLE IF EXISTS escola.diario_classe_bncc_habilidade_referencial CASCADE;

            DROP SEQUENCE IF EXISTS escola.bncceducacaoinfantiloriginal_ed167_sequencial_seq;
            DROP SEQUENCE IF EXISTS escola.bnccensinofundamentaloriginal_ed166_sequencial_seq;
            DROP SEQUENCE IF EXISTS escola.bnccreferencial_ed168_codigo_seq;
            DROP SEQUENCE IF EXISTS escola.diario_classe_bncc_habilidade_referencial_ed169_codigo_seq;

            ALTER TABLE secretariadeeducacao.sec_parametros DROP COLUMN ed290_bncc;
            ALTER TABLE escola.bncceducacaoinfantil DROP COLUMN ed147_ano;
            ALTER TABLE escola.bnccensinofundamental DROP COLUMN ed148_ano;
        ");
    }

    private function dicionario()
    {
       $this->execute("alter table db_sysarquivo alter column nomearq type varchar(45);");
       $this->execute("
        insert into db_sysarquivo
        values (1010612, 'bnccensinofundamentaloriginal', 'Estrutura da BNCC', 'ed166', '2020-08-13', 'Estrutura da BNCC', 0, 'f', 'f', 'f', 'f' ),
               (1010613, 'bncceducacaoinfantiloriginal', 'Estrutura da BNCC das habilidades do ensino infantil', 'ed167', '2020-08-13', 'Estrutura da BNCC EI', 0, 'f', 'f', 'f', 'f' ),
               (1010614, 'bnccreferencial', 'Essa tabela tem as habilidades do Referencial Curricular Estadual', 'ed168', '2020-08-13', 'Referencial Curricular Estadual', 0, 'f', 'f', 'f', 'f' ),
               (1010615, 'diario_classe_bncc_habilidade_referencial', 'Habilidade do referencial lançada no lançamento de conteúdo', 'ed169', '2020-08-13', 'Habilidade do referencial', 0, 'f', 'f', 'f', 'f' );

        insert into db_sysarqmod
        values (1008004,1010612),
               (1008004,1010613),
               (1008004,1010614),
               (1008004,1010615);

        insert into db_syscampo
        values (1011759,'ed166_sequencial','int4','Código PK','0', 'Código PK',10,'f','f','f',1,'text','Código PK'),
               (1011760,'ed166_disciplina','varchar(100)','Disciplina BNCC','', 'Disciplina BNCC',100,'f','f','f',0,'text','Disciplina BNCC'),
               (1011761,'ed166_etapa','varchar(100)','Etapa BNCC','', 'Etapa BNCC',100,'f','f','f',0,'text','Etapa BNCC'),
               (1011762,'ed166_codigo','varchar(8)','Código BNCC','', 'Código BNCC',8,'f','t','f',0,'text','Código BNCC'),
               (1011763,'ed166_unidade_tematica','varchar(150)','Unidade Temática','', 'Unidade Temática',150,'f','f','f',0,'text','Unidade Temática'),
               (1011764,'ed166_objeto_conhecimento','text','Objeto de Conhecimento','', 'Objeto de Conhecimento',1,'f','t','f',0,'text','Objeto de Conhecimento'),
               (1011765,'ed166_habilidade','text','Habilidade da BNCC','', 'Habilidade',1,'f','t','f',0,'text','Habilidade'),
               (1011766,'ed167_sequencial','int4','Código pk','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011767,'ed167_disciplina','varchar(100)','Disciplina BNCC','', 'Disciplina BNCC',100,'f','f','f',0,'text','Disciplina BNCC'),
               (1011768,'ed167_faixa_etaria','varchar(100)','Faixa Etaria','', 'Faixa Etaria',100,'f','t','f',0,'text','Faixa Etaria'),
               (1011769,'ed167_codigo','varchar(8)','Código BNCC','', 'Código BNCC',8,'f','t','f',0,'text','Código BNCC'),
               (1011770,'ed167_habilidade','text','Habilidade da BNCC','', 'Habilidade',1,'f','f','f',0,'text','Habilidade'),
               (1011771,'ed168_codigo','int4','Código PK','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011772,'ed168_ensino','varchar(2)','Sigla do Ensino na BNCC onde: EI : Ensino Infantil EF: Ensino Fundamental EM: Ensino Médio','', 'Ensino BNCC',2,'f','t','f',0,'text','Ensino BNCC'),
               (1011773,'ed168_etapa','varchar(100)','Etapa da BNCC separado por vírgula quando mais de uma','', 'Etapa BNCC',100,'t','t','f',0,'text','Etapa BNCC'),
               (1011774,'ed168_codigohabilidade','varchar(10)','Código de uma habilidade da BNCC de uma das tabelas: bnccensinofundamental ou bncceducacaoinfantil','', 'Código BNCC',10,'f','t','f',0,'text','Código BNCC'),
               (1011775,'ed168_codigoreferencial','varchar(20)','Código do Referencial Curricular Estadual','', 'Código Referencial',20,'f','t','f',0,'text','Código Referencial'),
               (1011776,'ed168_habilidade','text','Habilidade do referencial','', 'Habilidade do referencial',1,'f','t','f',0,'text','Habilidade do referencial'),
               (1011780,'ed168_ano','int4','Ano de validade','0', 'Ano ',4,'f','f','f',1,'text','Ano '),
               (1011777,'ed169_codigo','int4','Código PK','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011778,'ed169_diario_classe_bncc_habilidade','int4','Código da Habilidade lançada','0', 'Código da Habilidade lançada',10,'f','f','f',1,'text','Código da Habilidade lançada'),
               (1011779,'ed169_bnccreferencial','int4','Código Referencial lançado','0', 'Código Referencial',10,'f','f','f',1,'text','Código Referencial'),
               (1011781,'ed148_ano','int4','Ano de validade','0', 'Ano',4,'f','f','f',1,'text','Ano'),
               (1011782,'ed147_ano','int4','Ano de validade','0', 'Ano',4,'f','f','f',1,'text','Ano'),
               (1011783,'ed290_bncc','int4','Como a BNCC esta configurada sendo: 1 - BNCC Padrão 2 - BNCC Comentada 3 - Referencial Curricular Estadual','0', 'Base Curricular',10,'f','f','f',1,'text','Base Curricular');

        insert into db_sysarqcamp
        values (1010612,1011759,1,0),
               (1010612,1011760,2,0),
               (1010612,1011761,3,0),
               (1010612,1011762,4,0),
               (1010612,1011763,5,0),
               (1010612,1011764,6,0),
               (1010612,1011765,7,0),
               (1010613,1011766,1,0),
               (1010613,1011767,2,0),
               (1010613,1011768,3,0),
               (1010613,1011769,4,0),
               (1010613,1011770,5,0),
               (1010615,1011777,1,0),
               (1010615,1011778,2,0),
               (1010615,1011779,3,0),
               (1010614,1011771,1,0),
               (1010614,1011772,2,0),
               (1010614,1011773,3,0),
               (1010614,1011774,4,0),
               (1010614,1011775,5,0),
               (1010614,1011776,6,0),
               (1010614,1011780,7,0),
               (1010503,1011781,8,0),
               (1010502,1011782,6,0),
               (3180,1011783,5,0);


        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values (1010612,1011759,1,1011759),
               (1010613,1011766,1,1011766),
               (1010614,1011771,1,1011771),
               (1010615,1011777,1,1011777);


        insert into db_sysindices
        values (1008591,'bnccensinofundamentaloriginal_codigo_in',1010612,'0'),
               (1008592,'bnccensinofundamentaloriginal_unidade_tematica_in',1010612,'0'),
               (1008593,'bnccensinofundamentaloriginal_objeto_conhecimento_in',1010612,'0'),
               (1008594,'bnccensinofundamentaloriginal_etapa_in',1010612,'0'),
               (1008595,'bnccensinofundamentaloriginal_disciplina_in',1010612,'0'),
               (1008596,'bncceducacaoinfantiloriginal_disciplina_in',1010613,'0'),
               (1008597,'bncceducacaoinfantiloriginal_faixa_etaria_in',1010613,'0'),
               (1008598,'bncceducacaoinfantiloriginal_codigo_in',1010613,'0'),
               (1008599,'bnccreferencial_ensino_in',1010614,'0'),
               (1008600,'bnccreferencial_etapa_in',1010614,'0'),
               (1008601,'bnccreferencial_codigohabilidade_in',1010614,'0'),
               (1008602,'bnccreferencial_codigoreferencial_in',1010614,'0'),
               (1008603,'diarioclassebncchabilidadereferencial_bnccreferencia_in',1010615,'0'),
               (1008604,'bnccreferencial_ano_in',1010614,'0'),
               (1008605,'bncceducacaoinfantil_ano_in',1010502,'0'),
               (1008606,'bnccensinofundamental_ano_in',1010503,'0');


        insert into db_syscadind
        values (1008591,1011762,1),
               (1008592,1011763,1),
               (1008593,1011764,1),
               (1008594,1011761,1),
               (1008595,1011760,1),
               (1008596,1011767,1),
               (1008597,1011768,1),
               (1008598,1011769,1),
               (1008599,1011772,1),
               (1008600,1011773,1),
               (1008601,1011774,1),
               (1008602,1011775,1),
               (1008603,1011779,2),
               (1008604,1011780,1),
               (1008605,1011782,1),
               (1008606,1011781,1);


        insert into db_syssequencia
        values (1000963, 'bnccensinofundamentaloriginal_ed166_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000964, 'bncceducacaoinfantiloriginal_ed167_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000965, 'bnccreferencial_ed168_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000966, 'diario_classe_bncc_habilidade_referencial_ed169_codigo_seq', 1, 1, 9223372036854775807, 1, 1);

        update db_sysarqcamp set codsequencia = 1000963 where codarq = 1010612 and codcam = 1011759;
        update db_sysarqcamp set codsequencia = 1000964 where codarq = 1010613 and codcam = 1011766;
        update db_sysarqcamp set codsequencia = 1000965 where codarq = 1010614 and codcam = 1011771;
        update db_sysarqcamp set codsequencia = 1000966 where codarq = 1010615 and codcam = 1011777;



        insert into db_sysforkey
        values (1010615,1011778,1,1010521,0),
               (1010615,1011779,1,1010614,0);
       ");
    }

    private function dicionarioDown()
    {
        $this->execute("
            delete from db_sysprikey where codarq in (1010612, 1010613, 1010614, 1010615);
            delete from db_sysforkey where codarq in (1010612, 1010613, 1010614, 1010615);
            delete from db_sysarqcamp where codarq in (1010612, 1010613, 1010614, 1010615);
            delete from db_sysarqcamp where codcam in (1011781, 1011782, 1011783);
            delete from db_syssequencia where codsequencia in (1000963, 1000964, 1000965, 1000966);
            delete from db_syscadind  where codind in (1008591, 1008592, 1008593, 1008594, 1008595, 1008596, 1008597, 1008598, 1008599, 1008600, 1008601, 1008602, 1008603, 1008604, 1008605, 1008606);
            delete from db_sysindices where codind in (1008591, 1008592, 1008593, 1008594, 1008595, 1008596, 1008597, 1008598, 1008599, 1008600, 1008601, 1008602, 1008603, 1008604, 1008605, 1008606);
            delete from db_syscampodep where codcam in (1011759, 1011760, 1011761, 1011762, 1011763, 1011764, 1011765, 1011766, 1011767, 1011768, 1011769, 1011770, 1011771, 1011772, 1011773, 1011774, 1011775, 1011776, 1011780, 1011777, 1011778, 1011779, 1011781, 1011782, 1011783);
            delete from db_syscampodef where codcam in (1011759, 1011760, 1011761, 1011762, 1011763, 1011764, 1011765, 1011766, 1011767, 1011768, 1011769, 1011770, 1011771, 1011772, 1011773, 1011774, 1011775, 1011776, 1011780, 1011777, 1011778, 1011779, 1011781, 1011782, 1011783);
            delete from db_syscampo where codcam in (1011759, 1011760, 1011761, 1011762, 1011763, 1011764, 1011765, 1011766, 1011767, 1011768, 1011769, 1011770, 1011771, 1011772, 1011773, 1011774, 1011775, 1011776, 1011780, 1011777, 1011778, 1011779, 1011781, 1011782, 1011783);
            delete from db_sysarqmod where codarq in (1010612, 1010613, 1010614, 1010615);
            delete from db_sysarquivo where codarq in (1010612, 1010613, 1010614, 1010615);

        ");
    }
}
