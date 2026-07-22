<?php

use Classes\PostgresMigration;

class M15735AnexoAliquotaSimplesNacional extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(
            <<<SQL
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- CRIAÇÃO DE TABELAS
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            insert into db_sysarquivo values (1010550, 'issgscadanexos', 'cadastro de descrição de anexos iss', 'q157', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010550);
            insert into db_sysarquivo values (1010551, 'issgscadimpostos', 'cadastro descrição de impostos', 'q160', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010551);
            insert into db_sysarquivo values (1010552, 'issgsanexoscadfaixas', 'cadastro de faixas de valores de anexos iss', 'q161', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010552);
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
            insert into db_sysarquivo values (1010553, 'issgsanexos', 'tabela de relaï¿½ï¿½o entre issgruposervico e issgscadanexos', 'q162', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010553);
            insert into db_sysarquivo values (1010554, 'issgsanexosfaixas', 'tabela de relaï¿½ï¿½o que liga issgscadanexos na issgsanexosfaixasimpostosfaixas', 'q163', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010554);
            insert into db_sysarquivo values (1010555, 'issgsanexosfaixasimpostosfaixas', 'tabela que relaciona a issgsanexoscadfaixas, issgsanexosfaixas e issgsanexosfaixasimpostosvalores', 'q164', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010555);
            insert into db_sysarquivo values (1010556, 'issgsanexosfaixasimpostosvalores', 'tabela que relaciona issgsanexosfaixasimpostosfaixas e issgscadimpostos', 'q165', '2020-04-20', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010556);
            insert into db_sysarquivo values (1010567, 'issgsanexosfaixasimpostosfaixaslimites', 'Tabela de limites de regras individuais em faixas e impostos', 'q166', '2020-05-22', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010567);

            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- CADASTRO DE CAMPOS
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- CADASTRO issgscadanexos
            insert into db_syscampo values(1011198,'q157_sequencial','int4','sequencial da tabela issgscadanexos','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011199,'q157_codigo','varchar(255)','codigo de definiï¿½ï¿½o para issgscadanexos','', 'codigo',255,'f','t','f',0,'text','codigo');
            insert into db_syscampo values(1011200,'q157_descricao','varchar(255)','descricao dos anexos','', 'descricao dos anexos',255,'f','t','f',0,'text','descricao dos anexos');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010550,1011198,1,0);
            insert into db_sysarqcamp values(1010550,1011199,2,0);
            insert into db_sysarqcamp values(1010550,1011200,3,0);

            -- CADASTRO issgscadimpostos
            insert into db_syscampo values(1011201,'q160_sequencial','int4','sequencial da tabela issgscadimpostos','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011202,'q160_descricao','varchar(255)','descricao dos impostos iss','', 'descricao dos impostos',255,'f','t','f',0,'text','descricao dos impostos');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010551,1011201,1,0);
            insert into db_sysarqcamp values(1010551,1011202,2,0);

            -- CADASTRO issgsanexoscadfaixas
            insert into db_syscampo values(1011203,'q161_sequencial','int4','sequencial da tabela issgsanexoscadfaixas','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011204,'q161_descricao','varchar(255)','descricao das faixas de anexos','', 'descricao das faixas de anexos',255,'f','t','f',0,'text','descricao das faixas de anexos');
            insert into db_syscampo values(1011205,'q161_valorinicial','float4','valor inicial da faixa','0', 'valor inicial da faixa',10,'f','f','f',4,'text','valor inicial da faixa');
            insert into db_syscampo values(1011206,'q161_valorfinal','float4','valor final da faixa','0', 'valor final da faixa',10,'f','f','f',4,'text','valor final da faixa');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010552,1011203,1,0);
            insert into db_sysarqcamp values(1010552,1011204,2,0);
            insert into db_sysarqcamp values(1010552,1011205,3,0);
            insert into db_sysarqcamp values(1010552,1011206,4,0);

            -- CADASTRO issgsanexos
            insert into db_syscampo values(1011207,'q162_sequencial','int4','sequencial da tabela issgsanexos','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011208,'q162_issgruposervico','int4','chave unica issgruposervico','0', 'chave unica issgruposervico',10,'f','f','f',1,'text','chave unica issgruposervico');
            insert into db_syscampo values(1011209,'q162_issgscadanexos','int4','campos sequencial da tabela issgscadanexos','0', 'sequencial da issgscadanexos',10,'f','f','f',1,'text','sequencial da issgscadanexos');
            insert into db_syscampo values(1011210,'q162_data_fim','date','data limite do anexo','null', 'data limite',10,'f','f','f',3,'text','data limite');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010553,1011207,1,0);
            insert into db_sysarqcamp values(1010553,1011208,2,0);
            insert into db_sysarqcamp values(1010553,1011209,3,0);
            insert into db_sysarqcamp values(1010553,1011210,4,0);

            -- CADASTRO issgsanexosfaixas
            insert into db_syscampo values(1011211,'q163_sequencial','int4','campo sequencial','0', 'campo sequencial',10,'f','f','f',1,'text','sequencial issgsanexosfaixas');
            insert into db_syscampo values(1011218,'q163_issgscadanexos','int4','relaciona issgscadanexos','0', 'relaciona issgscadanexos',10,'f','f','f',1,'text','relaciona issgscadanexos');
            insert into db_syscampo values(1011219,'q163_competencia_inicial','date','data inicio competï¿½ncia','null', 'data inicio competï¿½ncia',10,'f','f','f',1,'text','data inicio competï¿½ncia');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010554,1011211,1,0);
            insert into db_sysarqcamp values(1010554,1011218,2,0);
            insert into db_sysarqcamp values(1010554,1011219,3,0);

            -- CADASTRO issgsanexosfaixasimpostosfaixas
            insert into db_syscampo values(1011220,'q164_sequencial','int4','sequencial','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011221,'q164_issgsanexosfaixas','int4','relaciona issgsanexosfaixas','0', 'relaciona issgsanexosfaixas',10,'f','f','f',1,'text','relaciona issgsanexosfaixas');
            insert into db_syscampo values(1011222,'q164_aliquotatotal','float4','valor total aliquota','0', 'valor total aliquota',10,'f','f','f',4,'text','valor total aliquota');
            insert into db_syscampo values(1011223,'q164_valordeducao','float4','valor da deduï¿½ï¿½o','0', 'valor da deduï¿½ï¿½o',10,'f','f','f',4,'text','valor da deduï¿½ï¿½o');
            insert into db_syscampo values(1011224,'q164_issgsanexoscadfaixas','int4','relaciona issgsanexoscadfaixas','0', 'relaciona issgsanexoscadfaixas',10,'f','f','f',1,'text','relaciona issgsanexoscadfaixas');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010555,1011220,1,0);
            insert into db_sysarqcamp values(1010555,1011221,2,0);
            insert into db_sysarqcamp values(1010555,1011222,3,0);
            insert into db_sysarqcamp values(1010555,1011223,4,0);
            insert into db_sysarqcamp values(1010555,1011224,5,0);

            -- CADASTRO issgsanexosfaixasimpostosfaixaslimites
            insert into db_syscampo values(1011306,'q166_sequencial','int4','Sequencial issgsanexosfaixasimpostosfaixaslimites','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011307,'q166_issgsanexosfaixasimpostosfaixas','int4','issgsanexosfaixasimpostosfaixas','0', 'issgsanexosfaixasimpostosfaixas',10,'f','f','f',1,'text','issgsanexosfaixasimpostosfaixas');
            insert into db_syscampo values(1011308,'q166_limite_inicial','float4','Limite Inicial','0', 'Limite Inicial',50,'f','f','f',4,'text','Limite Inicial');
            insert into db_syscampo values(1011309,'q166_limite_final','float4','Limite Final','0', 'Limite Final',50,'f','f','f',4,'text','Limite Final');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010567,1011306,1,0);
            insert into db_sysarqcamp values(1010567,1011307,2,0);
            insert into db_sysarqcamp values(1010567,1011308,3,0);
            insert into db_sysarqcamp values(1010567,1011309,4,0);

            -- CADASTRO issgsanexosfaixasimpostosvalores
            insert into db_syscampo values(1011225,'q165_sequencial','int4','sequencial issgsanexosfaixasimpostosvalores','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011232,'q165_issgsanexosfaixasimpostoslimites','int4','issgsanexosfaixasimpostosfaixaslimites','0', 'issgsanexosfaixasimpostosfaixaslimites',10,'t','f','f',1,'text','issgsanexosfaixasimpostosfaixaslimites');
            insert into db_syscampo values(1011233,'q165_issgscadimpostos','int4','relaciona issgscadimpostos','0', 'relaciona issgscadimpostos',10,'f','f','f',1,'text','relaciona issgscadimpostos');
            insert into db_syscampo values(1011234,'q165_aliquotaimposto','float4','aliquota do imposto','0', 'aliquota do imposto',10,'f','f','f',4,'text','aliquota do imposto');
            -- ATRIBUIÇÃO DOS CAMPOS A TABELA
            insert into db_sysarqcamp values(1010556,1011225,1,0);
            insert into db_sysarqcamp values(1010556,1011232,2,0);
            insert into db_sysarqcamp values(1010556,1011233,3,0);
            insert into db_sysarqcamp values(1010556,1011234,4,0);

            -- -- -- -- -- -- -- -- -- -- -- -- -- 
            -- -- -- ORGANIZAÇÃO DE CAMPOS -- -- --
            -- -- -- -- -- -- -- -- -- -- -- -- -- 

            -- issgscadanexos
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010550,1011198,1,1011198);

            -- issgscadimpostos
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010551,1011201,1,1011201);

            -- issgsanexoscadfaixas
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010552,1011203,1,1011203);

            -- issgsanexos
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010553,1011207,1,1011207);
            insert into db_sysforkey values(1010553,1011208,1,3248,0);
            insert into db_sysforkey values(1010553,1011209,1,1010550,0);

            -- issgsanexosfaixas
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010554,1011211,1,1011211);
            insert into db_sysforkey values(1010554,1011218,1,1010550,0);

            -- issgsanexosfaixasimpostosfaixas
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010555,1011220,1,1011220);
            insert into db_sysforkey values(1010555,1011221,1,1010554,0);
            insert into db_sysforkey values(1010555,1011224,1,1010552,0);

            -- issgsanexosfaixasimpostosvalores
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010556,1011225,1,1011225);
            insert into db_sysforkey values(1010556,1011232,1,1010555,0);
            insert into db_sysforkey values(1010556,1011233,1,1010551,0);

            -- issgsanexosfaixasimpostosfaixaslimites
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010567,1011306,1,1011306);
            insert into db_sysforkey values(1010567,1011307,1,1010555,0);

            -- CRIAÇÃO DE SEQUENCE
            insert into db_syssequencia values(1000901, 'issgscadanexos_q157_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000901 where codarq = 1010550 and codcam = 1011198;

            insert into db_syssequencia values(1000902, 'issgscadimpostos_q160_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000902 where codarq = 1010551 and codcam = 1011201;

            insert into db_syssequencia values(1000903, 'issgsanexoscadfaixas_q161_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000903 where codarq = 1010552 and codcam = 1011203;

            insert into db_syssequencia values(1000904, 'issgsanexos_q162_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000904 where codarq = 1010553 and codcam = 1011207;

            insert into db_syssequencia values(1000905, 'issgsanexosfaixas_q163_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000905 where codarq = 1010554 and codcam = 1011211;

            insert into db_syssequencia values(1000906, 'issgsanexosfaixasimpostosfaixas_q164_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000906 where codarq = 1010555 and codcam = 1011220;

            insert into db_syssequencia values(1000907, 'issgsanexosfaixasimpostosvalores_q165_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000907 where codarq = 1010556 and codcam = 1011225;

            insert into db_syssequencia values(1000916, 'issgsanexosfaixasimpostosfaixaslimites_q166_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000916 where codarq = 1010567 and codcam = 1011306;

SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(
            <<<SQL
            CREATE TABLE issqn.issgscadanexos (
                "q157_sequencial" serial NOT NULL,
                "q157_codigo" varchar(255) NOT NULL,
                "q157_descricao" varchar(255) NOT NULL,
                CONSTRAINT "issgscadanexos_pk" PRIMARY KEY ("q157_sequencial")
            );

            CREATE TABLE issqn.issgscadimpostos (
                "q160_sequencial" serial NOT NULL,
                "q160_descricao" varchar(255) NOT NULL,
                CONSTRAINT "issgscadimpostos_pk" PRIMARY KEY ("q160_sequencial")
            );

            CREATE TABLE issqn.issgsanexoscadfaixas (
                "q161_sequencial" serial NOT NULL,
                "q161_descricao" varchar(255) NOT NULL,
                "q161_valorinicial" FLOAT NOT NULL,
                "q161_valorfinal" FLOAT NOT NULL,
                CONSTRAINT "issgsanexoscadfaixas_pk" PRIMARY KEY ("q161_sequencial")
            );

            CREATE TABLE issqn.issgsanexos (
                "q162_sequencial" serial NOT NULL,
                "q162_issgruposervico" integer NOT NULL,
                "q162_issgscadanexos" integer NOT NULL,
                "q162_data_fim" DATE,
                CONSTRAINT "issgsanexos_pk" PRIMARY KEY ("q162_sequencial"),
                CONSTRAINT "issgsanexos_fk0" FOREIGN KEY ("q162_issgruposervico") REFERENCES "issgruposervico"("q126_sequencial"),
                CONSTRAINT "issgsanexos_fk1" FOREIGN KEY ("q162_issgscadanexos") REFERENCES "issgscadanexos"("q157_sequencial"),
                CONSTRAINT "issgsanexos_unique" UNIQUE ("q162_issgruposervico", "q162_issgscadanexos", "q162_data_fim")
            );

            CREATE TABLE issqn.issgsanexosfaixas (
                "q163_sequencial" serial NOT NULL,
                "q163_issgscadanexos" integer NOT NULL,
                "q163_competencia_inicial" DATE NOT NULL,
                CONSTRAINT "issgsanexosfaixas_pk" PRIMARY KEY ("q163_sequencial"),
                CONSTRAINT "issgsanexosfaixas_fk0" FOREIGN KEY ("q163_issgscadanexos") REFERENCES "issgscadanexos"("q157_sequencial")
            );

            CREATE TABLE issqn.issgsanexosfaixasimpostosfaixas (
                "q164_sequencial" serial NOT NULL,
                "q164_issgsanexosfaixas" integer NOT NULL,
                "q164_aliquotatotal" FLOAT NOT NULL,
                "q164_valordeducao" FLOAT NOT NULL,
                "q164_issgsanexoscadfaixas" integer NOT NULL,
                CONSTRAINT "issgsanexosfaixasimpostosfaixas_pk" PRIMARY KEY ("q164_sequencial"),
                CONSTRAINT "issgsanexosfaixasimpostosfaixas_fk0" FOREIGN KEY ("q164_issgsanexosfaixas") REFERENCES "issgsanexosfaixas"("q163_sequencial"),
                CONSTRAINT "issgsanexosfaixasimpostosfaixas_fk1" FOREIGN KEY ("q164_issgsanexoscadfaixas") REFERENCES "issgsanexoscadfaixas"("q161_sequencial")
            );

            CREATE TABLE issqn.issgsanexosfaixasimpostosfaixaslimites (
                "q166_sequencial" serial NOT NULL,
                "q166_issgsanexosfaixasimpostosfaixas" integer NOT NULL,
                "q166_limite_inicial" FLOAT NOT NULL,
                "q166_limite_final" FLOAT NOT NULL,
                CONSTRAINT "issgsanexosfaixasimpostosfaixaslimites_pk" PRIMARY KEY ("q166_sequencial"),
                CONSTRAINT "issgsanexosfaixasimpostosfaixaslimites_fk0" FOREIGN KEY ("q166_issgsanexosfaixasimpostosfaixas") REFERENCES "issgsanexosfaixasimpostosfaixas"("q164_sequencial")
            );

            CREATE TABLE issqn.issgsanexosfaixasimpostosvalores (
                "q165_sequencial" serial NOT NULL,
                "q165_issgsanexosfaixasimpostoslimites" integer NOT NULL,
                "q165_issgscadimpostos" integer NOT NULL,
                "q165_aliquotaimposto" FLOAT NULL,
                CONSTRAINT "issgsanexosfaixasimpostosvalores_pk" PRIMARY KEY ("q165_sequencial"),
                CONSTRAINT "issgsanexosfaixasimpostosvalores_fk0" FOREIGN KEY ("q165_issgsanexosfaixasimpostoslimites") REFERENCES "issgsanexosfaixasimpostosfaixaslimites"("q166_sequencial"),
                CONSTRAINT "issgsanexosfaixasimpostosvalores_fk1" FOREIGN KEY ("q165_issgscadimpostos") REFERENCES "issgscadimpostos"("q160_sequencial")
            );

            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
            -- POPULANDO TABELAS
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --  

            -- Descrição Anexos
            INSERT INTO issqn.issgscadanexos (q157_codigo, q157_descricao) VALUES ('3','Anexo III');
            INSERT INTO issqn.issgscadanexos (q157_codigo, q157_descricao) VALUES ('4','Anexo IV');
            INSERT INTO issqn.issgscadanexos (q157_codigo, q157_descricao) VALUES ('5','Anexo V');

            -- Descrição Impostos
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('IRPJ');
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('CSLL');
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('Cofins');
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('PIS/Pasep');
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('CPP');
            INSERT INTO issqn.issgscadimpostos (q160_descricao) VALUES ('ISS');

            -- Valores Faixas
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('1ï¿½ Faixa', '0', '180000.00');
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('2ï¿½ Faixa', '180000.01', '360000.00');
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('3ï¿½ Faixa', '360000.01', '720000.00');
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('4ï¿½ Faixa', '720000.01', '1800000.00');
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('5ï¿½ Faixa', '1800000.01', '3600000.00');
            INSERT INTO issqn.issgsanexoscadfaixas (q161_descricao, q161_valorinicial, q161_valorfinal) VALUES ('6ï¿½ Faixa', '3600000.01', '4800000.00');

            insert into issgsanexosfaixas ( q163_issgscadanexos, q163_competencia_inicial ) values (1, '2020-01-01');
            insert into issgsanexosfaixas ( q163_issgscadanexos, q163_competencia_inicial ) values (2, '2020-01-01');
            insert into issgsanexosfaixas ( q163_issgscadanexos, q163_competencia_inicial ) values (3, '2020-01-01');

            -- ANEXO III
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 6, 0, 1);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 11.2, 9360, 2);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 13.5, 17640, 3);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 16, 35640, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 21, 125640, 5);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (1, 33, 648000, 6);

            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (1, 0, 100);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (2, 0, 100);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (3, 0, 100);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (4, 0, 100);

            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (5, 0, 14.92537); -- 5a. faixa -- sequencial 5
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (5, 14.92538, 100); -- 5a. faixa -- sequencial 6
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (6, 0, 100); -- sequencial 7

            -- Faixa 1
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 1, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 2, 3.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 3, 12.82);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 4, 2.78);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 5, 43.40);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (1, 6, 33.50);

            -- Faixa 2
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 1, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 2, 3.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 3, 14.05);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 4, 3.05);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 5, 43.40);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (2, 6, 32);

            -- Faixa 3
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 1, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 2, 3.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 3, 13.64);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 4, 2.96);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 5, 43.40);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (3, 6, 32.50);

            -- Faixa 4
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 1, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 2, 3.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 3, 13.64);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 4, 2.96);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 5, 43.40);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (4, 6, 32.50);

            -- Faixa 5
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 1, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 2, 3.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 3, 12.82);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 4, 2.78);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 5, 43.40);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (5, 6, 33.50);

            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 1, 6.02);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 2, 5.26);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 3, 19.28);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 4, 4.18);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 5, 65.26);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (6, 6, 5);

            -- Faixa 6
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 1, 35);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 2, 15);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 3, 16.03);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 4, 3.47);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 5, 30.5);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (7, 6, 0);

            -- ANEXO IV
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 4.5, 0, 1); -- sequencial 7
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 0, 8100, 2);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 10.20, 12420, 3);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 14, 39780, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 22, 183780, 5);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (2, 33, 828000, 6); -- sequencial 12

            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (7, 0, 100 ); -- sequencial 8
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (8, 0, 100 );
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (9, 0, 100 );
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (10, 0, 100 );
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (11, 0, 12.5 ); -- 5a. faixa -- sequencial 12
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (11, 12.51, 100 ); -- 5a. faixa -- sequencial 13
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (12, 0, 100 ); -- sequencial 14

            -- Faixa 1
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 1, 18.8);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 2, 15.20);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 3, 17.67);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 4, 3.83);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (8, 6, 44.50);

            -- Faixa 2
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 1, 19.80);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 2, 15.20);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 3, 20.55);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 4, 4.45);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (9, 6, 40);

            -- Faixa 3
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 1, 20.80);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 2, 15.20);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 3, 19.73);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 4, 4.27);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (10, 6, 40);

            -- Faixa 4
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 1, 17.80);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 2, 19.20);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 3, 18.90);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 4, 4.10);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (11, 6, 40);

            -- Faixa 5
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 1, 18.80);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 2, 19.20);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 3, 18.08);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 4, 3.92);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (12, 6, 40);

            -- Faixa 5
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 1, 31.33);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 2, 32);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 3, 30.13);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 4, 6.54);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (13, 6, 5);

            -- Faixa 6
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 1, 53.50);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 2, 21.50);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 3, 20.55);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 4, 4.45);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 5, null);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (14, 6, null);

            -- ANEXO V
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 15.5, 0, 1); -- sequencial 13
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 18, 4500, 2);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 19.50, 9900, 3);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 20.50, 17100, 4);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 23, 62100, 5);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixas (q164_issgsanexosfaixas, q164_aliquotatotal, q164_valordeducao, q164_issgsanexoscadfaixas) VALUES (3, 30.50, 540000, 6); -- sequencial 18

            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (13, 0, 100); -- sequencial 15
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (14, 0, 100);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (15, 0, 100);
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (16, 0, 100);

            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (17, 0, 100); -- a. faixa -- sequencial 19
            INSERT INTO issqn.issgsanexosfaixasimpostosfaixaslimites (q166_issgsanexosfaixasimpostosfaixas, q166_limite_inicial, q166_limite_final) VALUES (18, 0, 100 ); -- sequencial 20

            -- Faixa 1
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 1, 25);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 2, 15);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 3, 14.10);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 4, 3.05);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 5, 28.85 );
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (15, 6, 14);

            -- Faixa 2
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 1, 23);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 2, 15);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 3, 14.10);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 4, 3.05);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 5, 27.85);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (16, 6, 17);

            -- Faixa 3
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 1, 24);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 2, 15);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 3, 14.92);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 4, 3.23);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 5, 23.85);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (17, 6, 19);

            -- Faixa 4
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 1, 21);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 2, 15);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 3, 15.74);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 4, 3.41);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 5, 23.85);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (18, 6, 21);

            -- Faixa 5
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 1, 23);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 2, 12.50);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 3, 14.10);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 4, 3.05);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 5, 23.85);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (19, 6, 23.50);

            -- Faixa 6
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 1, 35);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 2, 15.50);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 3, 16.44);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 4, 3.56);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 5, 29.50);
            INSERT INTO issqn.issgsanexosfaixasimpostosvalores (q165_issgsanexosfaixasimpostoslimites, q165_issgscadimpostos, q165_aliquotaimposto) VALUES (20, 6, null);

            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- INSERÇÃO TABELA ISSGSANEXOS
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- Estrutural 01.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico 
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 01.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 01.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 01.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 01.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 01.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 01.08
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '01.08'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 03.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '03.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.07
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.07'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.08
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.08'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.09
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.09'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.10
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.10'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 04.11
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.11'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.12
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.12'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.13
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.13'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 04.14
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.14'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.15
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.15'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.16
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.16'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 04.17
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '04.17'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 05.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '05.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 06.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '06.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 07.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '07.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 07.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '07.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 07.10
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '07.10'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 08.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '08.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 08.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '08.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 09.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '09.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 09.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '09.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 10.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.07
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.07'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.08
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.08'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.09
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.09'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 10.10
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '10.10'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 11.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '11.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 11.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '11.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 11.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '11.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 11.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '11.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 12.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.04
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.04'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.07
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.07'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.08
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.08'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.09
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.09'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.10
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.10'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.11
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.11'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.12
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.12'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.13
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.13'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.14
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.14'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.15
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.15'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.16
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.16'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 12.17
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '12.17'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 14.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '14.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 14.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '14.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 14.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '14.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 14.05
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '14.05'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 14.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '14.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 16.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '16.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 16.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '16.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 17.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.02
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.02'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.03
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.03'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.06
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.06'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.09
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.09'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.12
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.12'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.13
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.13'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.14
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.14'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '4'),
                (null)
            );
            -- Estrutural 17.16
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.16'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.17
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.17'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.19
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.19'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 17.20
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.20'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.21
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.21'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.23
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.23'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.24
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.24'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 17.25
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '17.25'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 19.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '19.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 26.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '26.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '3'),
                (null)
            );
            -- Estrutural 28.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '28.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 29.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '29.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 30.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '30.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 31.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '31.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 32.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '32.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 33.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '33.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 34.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '34.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 35.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '35.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 36.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '36.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 37.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '37.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 38.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '38.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 39.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '39.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );
            -- Estrutural 40.01
            INSERT INTO issqn.issgsanexos (q162_issgruposervico, q162_issgscadanexos, q162_data_fim)
            VALUES (
                (SELECT q126_sequencial FROM issgruposervico
                  INNER JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                  WHERE db121_estrutural = '40.01'
                ),
                (SELECT q157_sequencial FROM issgscadanexos WHERE q157_codigo = '5'),
                (null)
            );

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(
            <<<SQL
            -- REMOÇÃO DE FKs
            DELETE FROM db_sysforkey WHERE codarq IN (1010553, 1010554, 1010555, 1010556, 1010567);

            -- REMOÇÃO DE CAMPOS
            DELETE FROM db_sysarqcamp WHERE codarq IN (1010550, 1010551, 1010552, 1010553, 1010554, 1010555, 1010556, 1010567);
            DELETE FROM db_syscampo   WHERE codcam IN (1011198, 1011199, 1011200, 1011201, 1011202, 1011203, 1011204, 1011205, 1011206, 1011207, 1011208, 1011209, 1011210, 1011211, 1011218, 1011219, 1011220, 1011221, 1011222, 1011223, 1011224, 1011225, 1011232, 1011233, 1011234, 1011306, 1011307, 1011308, 1011309);

            -- REMOÇÃO DE PKs
            DELETE FROM db_sysprikey  WHERE codarq IN (1010550, 1010551, 1010552, 1010553, 1010554, 1010555, 1010556, 1010567);

            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            -- REMOÇÃO DE TABELAS
            -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
            DELETE FROM db_sysarqmod  WHERE codarq IN (1010550, 1010551, 1010552, 1010553, 1010554, 1010555, 1010556, 1010567);
            DELETE FROM db_sysarquivo WHERE codarq IN (1010550, 1010551, 1010552, 1010553, 1010554, 1010555, 1010556, 1010567);

            -- REMOÇÃO DE SEQUENCES
            DELETE FROM db_syssequencia WHERE codsequencia IN (1000901, 1000902, 1000903, 1000904, 1000905, 1000906, 1000907, 1000916);

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(
            <<<SQL
            DROP TABLE IF EXISTS issqn.issgscadanexos CASCADE;
            DROP TABLE IF EXISTS issqn.issgscadimpostos CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexoscadfaixas CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexos CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexosfaixas CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexosfaixasimpostosfaixas CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexosfaixasimpostosvalores CASCADE;
            DROP TABLE IF EXISTS issqn.issgsanexosfaixasimpostosfaixaslimites CASCADE;

SQL
        );
    }

}
