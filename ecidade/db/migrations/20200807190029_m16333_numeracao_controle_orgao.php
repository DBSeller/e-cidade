<?php

use Classes\PostgresMigration;

class M16333NumeracaoControleOrgao extends PostgresMigration
{
    public function up()
    {
       $this->upTableTipoDocumentoProcesso();
       $this->upMenuTipoDocumentoProcesso();
       $this->upColumnTipoDocumentoProcesso();
       $this->upOpcaoControleOrgao();
       $this->upInstituicaoParametroGlobal();
       $this->upNovasColunasControle();
       $this->upColumnsProtprocesso();
       $this->upMenusVolume();
    }

    public function down()
    {
        $this->downColumnTipoDocumentoProcesso();
        $this->downTableTipoDocumentoProcesso();
        $this->downMenuTipoDocumentoProcesso();
        $this->downOpcaoControleOrgao();
        $this->downInstituicaoParametroGlobal();
        $this->downNovasColunasControle();
        $this->downColumnsProtprocesso();
        $this->downMenusVolume();
    }

    private function upTableTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010611, 'prottipodocumentoprocesso', 'Tipo de Documento do Processo.', 'p91', '2020-08-07', 'Tipo de Documento', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010611);
            insert into db_syscampo values(1011749,'p91_sequencial','int4','PK da tabela.','0', 'Código Tipo Documento',10,'f','f','f',1,'text','Código Tipo Documento');
            insert into db_syscampo values(1011750,'p91_descricao','varchar(60)','Tipo de Documento do Processo','', 'Tipo de Documento',60,'f','f','f',0,'text','Tipo de Documento');
            insert into db_sysarqcamp values(1010611,1011749,1,0);
            insert into db_sysarqcamp values(1010611,1011750,2,0);
            
            insert into db_syscampo values(1011784,'p91_sigla','varchar(10)','Sigla do tipo de documento.','', 'Sigla',10,'f','t','f',0,'text','Sigla');
            insert into db_sysarqcamp values(1010611,1011784,3,0);

            
            insert into db_syssequencia values(1000962, 'prottipodocumentoprocesso_p91_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000962 where codarq = 1010611 and codcam = 1011749;

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010611,1011749,1,1011749);

            create table protocolo.prottipodocumentoprocesso(
                p91_sequencial int4,
                p91_descricao varchar(60) not null,
                p91_sigla varchar(10) not null,
                CONSTRAINT prottipodocumentoprocesso_pk PRIMARY KEY (p91_sequencial)
            );

            CREATE SEQUENCE protocolo.prottipodocumentoprocesso_p91_sequencial_seq 
                INCREMENT 1
                MINVALUE 1000 
                MAXVALUE 9223372036854775807 
                START 1000
                CACHE 1;
            
            INSERT INTO protocolo.prottipodocumentoprocesso values(1, 'Processo', 'PRO');
            INSERT INTO protocolo.prottipodocumentoprocesso values(2, 'Memorando', 'MEM');
            INSERT INTO protocolo.prottipodocumentoprocesso values(3, 'Ofício', 'OFC');
            INSERT INTO protocolo.prottipodocumentoprocesso values(4, 'Decreto', 'DEC');
SQL;

        $this->execute($sql);
    }

    private function downTableTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 1010611;
            delete from db_syscampo where codcam = 1011749;
            delete from db_syscampo where codcam = 1011750;
            delete from db_syscampo where codcam = 1011784;
            delete from db_sysarqmod where codarq = 1010611;
            delete from db_sysarquivo where codarq = 1010611;
            delete from db_syssequencia where codsequencia = 1000962;
            delete from db_sysprikey where codarq = 1010611;

            DROP SEQUENCE IF EXISTS protocolo.prottipodocumentoprocesso_p91_sequencial_seq;
            DROP TABLE IF EXISTS protocolo.prottipodocumentoprocesso;
SQL;

        $this->execute($sql);
    }

    private function upColumnTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1011758,'p51_prottipodocumentoprocesso','int4','Tipo de documento do processo.','0', 'Tipo de Documento',10,'f','f','f',1,'text','Tipo de Documento');
            insert into db_sysarqcamp values(393,1011758,7,0);
            insert into db_sysforkey values(393,1011758,1,1010611,0);

            ALTER TABLE protocolo.tipoproc ADD COLUMN p51_prottipodocumentoprocesso int4 NOT NULL DEFAULT 1;
            ALTER TABLE protocolo.tipoproc
                ADD CONSTRAINT tipoproc_prottipodocumentoprocesso_fk 
                FOREIGN KEY (p51_prottipodocumentoprocesso)
                REFERENCES protocolo.prottipodocumentoprocesso(p91_sequencial);
SQL;

        $this->execute($sql);
    }

    private function downColumnTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            delete from db_sysforkey where codarq = 393 and codcam = 1011758;
            delete from db_sysarqcamp where codarq = 393 and codcam = 1011758;
            delete from db_syscampo where codcam = 1011758;

            ALTER TABLE protocolo.tipoproc DROP CONSTRAINT IF EXISTS tipoproc_prottipodocumentoprocesso_fk;
            ALTER TABLE protocolo.tipoproc DROP COLUMN IF EXISTS p51_prottipodocumentoprocesso;
SQL;

        $this->execute($sql);
    }

    private function upMenuTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228316 ,'Tipo de Documento' ,'Tipo de documento do processo.' ,'pro1_prottipodocumentoprocesso.php' ,'1' ,'1' ,'Tipo de documento do processo.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228316 ,294 ,604 );
SQL;
        
        $this->execute($sql);
    }

    private function downMenuTipoDocumentoProcesso()
    {
        $sql = <<<SQL
            DELETE FROM db_itensmenu WHERE id_item = 228316;
            DELETE FROM db_menu WHERE id_item_filho = 228316 AND modulo = 604;
SQL;

        $this->execute($sql);
    }

    private function upOpcaoControleOrgao()
    {
        $sql = <<<SQL
            update db_syscampo set nomecam = 'p06_tipo', conteudo = 'int4', descricao = 'Tipo de controle. 1 = controle pelo campo sequencial 2 = controle pelo sequencial do ano 3 = Órgão', valorinicial = '0', rotulo = 'TIpo de Controle', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'TIpo de Controle' where codcam = 18213;
            insert into db_syscampodef values(18213,'3','Órgão');
SQL;

        $this->execute($sql);
    }

    private function downOpcaoControleOrgao()
    {
        $sql = <<<SQL
            update db_syscampo set nomecam = 'p06_tipo', conteudo = 'int4', descricao = 'Tipo de controle. 1 = controle pelo campo sequencial 2 = controle pelo sequencial do ano', valorinicial = '0', rotulo = 'TIpo de Controle', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'TIpo de Controle' where codcam = 18213;
            delete from db_syscampodef where codcam = 18213 and defcampo = '3';
SQL;

        $this->execute($sql);
    }

    private function upInstituicaoParametroGlobal()
    {
        $sqlConfiguracao = 'SELECT p06_tipo FROM protparamglobal';

        $rsConfiguracao = $this->query($sqlConfiguracao);
        $configuracao = $rsConfiguracao->fetchAll(PDO::FETCH_CLASS);

        $p06_tipo = $configuracao[0]->p06_tipo;
        
        $sql = <<<SQL
            insert into db_syscampo values(1011785,'p06_instituicao','int4','Código da Instituição ao qual a configuração está atrelada.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_sysarqcamp values(3217,1011785,3,0);
            insert into db_sysforkey values(3217,1011785,1,83,0);

            TRUNCATE TABLE protparamglobal RESTART IDENTITY;
            ALTER SEQUENCE protparamglobal_p06_sequencial_seq RESTART WITH 1;
            ALTER TABLE protparamglobal ADD COLUMN p06_instituicao int4 NOT NULL;

            INSERT INTO protparamglobal (p06_sequencial, p06_tipo, p06_instituicao)
                SELECT nextval('protparamglobal_p06_sequencial_seq'), {$p06_tipo}, codigo
                FROM db_config;
SQL;

        $this->execute($sql);
    }

    private function downInstituicaoParametroGlobal()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = codarq and codcam = 1011785;
            delete from db_sysforkey where codarq = 3217 and codcam = 1011785;
            delete from db_syscampo where codcam = 1011785;

            ALTER TABLE protparamglobal DROP COLUMN p06_instituicao;
            DELETE FROM protparamglobal WHERE p06_sequencial > 1;
SQL;

        $this->execute($sql);
    }

    private function upNovasColunasControle()
    {
        $sql = <<<SQL
            update db_syscampo set nomecam = 'p07_orgao', conteudo = 'int4', descricao = 'órgão', valorinicial = '0', rotulo = 'Órgão', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Órgão' where codcam = 1699;
            insert into db_syscampo values(1011787,'p07_prottipodocumentoprocesso','int4','Tipo de documento do processo.','0', 'Tipo de Documento',10,'t','f','f',1,'text','Tipo de Documento');
            insert into db_sysarqcamp values(3216,1011787,5,0);
            insert into db_sysarqcamp values(3216,1699,6,0);
            insert into db_sysforkey values(3216,1011787,1,1010611,0);

            delete from db_sysindices where codind = 3206;
            DROP INDEX IF EXISTS protprocessonumeracao_instit_ano_in;

            insert into db_sysindices values(1008608,'idx_instit_ano_orgao_tipodoc',3216,'1');
            insert into db_syscadind values(1008608,18210,1);
            insert into db_syscadind values(1008608,18209,2);
            insert into db_syscadind values(1008608,1699,3);
            insert into db_syscadind values(1008608,1011787,4);

            ALTER TABLE protprocessonumeracao ADD COLUMN p07_prottipodocumentoprocesso int4 DEFAULT 0;
            ALTER TABLE protprocessonumeracao ADD COLUMN p07_orgao int4 DEFAULT 0;

            CREATE UNIQUE INDEX idx_instit_ano_orgao_tipodoc ON protprocessonumeracao (p07_instit, p07_ano, p07_orgao, p07_prottipodocumentoprocesso);
SQL;

        $this->execute($sql);
    }

    private function downNovasColunasControle()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 3216 and codcam = 1011787;
            delete from db_sysarqcamp where codarq = 3216 and codcam = 1699;
            delete from db_sysforkey where codarq = 3216 and codcam = 1011787;
            delete from db_syscampo where codcam = 1011787;

            delete from db_sysindices where codind = 1008608;
            delete from db_syscadind where codind = 1008608;

            DROP INDEX IF EXISTS idx_instit_ano_orgao_tipodoc;

            ALTER TABLE protprocessonumeracao DROP COLUMN IF EXISTS p07_prottipodocumentoprocesso;
            ALTER TABLE protprocessonumeracao DROP COLUMN IF EXISTS p07_orgao;
SQL;

        $this->execute($sql);
    }


    private function upColumnsProtprocesso()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1011792,'p58_orgao','int4','Órgão no qual processo foi cadastrado.','0', 'Órgão',10,'f','f','f',1,'text','Órgão');
            insert into db_syscampo values(1011793,'p58_processopai','int4','Indica qual o processo pai caso o processo em questão seja um volume.','0', 'Processo Principal',10,'f','f','f',1,'text','Processo Principal');
            insert into db_syscampo values(1011794,'p58_volume','int4','Campo que identifica volume.','0', 'Volume',10,'f','f','f',1,'text','Volume');
            insert into db_sysarqcamp values(403,1011792,18,0);
            insert into db_sysarqcamp values(403,1011793,20,0);
            insert into db_sysarqcamp values(403,1011794,19,0);

            ALTER TABLE protprocesso ADD COLUMN p58_orgao int4 NOT NULL DEFAULT 0;
            ALTER TABLE protprocesso ADD COLUMN p58_processopai int4 NOT NULL DEFAULT 0;
            ALTER TABLE protprocesso ADD COLUMN p58_volume int4 NOT NULL DEFAULT 0;
SQL;

        $this->execute($sql);
    }

    private function downColumnsProtprocesso()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 403 and codcam = 1011792;
            delete from db_sysarqcamp where codarq = 403 and codcam = 1011793;
            delete from db_sysarqcamp where codarq = 403 and codcam = 1011794;

            delete from db_syscampo where codcam IN (1011792, 1011793, 1011794);

            ALTER TABLE protprocesso DROP COLUMN IF EXISTS p58_orgao;
            ALTER TABLE protprocesso DROP COLUMN IF EXISTS p58_processopai;
            ALTER TABLE protprocesso DROP COLUMN IF EXISTS p58_volume;
SQL;

        $this->execute($sql);
    }

    private function upMenusVolume()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228321 ,'Volumes' ,'Volumes de um Processo' ,'' ,'1' ,'1' ,'Volumes são processos filhos de outro processo.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228321 ,520 ,604 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228322 ,'Inclusão' ,'Inclusão de Volumes' ,'pro4_protprocessovolume.php' ,'1' ,'1' ,'Inclusão de Volumes.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228321 ,228322 ,1 ,604 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228323 ,'Alteração' ,'Alteração de Volume' ,'pro4_protprocessovolume.php?alteracao=1' ,'1' ,'1' ,'Alteração de Volume.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228321 ,228323 ,2 ,604 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228324 ,'Anexar Documentos' ,'Anexar Documentos ao Volume' ,'prot4_processodocumento001.php?volumes=1' ,'1' ,'1' ,'Anexar Documentos ao Volume.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228321 ,228324 ,3 ,604 );

            update db_menu set menusequencia = 1 where id_item = 32 and modulo = 604 and id_item_filho = 2522;
            update db_menu set menusequencia = 2 where id_item = 32 and modulo = 604 and id_item_filho = 2162;
            update db_menu set menusequencia = 3 where id_item = 32 and modulo = 604 and id_item_filho = 228321;
            update db_menu set menusequencia = 4 where id_item = 32 and modulo = 604 and id_item_filho = 2181;
            update db_menu set menusequencia = 5 where id_item = 32 and modulo = 604 and id_item_filho = 2170;
            update db_menu set menusequencia = 6 where id_item = 32 and modulo = 604 and id_item_filho = 2174;
            update db_menu set menusequencia = 7 where id_item = 32 and modulo = 604 and id_item_filho = 2182;
            update db_menu set menusequencia = 8 where id_item = 32 and modulo = 604 and id_item_filho = 2183;
            update db_menu set menusequencia = 9 where id_item = 32 and modulo = 604 and id_item_filho = 3435;
            update db_menu set menusequencia = 10 where id_item = 32 and modulo = 604 and id_item_filho = 2136;
            update db_menu set menusequencia = 11 where id_item = 32 and modulo = 604 and id_item_filho = 111;
            update db_menu set menusequencia = 12 where id_item = 32 and modulo = 604 and id_item_filho = 3809;
            update db_menu set menusequencia = 13 where id_item = 32 and modulo = 604 and id_item_filho = 4137;
            update db_menu set menusequencia = 14 where id_item = 32 and modulo = 604 and id_item_filho = 4459;
            update db_menu set menusequencia = 15 where id_item = 32 and modulo = 604 and id_item_filho = 3775;
            update db_menu set menusequencia = 16 where id_item = 32 and modulo = 604 and id_item_filho = 7886;
            update db_menu set menusequencia = 17 where id_item = 32 and modulo = 604 and id_item_filho = 8880;
            update db_menu set menusequencia = 18 where id_item = 32 and modulo = 604 and id_item_filho = 9224;
            update db_menu set menusequencia = 19 where id_item = 32 and modulo = 604 and id_item_filho = 228068;
SQL;

        $this->execute($sql);
    }

    private function downMenusVolume()
    {
        $sql = <<<SQL
            delete from db_menu where id_item_filho = 228321 AND modulo = 604;        
            delete from db_menu where id_item_filho = 228322 AND modulo = 604;
            delete from db_menu where id_item_filho = 228323 AND modulo = 604;
            delete from db_menu where id_item_filho = 228324 AND modulo = 604;

            delete from db_itensmenu where id_item in (228321, 228322, 228323, 228324);

            update db_menu set menusequencia = 1 where id_item = 32 and modulo = 604 and id_item_filho = 2522;
            update db_menu set menusequencia = 2 where id_item = 32 and modulo = 604 and id_item_filho = 2162;
            update db_menu set menusequencia = 3 where id_item = 32 and modulo = 604 and id_item_filho = 2181;
            update db_menu set menusequencia = 4 where id_item = 32 and modulo = 604 and id_item_filho = 2170;
            update db_menu set menusequencia = 5 where id_item = 32 and modulo = 604 and id_item_filho = 2174;
            update db_menu set menusequencia = 6 where id_item = 32 and modulo = 604 and id_item_filho = 2182;
            update db_menu set menusequencia = 7 where id_item = 32 and modulo = 604 and id_item_filho = 2183;
            update db_menu set menusequencia = 8 where id_item = 32 and modulo = 604 and id_item_filho = 3435;
            update db_menu set menusequencia = 9 where id_item = 32 and modulo = 604 and id_item_filho = 2136;
            update db_menu set menusequencia = 10 where id_item = 32 and modulo = 604 and id_item_filho = 111;
            update db_menu set menusequencia = 11 where id_item = 32 and modulo = 604 and id_item_filho = 3809;
            update db_menu set menusequencia = 12 where id_item = 32 and modulo = 604 and id_item_filho = 4137;
            update db_menu set menusequencia = 13 where id_item = 32 and modulo = 604 and id_item_filho = 4459;
            update db_menu set menusequencia = 14 where id_item = 32 and modulo = 604 and id_item_filho = 3775;
            update db_menu set menusequencia = 15 where id_item = 32 and modulo = 604 and id_item_filho = 7886;
            update db_menu set menusequencia = 16 where id_item = 32 and modulo = 604 and id_item_filho = 8880;
            update db_menu set menusequencia = 17 where id_item = 32 and modulo = 604 and id_item_filho = 9224;
SQL;

        $this->execute($sql);
    }
}
