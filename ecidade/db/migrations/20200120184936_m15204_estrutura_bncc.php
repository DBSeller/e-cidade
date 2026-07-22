<?php

use Classes\PostgresMigration;

class M15204EstruturaBncc extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionario();
        $this->criaEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->dowEstrutura();
    }

    private function criaDicionario()
    {
        $this->execute("
        insert into db_sysarquivo
        values (1010502, 'bncceducacaoinfantil', 'Tabela de habilidade do ensino infantil', 'ed147', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' ),
               (1010503, 'bnccensinofundamental', 'Tabela de habilidade do ensino fundamental', 'ed148', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' ),
               (1010504, 'bnccdisciplinas', 'Tabela de disciplinas da bncc', 'ed149', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' ),
               (1010505, 'bnccetapas', 'Tabela de etapas da BNCC', 'ed152', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' ),
               (1010506, 'caddisciplinabnccdisciplinas', 'Tabela de depara das disciplinas do e-cidade com as do bncc', 'ed153', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' ),
               (1010507, 'seriebnccetapas', 'tabela de depara das etapas do e-cidade com as etapas da bncc', 'ed154', '2020-01-20', '', 0, 'f', 'f', 'f', 'f' );
        ");

        $this->execute("
        insert into db_sysarqmod
        values (1008004,1010502),
               (1008004,1010503),
               (1008004,1010504),
               (1008004,1010505),
               (1008004,1010506),
               (1008004,1010507);
        ");
        $this->execute("
        insert into db_syscampo
        values (1010910,'ed147_sequencial','int4','Código PK','0', 'ID',10,'f','f','f',1,'text','ID'),
               (1010911,'ed147_disciplina','varchar(100)','Disciplina','', 'Disciplina',100,'f','t','f',0,'text','Disciplina'),
               (1010912,'ed147_faixa_etaria','varchar(100)','Faixa Etaria','', 'Faixa Etaria',100,'f','t','f',0,'text','Faixa Etaria'),
               (1010913,'ed147_codigo','varchar(8)','Código BNCC','', 'Código BNCC',8,'f','f','f',0,'text','Código BNCC'),
               (1010914,'ed147_habilidade','varchar(255)','Habilidade','', 'Habilidade',255,'f','t','f',0,'text','Habilidade'),
               (1010915,'ed148_sequencial','int4','Código pk','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1010916,'ed148_disciplina','varchar(100)','Disciplina','', 'Disciplina',100,'f','f','f',0,'text','Disciplina'),
               (1010917,'ed148_etapa','varchar(100)','Etapa ','', 'Etapa',100,'f','f','f',0,'text','Etapa'),
               (1010918,'ed148_codigo','varchar(8)','Código BNCC','', 'Código BNCC',8,'f','t','f',0,'text','Código BNCC'),
               (1010919,'ed148_unidade_tematica','varchar(100)','Unidade Temática','', 'Unidade Temática',100,'f','f','f',0,'text','Unidade Temática'),
               (1010920,'ed148_objeto_conhecimento','varchar(255)','Objeto de Conhecimento','', 'Objeto de Conhecimento',255,'f','f','f',0,'text','Objeto de Conhecimento'),
               (1010921,'ed148_habilidade','varchar(255)','Habilidade','', 'Habilidade',255,'f','f','f',0,'text','Habilidade'),
               (1010922,'ed149_sequencial','int4','Código PK','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1010923,'ed149_nome','varchar(100)','Nome da disciplina','', 'Nome',100,'f','f','f',0,'text','Nome'),
               (1010924,'ed149_sigla','varchar(3)','Sigla','', 'Sigla',3,'f','t','f',0,'text','Sigla'),
               (1010925,'ed149_area_conhecimento','varchar(100)','Área de Conhecimento','', 'Área de Conhecimento',100,'f','t','f',0,'text','Área de Conhecimento'),
               (1010926,'ed149_ensino','varchar(2)','Ensino','', 'Ensino',2,'t','t','f',0,'text','Ensino'),
               (1010927,'ed152_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1010928,'ed152_etapa','varchar(10)','Etapa','', 'Etapa',10,'f','f','f',0,'text','Etapa'),
               (1010929,'ed152_ensino','varchar(2)','Ensino','', 'Ensino',2,'f','t','f',0,'text','Ensino'),
               (1010930,'ed153_sequencial','int4','Código PK','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1010931,'ed153_caddisciplina','int4','Disciplina e-cidade','0', 'Disciplina e-cidade',10,'f','f','f',1,'text','Disciplina e-cidade'),
               (1010932,'ed153_bnccdisciplina','int4','Disciplina BNCC','0', 'Disciplina BNCC',10,'f','f','f',1,'text','Disciplina BNCC'),
               (1010933,'ed154_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Etapa e-Cidade'),
               (1010934,'ed154_bnccetapa','int4','Etapa BNCC','0', 'Etapa BNCC',10,'f','f','f',1,'text','Etapa BNCC'),
               (1010935,'ed154_serie','int4','Etapa e-Cidade','0', 'Etapa e-Cidade',10,'f','f','f',1,'text','Etapa e-Cidade');
        ");
        $this->execute("
        insert into db_sysarqcamp
        values (1010502,1010910,1,0),
               (1010502,1010911,2,0),
               (1010502,1010912,3,0),
               (1010502,1010913,4,0),
               (1010502,1010914,5,0),
               (1010503,1010915,1,0),
               (1010503,1010916,2,0),
               (1010503,1010917,3,0),
               (1010503,1010918,4,0),
               (1010503,1010919,5,0),
               (1010503,1010920,6,0),
               (1010503,1010921,7,0),
               (1010504,1010922,1,0),
               (1010504,1010923,2,0),
               (1010504,1010924,3,0),
               (1010504,1010925,4,0),
               (1010504,1010926,5,0),
               (1010505,1010927,1,0),
               (1010505,1010928,2,0),
               (1010505,1010929,3,0),
               (1010506,1010930,1,0),
               (1010506,1010931,2,0),
               (1010506,1010932,3,0),
               (1010507,1010933,1,0),
               (1010507,1010935,2,0),
               (1010507,1010934,3,0);
        ");
        $this->execute("
        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values (1010502,1010910,1,1010911),
               (1010503,1010915,1,1010918),
               (1010504,1010922,1,1010923),
               (1010505,1010927,1,1010928),
               (1010506,1010930,1,1010930),
               (1010507,1010933,1,1010933);
        ");
        $this->execute("
        insert into db_sysindices
        values (1008504,'bncceducacaoinfantil_codigo_in',1010502,'1'),
               (1008505,'bncceducacaoinfantil_disciplina_in',1010502,'0'),
               (1008506,'bnccensinofundamental_codigo_in',1010503,'0'),
               (1008507,'bnccensinofundamental_unidade_tematica_in',1010503,'0'),
               (1008508,'bnccensinofundamental_objeto_conhecimento_in',1010503,'0'),
               (1008509,'bnccensinofundamental_disciplina_etapa_in',1010503,'0'),
               (1008510,'bnccdisciplinas_nome_in',1010504,'0'),
               (1008511,'bnccdisciplinas_ensino_in',1010504,'0'),
               (1008512,'bnccetapas_ensino_in',1010505,'0'),
               (1008513,'bnccetapas_ensino_etapa_in',1010505,'0'),
               (1008514,'caddisciplinabnccdisciplinas__caddisciplina_bnccdisciplina_in',1010506,'0'),
               (1008515,'seriebnccetapas_serie_bnccetapa_in',1010507,'0');
        ");
        $this->execute("
        insert into db_syscadind
        values (1008504,1010913,1),
               (1008505,1010911,1),
               (1008506,1010918,1),
               (1008507,1010919,1),
               (1008508,1010920,1),
               (1008509,1010916,1),
               (1008509,1010917,2),
               (1008510,1010923,1),
               (1008511,1010926,1),
               (1008512,1010929,1),
               (1008513,1010928,1),
               (1008513,1010929,2),
               (1008514,1010931,1),
               (1008514,1010932,2),
               (1008515,1010935,1),
               (1008515,1010934,2);
        ");

        $this->execute("
        insert into db_syssequencia
        values (1000870, 'bncceducacaoinfantil_ed147_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000871, 'bnccensinofundamental_ed148_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000872, 'bnccdisciplinas_ed149_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000873, 'bnccetapas_ed152_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000874, 'caddisciplinabnccdisciplinas_ed153_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000875, 'seriebnccetapas_ed154_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        ");
        $this->execute("
        update db_sysarqcamp set codsequencia = 1000870 where codarq = 1010502 and codcam = 1010910;
        update db_sysarqcamp set codsequencia = 1000871 where codarq = 1010503 and codcam = 1010915;
        update db_sysarqcamp set codsequencia = 1000872 where codarq = 1010504 and codcam = 1010922;
        update db_sysarqcamp set codsequencia = 1000873 where codarq = 1010505 and codcam = 1010927;
        update db_sysarqcamp set codsequencia = 1000874 where codarq = 1010506 and codcam = 1010930;
        update db_sysarqcamp set codsequencia = 1000875 where codarq = 1010507 and codcam = 1010933;
        ");
        $this->execute("
        insert into db_sysforkey values(1010506,1010931,1,2017,0);
        insert into db_sysforkey values(1010506,1010932,1,1010504,0);
        insert into db_sysforkey values(1010507,1010935,1,1010047,0);
        insert into db_sysforkey values(1010507,1010934,1,1010505,0);
        ");
    }

    public function downDicionario()
    {
        $this->execute("
        delete from db_syscadind where codind in (1008504, 1008505, 1008506, 1008507, 1008508, 1008509, 1008510, 1008511, 1008512, 1008513, 1008514, 1008515);
        delete from db_sysindices where codind in (1008504, 1008505, 1008506, 1008507, 1008508, 1008509, 1008510, 1008511, 1008512, 1008513, 1008514, 1008515);
        delete from db_sysprikey where codarq in (1010502, 1010503, 1010504, 1010505, 1010506, 1010507);
        delete from db_sysforkey where codarq in (1010506, 1010507);
        delete from db_syssequencia where codsequencia in (1000870, 1000871, 1000872, 1000873, 1000874, 1000875);
        delete from db_sysarqcamp where codarq in (1010502,1010503,1010504,1010505,1010506,1010507);
        delete from db_syscampo where codcam in (1010910, 1010911, 1010912, 1010913, 1010914, 1010915, 1010916, 1010917, 1010918, 1010919, 1010920, 1010921, 1010922, 1010923, 1010924, 1010925, 1010926, 1010927, 1010928, 1010929, 1010930, 1010931, 1010932, 1010933, 1010934, 1010935);
        delete from db_sysarqmod where codarq in (1010502,1010503,1010504,1010505,1010506,1010507);
        delete from db_sysarquivo where codarq in (1010502,1010503,1010504,1010505,1010506,1010507);
        ");
    }

    private function dowEstrutura()
    {
        $this->execute("
        DROP TABLE IF EXISTS bnccdisciplinas CASCADE;
        DROP TABLE IF EXISTS bncceducacaoinfantil CASCADE;
        DROP TABLE IF EXISTS bnccensinofundamental CASCADE;
        DROP TABLE IF EXISTS bnccetapas CASCADE;
        DROP TABLE IF EXISTS caddisciplinabnccdisciplinas CASCADE;
        DROP TABLE IF EXISTS seriebnccetapas CASCADE;
        ");

        $this->execute("
        DROP SEQUENCE IF EXISTS bnccdisciplinas_ed149_sequencial_seq;
        DROP SEQUENCE IF EXISTS bncceducacaoinfantil_ed147_sequencial_seq;
        DROP SEQUENCE IF EXISTS bnccensinofundamental_ed148_sequencial_seq;
        DROP SEQUENCE IF EXISTS bnccetapas_ed152_sequencial_seq;
        DROP SEQUENCE IF EXISTS caddisciplinabnccdisciplinas_ed153_sequencial_seq;
        DROP SEQUENCE IF EXISTS seriebnccetapas_ed154_sequencial_seq;
        ");
    }

    private function criaEstrutura()
    {
        $this->execute("
        CREATE SEQUENCE bnccdisciplinas_ed149_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        CREATE SEQUENCE bncceducacaoinfantil_ed147_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        CREATE SEQUENCE bnccensinofundamental_ed148_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        CREATE SEQUENCE bnccetapas_ed152_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        CREATE SEQUENCE caddisciplinabnccdisciplinas_ed153_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        CREATE SEQUENCE seriebnccetapas_ed154_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute("
        CREATE TABLE escola.bnccdisciplinas (
            ed149_sequencial int4 not null,
            ed149_nome varchar(100) not null,
            ed149_sigla varchar(3) not null,
            ed149_area_conhecimento varchar(100),
            ed149_ensino varchar(2),
            CONSTRAINT bnccdisciplinas_sequ_pk PRIMARY KEY (ed149_sequencial)
        );

        CREATE TABLE escola.bncceducacaoinfantil(
            ed147_sequencial  int4 not null,
            ed147_disciplina varchar(100) not null,
            ed147_faixa_etaria varchar(100) not null,
            ed147_codigo varchar(8) not null,
            ed147_habilidade varchar(255) not null,
            CONSTRAINT bncceducacaoinfantil_sequ_pk PRIMARY KEY (ed147_sequencial)
        );

        CREATE TABLE escola.bnccensinofundamental(
            ed148_sequencial int4 not null,
            ed148_disciplina varchar(100) not null,
            ed148_etapa varchar(100) not null,
            ed148_codigo varchar(8) not null,
            ed148_unidade_tematica varchar(100) not null,
            ed148_objeto_conhecimento text not null,
            ed148_habilidade text not null,
            CONSTRAINT bnccensinofundamental_sequ_pk PRIMARY KEY (ed148_sequencial)
        );

        CREATE TABLE escola.bnccetapas(
            ed152_sequencial int4 not null,
            ed152_etapa varchar(10) not null,
            ed152_ensino varchar(2) not null,
            CONSTRAINT bnccetapas_sequ_pk PRIMARY KEY (ed152_sequencial)
        );

        CREATE TABLE escola.caddisciplinabnccdisciplinas(
            ed153_sequencial int4 not null,
            ed153_caddisciplina int4 not null,
            ed153_bnccdisciplina int4 not null,
            CONSTRAINT caddisciplinabnccdisciplinas_sequ_pk PRIMARY KEY (ed153_sequencial)
        );

        CREATE TABLE escola.seriebnccetapas(
            ed154_sequencial int4 not null,
            ed154_bnccetapa int4 not null,
            ed154_serie int4 not null,
            CONSTRAINT seriebnccetapas_sequ_pk PRIMARY KEY (ed154_sequencial)
        );
        ");

        $this->execute("
        ALTER TABLE escola.caddisciplinabnccdisciplinas ADD CONSTRAINT caddisciplinabnccdisciplinas_bnccdisciplina_fk FOREIGN KEY (ed153_bnccdisciplina) REFERENCES bnccdisciplinas;
        ALTER TABLE escola.caddisciplinabnccdisciplinas ADD CONSTRAINT caddisciplinabnccdisciplinas_caddisciplina_fk FOREIGN KEY (ed153_caddisciplina) REFERENCES caddisciplina;
        ALTER TABLE escola.seriebnccetapas ADD CONSTRAINT seriebnccetapas_bnccetapa_fk FOREIGN KEY (ed154_bnccetapa) REFERENCES bnccetapas;
        ALTER TABLE escola.seriebnccetapas ADD CONSTRAINT seriebnccetapas_serie_fk FOREIGN KEY (ed154_serie) REFERENCES serie;
        ");

        $this->execute("
        CREATE INDEX bnccdisciplinas_nome_in ON escola.bnccdisciplinas(ed149_nome);
        CREATE INDEX bnccdisciplinas_ensino_in ON escola.bnccdisciplinas(ed149_ensino);
        CREATE UNIQUE INDEX bncceducacaoinfantil_codigo_in ON escola.bncceducacaoinfantil(ed147_codigo);
        CREATE INDEX bncceducacaoinfantil_disciplina_in ON escola.bncceducacaoinfantil(ed147_disciplina);
        CREATE INDEX bnccensinofundamental_codigo_in ON escola.bnccensinofundamental(ed148_codigo);
        CREATE INDEX bnccensinofundamental_unidade_tematica_in ON escola.bnccensinofundamental(ed148_unidade_tematica);
        CREATE INDEX bnccensinofundamental_objeto_conhecimento_in ON escola.bnccensinofundamental(ed148_objeto_conhecimento);
        CREATE INDEX bnccensinofundamental_disciplina_etapa_in ON escola.bnccensinofundamental(ed148_disciplina,ed148_etapa);
        CREATE INDEX bnccetapas_ensino_in ON escola.bnccetapas(ed152_ensino);
        CREATE INDEX bnccetapas_ensino_etapa_in ON escola.bnccetapas(ed152_etapa,ed152_ensino);
        CREATE INDEX caddisciplinabnccdisciplinas__caddisciplina_bnccdisciplina_in ON escola.caddisciplinabnccdisciplinas(ed153_caddisciplina,ed153_bnccdisciplina);
        CREATE INDEX seriebnccetapas_serie_bnccetapa_in ON escola.seriebnccetapas(ed154_serie,ed154_bnccetapa);
        ");
    }
}
