<?php

use Classes\PostgresMigration;

class M18711CriaTabelaVacinas extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->execute(<<<SQL
            CREATE TABLE escola.vacinas_escola (
                ed178_codigo SERIAL PRIMARY KEY,
                ed178_descricao VARCHAR,
                ed178_sigla VARCHAR
            );

            CREATE TABLE escola.doses (
                ed180_codigo SERIAL PRIMARY KEY,
                ed180_descricao VARCHAR
            );

            CREATE TABLE escola.vacinas_doses (
                ed179_codigo SERIAL PRIMARY KEY,
                ed179_vacina INTEGER,
                ed179_dose INTEGER,
                FOREIGN KEY (ed179_vacina) REFERENCES escola.vacinas_escola (ed178_codigo),
                FOREIGN KEY (ed179_dose) REFERENCES escola.doses (ed180_codigo)
            );

            CREATE TABLE escola.rechumano_vacinacao (
                ed181_codigo SERIAL PRIMARY KEY,
                ed181_rechumano INTEGER NOT NULL,
                ed181_data DATE NOT NULL,
                ed181_vacina INTEGER NOT NULL,
                ed181_dose INTEGER NOT NULL,
                FOREIGN KEY (ed181_vacina) REFERENCES escola.vacinas_escola (ed178_codigo),
                FOREIGN KEY (ed181_dose) REFERENCES escola.doses (ed180_codigo),
                FOREIGN KEY (ed181_rechumano) REFERENCES escola.rechumano (ed20_i_codigo)
            );

            INSERT INTO
                escola.vacinas_escola
            VALUES
                (1, 'Hepatite B', 'HB'),
                (2, 'Febre Amarela', 'FA'),
                (3, 'Tríplice Viral  - Sarampo, Caxumba, Rubéola', 'SCR'),
                (4, 'Dupla Adulto - Difeteria e Tétano', 'dT'),
                (5, 'Dengue 1, 2, 3 e 4', 'Dengue'),
                (6, 'Covid-19 - Covishield - Oxford/AstraZeneca', 'COV19 Oxford - AstraZeneca'),
                (7, 'Covid-19 - Coronavac - Sinovac/Butantan', 'COV19 Coronavac - Sinovac/Butantan'),
                (8, 'Covid-19 - BioNTech/Fosun Pharma/Pfizer', 'COV19 Biontech - Pfizer'),
                (9, 'Covid-19 - Janssen-Cilag', 'COV19 Janssen - Cilag'),
                (10, 'Influenza Trivalente', 'FLU3V');

                SELECT setval('vacinas_escola_ed178_codigo_seq', 10);

            INSERT INTO
                escola.doses
            VALUES
                (1, 'Dose'),
                (2, '1ª Dose'),
                (3, '2ª Dose'),
                (4, '3ª Dose'),
                (5, 'Dose Única'),
                (6, 'Reforço'),
                (7, 'Revacinação');

            SELECT setval('doses_ed180_codigo_seq', 7);

            INSERT INTO
                escola.vacinas_doses
            VALUES
                (1, 1, 2),
                (2, 1, 3),
                (3, 1, 4),
                (4, 1, 7),
                (5, 2, 2),
                (6, 2, 5),
                (7, 2, 6),
                (8, 3, 2),
                (9, 3, 3),
                (10, 4, 2),
                (11, 4, 3),
                (12, 4, 4),
                (13, 4, 6),
                (14, 5, 2),
                (15, 5, 3),
                (16, 5, 4),
                (17, 6, 2),
                (18, 6, 3),
                (19, 7, 2),
                (20, 7, 3),
                (21, 8, 2),
                (22, 8, 3),
                (23, 9, 1),
                (24, 10, 5);

                SELECT setval('vacinas_doses_ed179_codigo_seq', 24);
SQL
        );
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->execute(<<<SQL
            DROP TABLE escola.rechumano_vacinacao;
            DROP TABLE escola.vacinas_doses;
            DROP TABLE escola.doses;
            DROP TABLE escola.vacinas_escola;
SQL
        );
    }

    private function dicionarioUp()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010824, 'vacinas_escola', 'Guarda a lista de vacinas que são usadas no módulo escola', 'ed178', '2021-08-30', 'Vacinas', 0, 't', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010825, 'doses', 'Tipos de doses de cada vacina da tabela escola', 'ed180', '2021-08-30', 'Doses', 0, 't', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010826, 'vacinas_doses', 'Guarda o vinculo de doses com vacinas, qual dose cada vacina pode ter.', 'ed179', '2021-08-30', 'Doses da Vacina', 0, 't', 'f', 'f', 'f' );
            insert into db_sysarquivo values (1010827, 'rechumano_vacinacao', 'Guarda os registros de vacinas do profissional', 'ed181', '2021-08-30', 'Vacinas do Profissional', 0, 't', 'f', 'f', 'f' );

            insert into db_sysarqmod
                values (1008004,1010824),
                        (1008004,1010825),
                        (1008004,1010826),
                        (1008004,1010827);

            insert into db_syscampo
                values (1013410,'ed178_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
                        (1013411,'ed178_descricao','varchar(255)','Nome da Vacina','', 'Descrição',255,'f','f','f',0,'text','Descrição'),
                        (1013412,'ed178_sigla','varchar(255)','Nome abreviado da vacina (Sigla)','', 'Sigla',255,'f','f','f',0,'text','Sigla'),
                        (1013413,'ed180_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
                        (1013414,'ed180_descricao','varchar(255)','Descrição da dose','', 'Descrição',255,'f','f','f',0,'text','Descrição'),
                        (1013415,'ed179_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
                        (1013416,'ed179_vacina','int4','Faz referência com a tabela vacinas_escola','0', 'Vacina',10,'f','f','f',1,'text','Vacina'),
                        (1013417,'ed179_dose','int4','Guarda a referencia dose com a vacina','0', 'Dose',10,'f','f','f',1,'text','Dose'),
                        (1013418,'ed181_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
                        (1013419,'ed181_rechumano','int4','Guarda o vinculo com rechumano, profissional que feza vacina','0', 'Profissional',10,'f','f','f',1,'text','Profissional'),
                        (1013420,'ed181_data','date','Data da Vacinação','null', 'Data da Vacinação',10,'f','f','f',1,'text','Data da Vacinação'),
                        (1013421,'ed181_vacina','int4','Guarda a referencia com a Vacina (vacinas_escola)','0', 'Vacina',10,'f','f','f',1,'text','Vacina'),
                        (1013422,'ed181_dose','int4','Guarda a dose da vacina (tabela doses)','0', 'Dose',10,'f','f','f',1,'text','Dose');

                insert into db_sysarqcamp
                    values (1010824,1013410,1,0),
                            (1010824,1013411,2,0),
                            (1010824,1013412,3,0),
                            (1010825,1013413,1,0),
                            (1010825,1013414,2,0),
                            (1010826,1013415,1,0),
                            (1010826,1013416,2,0),
                            (1010826,1013417,3,0),
                            (1010827,1013418,1,0),
                            (1010827,1013419,2,0),
                            (1010827,1013420,3,0),
                            (1010827,1013421,4,0),
                            (1010827,1013422,5,0);

                insert into db_sysprikey (codarq,codcam,sequen,camiden)
                    values (1010824,1013410,1,1013410),
                           (1010825,1013413,1,1013413),
                           (1010826,1013415,1,1013415),
                           (1010827,1013418,1,1013418);

                insert into db_sysforkey
                    values (1010826,1013416,1,1010824,0),
                            (1010826,1013417,1,1010825,0),
                            (1010827,1013421,1,1010824,0),
                            (1010827,1013422,1,1010825,0),
                            (1010827,1013419,1,1010087,0);
SQL
        );
    }

    private function dicionarioDown()
    {
        $this->execute(<<<SQL
        delete from db_sysforkey where codarq in (1010826, 1010827);
        delete from db_sysprikey where codarq in (1010824, 1010825, 1010826, 1010827);
        delete from db_sysarqcamp where codarq in (1010824, 1010825, 1010826, 1010827);
        delete from db_syscampo
            where codcam in (1013410, 1013411, 1013412, 1013413, 1013414, 1013415, 1013416,
                             1013417, 1013418, 1013419, 1013420, 1013421, 1013422);
        delete from db_sysarqmod where codarq in (1010824, 1010825, 1010826, 1010827);
        delete from db_sysarquivo where codarq in (1010824, 1010825, 1010826, 1010827);
SQL
        );
    }
}
