<?php

use Classes\PostgresMigration;

class M16127CadastroImoveisRurais extends PostgresMigration
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
        -- Cria campo na tabela iptubase que vincula a tipoimovel
        INSERT INTO db_syscampo VALUES(1011672,'j01_tipoimovel','int4','Vincula a tabela tipoimovel','0', 'Tipo de Imóvel',10,'f','f','f',1,'text','Tipo de Imóvel');

        -- Cria campos na tabela iptubase referentes ao imóvel rural
        INSERT INTO db_syscampo VALUES(1011742,'j01_distrito','char(4)','Distrito','', 'Distrito',4,'f','t','f',0,'text','Distrito');
        INSERT INTO db_syscampo VALUES(1011743,'j01_hectare','float4','Hectare imovel rural','0', 'Hectare',10,'f','f','f',4,'text','Hectare');
        INSERT INTO db_syscampo VALUES(1011744,'j01_situcad','varchar(50)','Situação Cadastral de imóvel rural','', 'Situação Cadastral',50,'f','t','f',0,'text','Situação Cadastral');
        INSERT INTO db_syscampo VALUES(1011745,'j01_datacad','date','Data Cadastro de imóvel rural','null', 'Data Cadastro',10,'f','f','f',1,'text','Data Cadastro');
        INSERT INTO db_syscampo VALUES(1011746,'j01_processo','int4','Número Processo de imóvel rural','0', 'Processo',10,'f','f','f',1,'text','Processo');
        INSERT INTO db_syscampo VALUES(1011747,'j01_incra','int4','Inscrição INCRA de imóvel rural','0', 'Inscrição INCRA',10,'f','f','f',1,'text','Inscrição INCRA');
        INSERT INTO db_syscampo VALUES(1011748,'j01_descrlocal','varchar(255)','Descrição da Localização de imóvel rural','', 'Descrição da Localização',255,'f','t','f',0,'text','Descrição da Localização');

        -- Vincula os campos na tabela iptubase
        INSERT INTO db_sysarqcamp VALUES(27,1011672,9,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011742,10,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011743,11,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011744,12,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011745,13,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011746,14,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011747,15,0);
        INSERT INTO db_sysarqcamp VALUES(27,1011748,16,0);

        -- Ajusta fonte de inclusão da manutenção de imóveis para receber tipo de Imovel Urbano
        UPDATE db_itensmenu SET id_item = 1721 , descricao = 'Inclusão' , help = 'Inclusão' , funcao = 'cad1_iptubase001.php?tipoImovel=1' , itemativo = '1' , manutencao = '1' , desctec = 'Inclusão de matrícula' , libcliente = 'true' where id_item = 1721;

        -- Cria menu Manutenção de Imóveis Rurais
        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228285 ,'Manutenção de Imóveis Rurais' ,'Manutenção de Imóveis Rurais' ,'' ,'1' ,'1' ,'Inclusão, Alteração, Baixa e Exclusão de Baixa de um Imóvel Rurais' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 32 ,228285 ,518 ,578 );

        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228286 ,'Inclusão' ,'Inclusão ' ,'cad1_iptubase001.php?tipoImovel=2' ,'1' ,'1' ,'Inclusão de matrícula rural' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228285 ,228286 ,1 ,578 );

        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228287 ,'Alteração' ,'Alteração' ,'cad1_iptubase002.php' ,'1' ,'1' ,'Alteração de Imóvel Rural' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228285 ,228287 ,2 ,578 );

        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228288 ,'Baixa' ,'Baixa' ,'cad1_iptubaixa001.php' ,'1' ,'1' ,'Baixa da Matricula de Imóvel Rural' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228285 ,228288 ,3 ,578 );

        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228289 ,'Exclusão da Baixa' ,'Exclusão da Baixa' ,'cad1_iptubaixa003.php' ,'1' ,'1' ,'Exclusão de Baixa de Matricula de Imóvel Rural' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228285 ,228289 ,4 ,578 );

        -- Atribui parâmetro tipoImovel para menus de Alteração da Manutenção de Imóveis Urbano e Rural
        UPDATE db_itensmenu SET id_item = 228287 , descricao = 'Alteração' , help = 'Alteração' , funcao = 'cad1_iptubase002.php?tipoImovel=2' , itemativo = '1' , manutencao = '1' , desctec = 'Alteração de Imóvel Rural' , libcliente = 'true' WHERE id_item = 228287;
        UPDATE db_itensmenu SET id_item = 1722 , descricao = 'Alteração' , help = 'Alteração' , funcao = 'cad1_iptubase002.php?tipoImovel=1' , itemativo = '1' , manutencao = '1' , desctec = 'Alteração de Matrícula do Imóvel' , libcliente = 'true' WHERE id_item = 1722;

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228303 ,'Consulta Imóvel Rural' ,'Consulta Imóvel Rural' ,'cad1_consimovelrural.php' ,'1' ,'1' ,'Menu para consulta de imóvel rural.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,228303 ,191 ,578 );

        -- Atribui parâmetro tipoImovel para menus de Baixa da Manutenção de Imóveis Urbano e Rural
        UPDATE db_itensmenu SET id_item = 5742 , descricao = 'Baixa' , help = 'Baixa' , funcao = 'cad1_iptubaixa001.php?tipoImovel=1' , itemativo = '1' , manutencao = '1' , desctec = 'Baixa da Matricula, não possui exclusão da Matricula' , libcliente = 'true' WHERE id_item = 5742;
        UPDATE db_itensmenu SET id_item = 228288 , descricao = 'Baixa' , help = 'Baixa' , funcao = 'cad1_iptubaixa001.php?tipoImovel=2' , itemativo = '1' , manutencao = '1' , desctec = 'Baixa da Matricula de Imóvel Rural' , libcliente = 'true' WHERE id_item = 228288;

        -- Atribui parâmetro tipoImovel para Consulta do Cadastro Técnico Municipal (Novo)
        UPDATE db_itensmenu SET id_item = 8929 , descricao = 'Cadastro Técnico Municipal (Novo)' , help = 'Cadastro Técnico Municipal (Novo)' , funcao = 'cad3_conscadastronovo_001.php?tipoImovel=1' , itemativo = '1' , manutencao = '1' , desctec = 'Nova tela de consulta cadastro técnico municipal' , libcliente = 'true' WHERE id_item = 8929;

        -- Cria campo de parâmetro de Matrícula de Imóvel Rural e vincula na tabela paritbi
        INSERT INTO db_syscampo VALUES(1011737,'it24_matricrural','bool','Parâmetro de Matrícula Imóvel Rural','f', 'Matrícula Imóvel Rural',1,'f','f','f',5,'text','Matrícula Imóvel Rural');
        INSERT INTO db_sysarqcamp VALUES(2362,1011737,15,0);

        -- Cria parâmetro tipoImovel rural na exclusão da baixa de imóveis rurais
        UPDATE db_itensmenu SET id_item = 228289 , descricao = 'Exclusão da Baixa' , help = 'Exclusão da Baixa' , funcao = 'cad1_iptubaixa003.php?tipoImovel=2' , itemativo = '1' , manutencao = '1' , desctec = 'Exclusão de Baixa de Matricula de Imóvel Rural' , libcliente = 'true' WHERE id_item = 228289;
        

        -- percposserural
        insert into db_sysarquivo values (1010616, 'percposserural', 'Percentual de Posse da Matrícula Rural', 'j166', '2020-08-27', 'percposserural', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (2,1010616);
        insert into db_syscampo values(1011797,'j166_sequencial','int4','Código sequencial da tabela.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1011798,'j166_matric','int8','Matrícula','0', 'Matrícula',20,'f','f','f',1,'text','Matrícula');
        insert into db_syscampo values(1011799,'j166_numcgm','int8','Número do CGM.','0', 'CGM',20,'f','f','f',1,'text','CGM');
        insert into db_syscampo values(1011800,'j166_percentual','float4','Percentual da posse do proprietário da matrícula.','0', 'Percentual',10,'t','f','f',4,'text','Percentual');
        insert into db_sysarqcamp values(1010616,1011797,1,0);
        insert into db_sysarqcamp values(1010616,1011798,2,0);
        insert into db_sysarqcamp values(1010616,1011799,3,0); 
        insert into db_sysarqcamp values(1010616,1011800,4,0); 
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010616,1011797,1,1011797);
        insert into db_sysforkey values(1010616,1011798,1,27,0);   
        insert into db_sysforkey values(1010616,1011799,1,42,0);
        insert into db_syssequencia values(1000967, 'percposserural_j166_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000967 where codarq = 1010616 and codcam = 1011797;
SQL
        );
    }

    public function upEstrutura()
    {
        $aAnos = $this->anosRetroativos();

        $this->execute("SELECT fc_startsession();");

        foreach ($aAnos as $ano) {
            $schema = "cadastro";

            $this->execute("SELECT fc_set_pg_search_path();");

            if ($ano != 2020) {
                $schema = "{$schema}_{$ano}";
            }

            $this->execute(<<<SQL
            
            -- Cria coluna nova na tabela iptubase que vincula tipoimovel
            ALTER TABLE {$schema}.iptubase ADD COLUMN "j01_tipoimovel" INTEGER DEFAULT 1;

            -- Cria colunas na iptubase
            ALTER TABLE {$schema}.iptubase add column "j01_distrito"   VARCHAR(50);
            ALTER TABLE {$schema}.iptubase add column "j01_hectare"    DOUBLE PRECISION DEFAULT 0;
            ALTER TABLE {$schema}.iptubase add column "j01_situcad"    VARCHAR(50);
            ALTER TABLE {$schema}.iptubase add column "j01_datacad"    DATE;
            ALTER TABLE {$schema}.iptubase add column "j01_processo"   INTEGER;
            ALTER TABLE {$schema}.iptubase add column "j01_incra"      INTEGER;
            ALTER TABLE {$schema}.iptubase add column "j01_descrlocal" VARCHAR(255);

SQL
            );
        }

        $this->execute(
        <<<SQL

        -- Cria coluna de Matricula de Imóveis Rurais na tabela de parâmetros de ITBI
        ALTER TABLE itbi.paritbi ADD COLUMN "it24_matricrural" BOOL DEFAULT FALSE;

        -- Insere lote padrão rural
        INSERT INTO lote VALUES (
            1000000000, 
            (SELECT j30_codi FROM setor ORDER BY 1 LIMIT 1),
            '0001',
            '',
            0,
            (SELECT j13_codi FROM bairro ORDER BY 1 LIMIT 1), 
            0,
            0,
            0,
            0,
            0
        ) ON CONFLICT (j34_idbql) DO NOTHING;

        INSERT INTO setorfiscal VALUES (nextval('setorfiscal_j90_codigo_seq'), 'Setor Lote Rural', 0);

        INSERT INTO lotesetorfiscal VALUES (1000000000, 
            (SELECT currval('setorfiscal_j90_codigo_seq'))
        );

        INSERT INTO testada VALUES (1000000000, 1763, 
        (SELECT j36_codigo FROM testada ORDER BY 1 LIMIT 1)
        , 1, 1);

        INSERT INTO carlote VALUES (1000000000, 
        (SELECT j35_caract FROM carlote ORDER BY 1 LIMIT 1)
        , current_timestamp);

        -- Cria tabela de percentual de posse de matrícula rural
        CREATE TABLE cadastro.percposserural (
            j166_sequencial SERIAL,
            j166_matric INT,
            j166_numcgm INT,
            j166_percentual float DEFAULT 0
        );

        ALTER TABLE cadastro.percposserural add CONSTRAINT "percposserural_j166_matric_fk" FOREIGN KEY ("j166_matric") REFERENCES "iptubase"("j01_matric");
        ALTER TABLE cadastro.percposserural add CONSTRAINT "percposserural_j166_numcgm_fk" FOREIGN KEY ("j166_numcgm") REFERENCES "cgm"("z01_numcgm");

SQL
        );
    }

    private function anosRetroativos()
    {
        $this->execute("SELECT fc_set_pg_search_path();");

        $stmt = $this->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'cadastro'
                                    AND table_name = 'calculoretroativoiptuschema'");
        $numlinhas = $stmt->rowcount();
        $anos[] = 2020;

        if ($numlinhas > 0) {
            $rAnos = $this->query("SELECT j153_anousu FROM calculoretroativoiptuschema order by 1 desc;");
            $collection = $rAnos->fetchAll();

            foreach ($collection as $ano){
                $anos[] = $ano["j153_anousu"];
            }
        }

        return $anos;
    }

    public function downDicionario()
    {
        $this->execute(
        <<<SQL
        -- Remove Menu
        DELETE FROM db_menu WHERE id_item_filho = 228269 AND modulo = 578;
        DELETE FROM db_itensmenu WHERE id_item = 228269;

        -- Remove os campos e os seus vínculos da tabela iptubase referentes ao imóvel rural
        DELETE FROM db_sysarqcamp WHERE codcam IN (1011672, 1011742, 1011743, 1011744, 1011745, 1011746, 1011747, 1011748);
        DELETE FROM db_syscampo WHERE codcam IN (1011672, 1011742, 1011743, 1011744, 1011745, 1011746, 1011747, 1011748);

        -- Desnvicula e remove menu de Manutenção de Imóveis Rurais
        DELETE FROM db_menu WHERE id_item_filho = 228285 AND modulo = 578;
        DELETE FROM db_menu WHERE id_item_filho = 228286 AND modulo = 578;
        DELETE FROM db_menu WHERE id_item_filho = 228287 AND modulo = 578;
        DELETE FROM db_menu WHERE id_item_filho = 228288 AND modulo = 578;
        DELETE FROM db_menu WHERE id_item_filho = 228289 AND modulo = 578;
        DELETE FROM db_menu WHERE id_item_filho = 228303 AND modulo = 578;

        DELETE FROM db_itensmenu WHERE id_item = 228285;
        DELETE FROM db_itensmenu WHERE id_item = 228286;
        DELETE FROM db_itensmenu WHERE id_item = 228287;
        DELETE FROM db_itensmenu WHERE id_item = 228288;
        DELETE FROM db_itensmenu WHERE id_item = 228289;
        DELETE FROM db_itensmenu WHERE id_item = 228303;

        -- Remove parâmetro tipoImovel para menus de Baixa da Manutenção de Imóveis Urbano e Rural
        UPDATE db_itensmenu SET id_item = 5742 , descricao = 'Baixa' , help = 'Baixa' , funcao = 'cad1_iptubaixa001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Baixa da Matricula, não possui exclusão da Matricula' , libcliente = 'true' WHERE id_item = 5742;
        
        -- Remove parâmetro tipoImovel para Consulta do Cadastro Técnico Municipal (Novo)
        UPDATE db_itensmenu SET id_item = 8929 , descricao = 'Cadastro Técnico Municipal (Novo)' , help = 'Cadastro Técnico Municipal (Novo)' , funcao = 'cad3_conscadastronovo_001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Nova tela de consulta cadastro técnico municipal' , libcliente = 'true' WHERE id_item = 8929;

        -- Remove o vínculo da tabela paritbi e deleta campo de parâmetro de Matrícula de Imóvel Rural
        DELETE FROM db_sysarqcamp WHERE codcam = 1011737;
        DELETE FROM db_syscampo WHERE codcam = 1011737;

        -- Remove parâmetro tipoImovel rural na exclusão da baixa de imóveis rurais
        UPDATE db_itensmenu SET id_item = 228289 , descricao = 'Exclusão da Baixa' , help = 'Exclusão da Baixa' , funcao = 'cad1_iptubaixa003.php?' , itemativo = '1' , manutencao = '1' , desctec = 'Exclusão de Baixa de Matricula de Imóvel Rural' , libcliente = 'true' WHERE id_item = 228289;


        -- percposserual
        delete from db_syssequencia where codsequencia = 1000967;
        delete from db_sysprikey where codarq = 1010616;
        delete from db_sysforkey where codarq = 1010616;
        delete from db_sysarqcamp where codarq = 1010616;
        DELETE FROM db_syscampo WHERE codcam IN (1011797, 1011798, 1011799, 1011800);
        DELETE FROM db_sysarqmod WHERE codarq = 1010616;
        DELETE FROM db_sysarquivo WHERE codarq = 1010616;
SQL
        );
    }

    public function downEstrutura()
    {
     
        $aAnos = $this->anosRetroativos();

        $this->execute("SELECT fc_startsession();");

        foreach ($aAnos as $ano) {
            $schema = "cadastro";

            $this->execute("SELECT fc_set_pg_search_path();");

            if ($ano != 2020) {
                $schema = "{$schema}_{$ano}";
            }

            $this->execute(<<<SQL
            
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_tipoimovel";    
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_distrito";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_hectare";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_situcad";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_datacad";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_processo";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_incra";
            ALTER TABLE {$schema}.iptubase DROP COLUMN "j01_descrlocal";

SQL
            );
        }

        $this->execute(
        <<<SQL

        -- Cria coluna de Matricula de Imóveis Rurais na tabela de parâmetros de ITBI
        ALTER TABLE itbi.paritbi DROP COLUMN "it24_matricrural";

        -- Remove dados da Base que foram cadastrados utilizando lote rural
        DELETE FROM testada         WHERE j36_idbql = 1000000000;
        DELETE FROM lotesetorfiscal WHERE j91_idbql = 1000000000;
        DELETE FROM setorfiscal     WHERE j90_descr = 'Setor Lote Rural';
        DELETE FROM carlote         WHERE j35_idbql = 1000000000;

        DROP TABLE cadastro.percposserural;

SQL
        );
    }

}
