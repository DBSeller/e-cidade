<?php

use Classes\PostgresMigration;

class M13762ManutencaoSisobra extends PostgresMigration
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
        $this->upAcertoRegistros();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downAcertoRegistros();
        $this->downEstrutura();
    }

    /************************ UPs ************************/
    public function upDicionario()
    {
        $this->execute(<<<SQL
        update db_itensmenu set id_item = 3681 , descricao = 'Tipo de Responsável' , help = 'Tipo de Responsável' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo de Responsável' , libcliente = 'false' where id_item = 3681;

        -- Cria campos na tabela parprojetos para salvar local e senha do certificado A1
        insert into db_syscampo values(1013146,'ob21_localcertificadoa1','text','Campo que salva o local que se encontra o arquivo do certificado A1 para o SISOBRA','', 'Local Certificado A1',250,'f','f','f',0,'text','Local Certificado A1');
        insert into db_syscampo values(1013147,'ob21_senhacertificadoa1','int8','Campo que guarda a senha do certificado A1 para o SISOBRA','0', 'Senha Certificado A1',11,'f','f','f',1,'text','Senha Certificado A1');
        insert into db_sysarqcamp values(2051,1013146,9,0);
        insert into db_sysarqcamp values(2051,1013147,10,0);

        -- Cria campos na tabela obras para salvar numero art e rrt da obra, de acordo com responsáveis técnico e do projeto
        insert into db_syscampo values(1013148,'ob01_numeroartprojeto','varchar(11)','Número do documento expedido pelo CREA(ART) pelo Responsável pelo Projeto','0','ART Responsável Projeto',11,'t','t','f',0,'text','ART Responsável Projeto');
        insert into db_syscampo values(1013150,'ob01_numerorrtprojeto','varchar(11)','Número do documento expedido pelo CAU(RRT) pelo Responsávelo pelo Projeto','0','RRT Responsável Projeto',11,'t','t','f',0,'text','RRT Responsável Projeto');
        insert into db_syscampo values(1013243,'ob01_numeroarttecnico','varchar(11)','ART Responsável Técnico','0','ART Responsável Técnico',10,'t','t','f',0,'text','ART Responsável Técnico');
        insert into db_syscampo values(1013244,'ob01_numerorrttecnico','varchar(11)','RRT Responsável Técnico','0','RRT Responsável Técnico',10,'t','t','f',0,'text','RRT Responsável Técnico');
        insert into db_sysarqcamp values(946,1013148,12,0);
        insert into db_sysarqcamp values(946,1013150,13,0);
        insert into db_sysarqcamp values(946,1013244,14,0);
        insert into db_sysarqcamp values(946,1013243,15,0);

        -- Cria tabela obrastecprofissao
        insert into db_sysarquivo values (1010798, 'obrastecprofissao', 'profissao responsavel tecnico da obra', 'ob30', '2021-05-13', 'profissao responsavel tecnico da obra', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (40,1010798);
        insert into db_syscampo values(1013240,'ob30_sequencial','int4','sequencial da tabela obrastecprofissao','0', 'sequencial da tabela obrastecprofissao',10,'f','f','f',1,'text','sequencial da tabela obrastecprofissao');
        insert into db_syscampo values(1013241,'ob30_descricao','varchar(50)','descricao da profissao do responsável técnico da obra','', 'descricao da profissao obrastecprofissao',50,'f','t','f',0,'text','descricao da profissao obrastecprofissao');
        insert into db_sysarqcamp values(1010798,1013240,1,0);
        insert into db_sysarqcamp values(1010798,1013241,2,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010798,1013240,1,1013240);
        insert into db_syssequencia values(1001003, 'obrastecprofissao_ob30_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001003 where codarq = 1010798 and codcam = 1013240;

        -- Cria campo chave estrangeira na tabela obrastec, para referenciar tabela nova obrastecprofissao
        insert into db_syscampo values(1013242,'ob15_profissao','int4','referencia a tabela obrastecprofissao','0', 'Profissão',10,'f','f','f',1,'text','Profissão');
        insert into db_sysarqcamp values(1001,1013242,5,0);
        insert into db_sysforkey values(1001,1013242,1,1010798,0);

        -- Cria nova tabela obrasenvioregalvara
        insert into db_sysarquivo values (1010799, 'obrasenvioregalvara', 'armazena as obras enviadas ,seus registros e alvarás', 'ob31', '2021-05-14', 'Obras enviadas registro com alvarás', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (40,1010799);
        insert into db_syscampo values(1013246,'ob31_sequencial','int4','Sequencial tabela obrasenvioregalvara','0', 'Sequencial tabela obrasenvioregalvara',10,'f','f','f',1,'text','Sequencial tabela obrasenvioregalvara');
        insert into db_syscampo values(1013247,'ob31_obrasenvioreg','int8','Referencia obrasenvioreg','0', 'Referencia obrasenvioreg',10,'f','f','f',1,'text','Referencia obrasenvioreg');
        insert into db_syscampo values(1013248,'ob31_codalvara','int4','Código alvará','0', 'Código alvará',10,'f','f','f',1,'text','Código alvará');
        insert into db_syscampo values(1013250,'ob31_protocolo','int8','Código protocolo retorno alvará','0', 'Código protocolo retorno alvará',20,'f','f','f',1,'text','Código protocolo retorno alvará');
        insert into db_sysarqcamp values(1010799,1013246,1,0);
        insert into db_sysarqcamp values(1010799,1013247,2,0);
        insert into db_sysarqcamp values(1010799,1013248,3,0);
        insert into db_sysarqcamp values(1010799,1013250,5,0);
        insert into db_syssequencia values(1001004, 'obrasenvioregalvara_ob31_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001004 where codarq = 1010799 and codcam = 1013246;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010799,1013246,1,1013246);
        insert into db_sysforkey values(1010799,1013247,1,1056,0);

        -- Cria campos na tabela obrasenvioreghab
        insert into db_syscampo values(1013251,'ob18_codretorno','varchar(50)','Código retorno habitese','', 'Código retorno habitese',50,'f','t','f',0,'text','Código retorno habitese');
        insert into db_syscampo values(1013252,'ob18_protocolo','int8','Código protocolo retorno habitese','0', 'Código protocolo retorno habitese',20,'f','f','f',1,'text','Código protocolo retorno habitese');
        insert into db_sysarqcamp values(1057,1013251,4,0);
        insert into db_sysarqcamp values(1057,1013252,5,0);

SQL
        );

    }

    public function upAcertoRegistros()
    {
        $this->execute(<<<SQL
        /*
         * Altera tiporesp da tabela obras (caso seja proprietario ou incorporadora) para o padrão correto
         */
        UPDATE obras SET ob01_tiporesp = 51 WHERE ob01_tiporesp NOT IN (2, 55);
        UPDATE obras SET ob01_tiporesp = 55 WHERE ob01_tiporesp = 2;

        /*
         * Remove tipos da tabela obrastiporesp sem vínculo na tabela obras
         */
        DELETE FROM
            obrastiporesp
        WHERE
            NOT EXISTS (
                SELECT
                    ob01_tiporesp
                FROM
                    obras
                WHERE
                    ob01_tiporesp = ob02_cod
            );

        /*
         * Insere tipos conforme padrão da Receita via manual Sisobrapref, na tabela obrastiporesp
         */
        UPDATE obrastiporesp SET ob02_descr = 'PROPRIETÁRIO' WHERE ob02_cod = 51;
        INSERT INTO obrastiporesp VALUES (51, 'PROPRIETÁRIO') ON CONFLICT DO NOTHING;
        INSERT INTO obrastiporesp VALUES (52, 'DONO') ON CONFLICT DO NOTHING;
        INSERT INTO obrastiporesp VALUES (53, 'CONSTRUTORA') ON CONFLICT DO NOTHING;
        UPDATE obrastiporesp SET ob02_descr = 'INCORPORADORA' WHERE ob02_cod = 55;
        INSERT INTO obrastiporesp VALUES (55, 'INCORPORADORA') ON CONFLICT DO NOTHING;
        INSERT INTO obrastiporesp VALUES (56, 'CONSTRUÇÃO NOME COLETIVO') ON CONFLICT DO NOTHING;

        /*
         * Insere dados na tabela caracter referentes a destinacao
         */
        UPDATE caracter SET j31_descr = 'COMERCIAL_SALAS_LOJAS' WHERE j31_descr = 'COMERCIAL' AND j31_grupo IN (SELECT ob21_grupotipoocupacao FROM parprojetos WHERE ob21_anousu = extract(year from now()));
        UPDATE caracter SET j31_descr = 'GALPAO_INDUSTRIAL' WHERE j31_descr = 'INDUSTRIAL' AND j31_grupo IN (SELECT ob21_grupotipoocupacao FROM parprojetos WHERE ob21_anousu = extract(year from now()));
        UPDATE caracter SET j31_descr = 'RESIDENCIAL_UNIFAMILIAR' WHERE j31_descr = 'RESIDENCIAL' AND j31_grupo IN (SELECT ob21_grupotipoocupacao FROM parprojetos WHERE ob21_anousu = extract(year from now()));

        SELECT nextval('caracter_j31_codigo_seq');

        INSERT INTO caracter
        SELECT
            last_value AS last_value,
            'RESIDENCIAL_MULTIFAMILIAR' AS j31_descr,
            ob21_grupotipoocupacao AS j31_grupo,
            0 AS j31_pontos
        FROM
            parprojetos,
            caracter_j31_codigo_seq
        WHERE
            ob21_anousu = extract(
                year
                FROM
                    NOW()
            );

        SELECT nextval('caracter_j31_codigo_seq');

        INSERT INTO caracter
        SELECT
            last_value AS last_value,
            'EDIFICIO_GARAGENS' AS j31_descr,
            ob21_grupotipoocupacao AS j31_grupo,
            0 AS j31_pontos
        FROM
            parprojetos,
            caracter_j31_codigo_seq
        WHERE
            ob21_anousu = extract(
                year
                FROM
                    NOW()
            );

        SELECT nextval('caracter_j31_codigo_seq');

        INSERT INTO caracter
        SELECT
            last_value AS last_value,
            'CASA_POPULAR' AS j31_descr,
            ob21_grupotipoocupacao AS j31_grupo,
            0 AS j31_pontos
        FROM
            parprojetos,
            caracter_j31_codigo_seq
        WHERE
            ob21_anousu = extract(
                year
                FROM
                    NOW()
            );

        SELECT nextval('caracter_j31_codigo_seq');

        INSERT INTO caracter
        SELECT
            last_value AS last_value,
            'CONJUNTO_HABITACIONAL_POPULAR' AS j31_descr,
            ob21_grupotipoocupacao AS j31_grupo,
            0 AS j31_pontos
        FROM
            parprojetos,
            caracter_j31_codigo_seq
        WHERE
            ob21_anousu = extract(
                year
                FROM
                    NOW()
            );

        /*
         * Atualiza destinacao na tabela obrasconstr de 'MISTA' e 'SERVIÇO' para 'COMERCIAL_SALAS_LOJAS' e remove da tabela caracter
         */
         -- Atualiza obrasconstr de 'MISTA' para 'COMERCIAL_SALAS_LOJAS'
        UPDATE obrasconstr SET ob08_ocupacao = (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'COMERCIAL_SALAS_LOJAS' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        ) WHERE ob08_ocupacao IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'MISTA' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );
         -- Atualiza caractercaracteristica de 'MISTA' para 'COMERCIAL_SALAS_LOJAS'
        UPDATE caractercaracteristica SET db143_caracter = (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'COMERCIAL_SALAS_LOJAS' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        ) WHERE db143_caracter IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'MISTA' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );
         -- Deleta 'MISTA' de caracter
        DELETE FROM caracter WHERE j31_codigo IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'MISTA' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );
         -- Atualiza obrasconstr de 'SERVIÇO' para 'COMERCIAL_SALAS_LOJAS'
        UPDATE obrasconstr SET ob08_ocupacao = (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'COMERCIAL_SALAS_LOJAS' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        ) WHERE ob08_ocupacao IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'SERVIÇO' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );
         -- Atualiza caractercaracteristica de 'SERVIÇO' para 'COMERCIAL_SALAS_LOJAS'
        UPDATE caractercaracteristica SET db143_caracter = (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'COMERCIAL_SALAS_LOJAS' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        ) WHERE db143_caracter IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'SERVIÇO' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );
         -- Deleta 'MISTA' de caracter
        DELETE FROM caracter WHERE j31_codigo IN (
            SELECT
                j31_codigo
            FROM
                caracter
            WHERE
                j31_descr = 'SERVIÇO' 
            AND j31_grupo IN (
                SELECT
                    ob21_grupotipoocupacao
                FROM
                    parprojetos
                WHERE
                    ob21_anousu = extract(year FROM NOW())
            )
        );

        /*
         * Insere e altera dados na tabela caracter referentes a categoria
         */
        UPDATE caracter SET j31_descr = 'OBRA_NOVA' WHERE j31_descr = 'NOVA' AND j31_grupo IN (SELECT ob21_grupotipolancamento FROM parprojetos WHERE ob21_anousu = extract(year from now()));
        UPDATE caracter SET j31_descr = 'ACRESCIMO' WHERE j31_descr = 'AMPLIAÇÃO' AND j31_grupo IN (SELECT ob21_grupotipolancamento FROM parprojetos WHERE ob21_anousu = extract(year from now()));
        UPDATE caracter SET j31_descr = 'EXISTENTE' WHERE j31_descr = 'REGULARIZACAO' AND j31_grupo IN (SELECT ob21_grupotipolancamento FROM parprojetos WHERE ob21_anousu = extract(year from now()));

SQL
        );
    }

    /************************ DOWNs ************************/
    public function downDicionario()
    {
        $this->execute(<<<SQL
        UPDATE db_itensmenu SET id_item = 3681 , descricao = 'Tipo de Responsável' , help = 'Tipo de Responsável' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo de Responsável' , libcliente = 'true' WHERE id_item = 3681;

        delete from db_sysforkey where codcam in (
            1013242,
            1013247
        );

        delete from db_syssequencia where codsequencia in (
            1001003,
            1001004
        );

        delete from db_sysprikey where codarq in (1010798, 1010799);

        delete from db_sysarqcamp where codcam in (
            1013146,
            1013147,
            1013148,
            1013150,
            1013243,
            1013244,
            1013240,
            1013241,
            1013242,
            1013251,
            1013252
        );
        delete from db_sysarqcamp where codarq in (
            1010798, 
            1010799
        );

        delete from db_syscampo where codcam in (
            1013146,
            1013147,
            1013148,
            1013150,
            1013243,
            1013244,
            1013240,
            1013241,
            1013242,
            1013246,
            1013247,
            1013248,
            1013250,
            1013251,
            1013252
        );

        delete from db_sysarqmod where codarq in (
            1010798,
            1010799
        );

        delete from db_sysarquivo where codarq in (
            1010798,
            1010799
        );

SQL
        );

    }

    public function downAcertoRegistros()
    {
        $this->execute(<<<SQL
        /*
         * Remove tipos da tabela obrastiporesp sem vínculo na tabela obras
         */
        DELETE FROM
            obrastiporesp
        WHERE
            NOT EXISTS (
                SELECT
                    *
                FROM
                    obras
                WHERE
                    ob01_tiporesp IN (51, 52, 53, 55, 56)
            )
            AND ob02_cod IN (51, 52, 53, 55, 56);
SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            alter table parprojetos add column ob21_localcertificadoa1 text;
            alter table parprojetos add column ob21_senhacertificadoa1 text;

            alter table obras add column ob01_numeroartprojeto text;
            alter table obras add column ob01_numerorrtprojeto text;
            alter table obras add column ob01_numeroarttecnico text;
            alter table obras add column ob01_numerorrttecnico text;

            create table obrastecprofissao (
                ob30_sequencial serial,
                ob30_descricao text,
                primary key(ob30_sequencial)
            );

            insert into obrastecprofissao values (1, 'Arquiteto');
            insert into obrastecprofissao values (2, 'Engenheiro');

            alter table obrastec add column ob15_profissao integer;
            alter table obrastec add constraint ob15_profissao_fk foreign key (ob15_profissao) references obrastecprofissao (ob30_sequencial);

            drop index obrastec_numcgm_tipo_in;
            create index obrastec_numcgm_tipo_profissao_in on obrastec (ob15_numcgm, ob15_tipo, ob15_profissao);

            create table obrasenvioregalvara (
                ob31_sequencial serial,
                ob31_obrasenvioreg integer,
                ob31_codalvara integer,
                ob31_protocolo varchar(250),
                primary key(ob31_sequencial),
                constraint ob31_obrasenvioreg_fk foreign key (ob31_obrasenvioreg) references obrasenvioreg (ob17_codobrasenvioreg)
            );

            alter table obrasenvioreghab add column ob18_protocolo varchar(250);

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            alter table parprojetos drop column ob21_localcertificadoa1;
            alter table parprojetos drop column ob21_senhacertificadoa1;

            alter table obras drop column ob01_numeroartprojeto;
            alter table obras drop column ob01_numerorrtprojeto;
            alter table obras drop column ob01_numeroarttecnico;
            alter table obras drop column ob01_numerorrttecnico;

            alter table obrastec drop column ob15_profissao;
            create index obrastec_numcgm_tipo_in on obrastec (ob15_numcgm, ob15_tipo);

            delete from obrastecprofissao;
            drop table obrastecprofissao;

            drop table obrasenvioregalvara;

            alter table obrasenvioreghab drop column ob18_protocolo;

SQL
        );
    }
}
