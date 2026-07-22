<?php

use Classes\PostgresMigration;

class M12498IptuTaxa extends PostgresMigration
{
    public function up()
    {
        $this->atualizaDicionario();
        $this->criaTabela();
    }

    public function down()
    {
        $this->retornaDicionario();
        $this->removeTabela();
    }

    private function atualizaDicionario()
    {
        $sql  = <<<SQL
            insert into db_sysarquivo values (1010404, 'iptutaxanump', 'Tabela de taxas do iptu', 'j151', '2019-01-10', 'Taxas de IPTU', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (2,1010404);
            insert into db_syscampo values(1010286,'j151_matric','int4','Matrícula do imóvel da taxa de iptu','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1010287,'j151_numpre','int8','Numpre da taxa de iptu','0', 'Numpre',10,'f','f','f',1,'text','Numpre');
            insert into db_syscampo values(1010288,'j151_iptucadtaxaexe','int4','Código do Cadastro de Taxa no Exercício','0', 'Código do Cadastro de Taxa no Exercício',10,'f','f','f',1,'text','Código do Cadastro de Taxa no Exercício');
            insert into db_syscampo values(1010289,'j151_codigo','int4','Código sequencial da iptutaxanump','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010290,'j152_codigo','int4','Código sequencia da tabela iptutaxacalv','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010291,'j152_iptutaxanump','int4','Código de vinculo com a iptutaxanump','0', 'Código Iptutaxanump',10,'f','f','f',1,'text','Código Iptutaxanump');
            insert into db_syscampo values(1010292,'j152_codhis','int4','Código de vinculo com a iptucalh','0', 'Código Iptucalh',10,'f','f','f',1,'text','Código Iptucalh');
            insert into db_syscampo values(1010293,'j152_receit','int4','Código de vinculo com a receita','0', 'Código da Receita',10,'f','f','f',1,'text','Código da Receita');
            insert into db_syscampo values(1010294,'j152_valor','float8','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor');
            insert into db_sysarquivo values (1010405, 'iptutaxacalv', 'Tabela de calculo de taxa de iptu', 'j152', '2019-01-10', 'iptutaxacalv', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (2,1010405);
            delete from db_sysarqcamp where codarq = 1010404;
            insert into db_sysarqcamp values(1010404,1010289,1,0);
            insert into db_sysarqcamp values(1010404,1010286,2,0);
            insert into db_sysarqcamp values(1010404,1010287,3,0);
            insert into db_sysarqcamp values(1010404,1010288,4,0);
            delete from db_sysprikey where codarq = 1010404;
            delete from db_sysprikey where codarq = 1010404;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010404,1010289,1,1010289);
            delete from db_sysforkey where codarq = 1010404 and referen = 0;
            insert into db_sysforkey values(1010404,1010286,1,27,0);
            delete from db_sysforkey where codarq = 1010404 and referen = 0;
            insert into db_sysforkey values(1010404,1010288,1,1629,0);
            insert into db_syssequencia values(1000812, 'iptutaxanump_j151_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000812 where codarq = 1010404 and codcam = 1010289;
            delete from db_sysarqcamp where codarq = 1010404;
            insert into db_sysarqcamp values(1010404,1010289,1,1000812);
            insert into db_sysarqcamp values(1010404,1010286,2,0);
            insert into db_sysarqcamp values(1010404,1010287,3,0);
            insert into db_sysarqcamp values(1010404,1010288,4,0);
            delete from db_sysarqcamp where codarq = 1010405;
            insert into db_sysarqcamp values(1010405,1010290,1,0);
            insert into db_sysarqcamp values(1010405,1010291,2,0);
            insert into db_sysarqcamp values(1010405,1010292,3,0);
            insert into db_sysarqcamp values(1010405,1010293,4,0);
            insert into db_sysarqcamp values(1010405,1010294,5,0);
            delete from db_sysprikey where codarq = 1010405;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010405,1010290,1,1010290);
            delete from db_sysforkey where codarq = 1010405 and referen = 0;
            insert into db_sysforkey values(1010405,1010291,1,1010404,0);
            delete from db_sysforkey where codarq = 1010405 and referen = 0;
            insert into db_sysforkey values(1010405,1010292,1,904,0);
            delete from db_sysforkey where codarq = 1010405 and referen = 0;
            insert into db_sysforkey values(1010405,1010293,1,75,0);
            insert into db_syssequencia values(1000813, 'iptutaxacalv_j152_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000813 where codarq = 1010405 and codcam = 1010290;
SQL;
        $this->execute($sql);
    }

    private function retornaDicionario()
    {
        $sql = <<<SQL
            delete from db_syssequencia where codsequencia in (1000812, 1000813);
            delete from db_sysforkey where codarq in (1010404, 1010405);
            delete from db_sysarqcamp where codarq in (1010404, 1010405);
            delete from db_sysarqmod where codarq in (1010404, 1010405);
            delete from db_sysarquivo where codarq in (1010404, 1010405);
            delete from db_syscampo where codcam in (1010286, 1010287, 1010288, 1010289, 1010290, 1010291, 1010292, 1010293, 1010294);
SQL;
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE cadastro.iptutaxacalv_j152_codigo_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;


            CREATE SEQUENCE cadastro.iptutaxanump_j151_codigo_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;


            -- TABELAS E ESTRUTURA

            -- Módulo: cadastro
            CREATE TABLE cadastro.iptutaxacalv(
            j152_codigo     int4 NOT NULL default 0,
            j152_iptutaxanump       int4 NOT NULL default 0,
            j152_codhis     int4 NOT NULL default 0,
            j152_receit     int4 NOT NULL default 0,
            j152_valor      float8 default 0,
            CONSTRAINT iptutaxacalv_codi_pk PRIMARY KEY (j152_codigo));


            -- Módulo: cadastro
            CREATE TABLE cadastro.iptutaxanump(
            j151_codigo     int4 NOT NULL default 0,
            j151_matric     int4 NOT NULL default 0,
            j151_numpre     int8 NOT NULL default 0,
            j151_iptucadtaxaexe     int4 default 0,
            CONSTRAINT iptutaxanump_codi_pk PRIMARY KEY (j151_codigo));


            -- CHAVE ESTRANGEIRA

            ALTER TABLE iptutaxacalv
            ADD CONSTRAINT iptutaxacalv_iptutaxanump_fk FOREIGN KEY (j152_iptutaxanump)
            REFERENCES iptutaxanump;

            ALTER TABLE iptutaxacalv
            ADD CONSTRAINT iptutaxacalv_codhis_fk FOREIGN KEY (j152_codhis)
            REFERENCES iptucalh;

            ALTER TABLE iptutaxacalv
            ADD CONSTRAINT iptutaxacalv_receit_fk FOREIGN KEY (j152_receit)
            REFERENCES tabrec;

            ALTER TABLE iptutaxanump
            ADD CONSTRAINT iptutaxanump_matric_fk FOREIGN KEY (j151_matric)
            REFERENCES iptubase;

            ALTER TABLE iptutaxanump
            ADD CONSTRAINT iptutaxanump_iptucadtaxaexe_fk FOREIGN KEY (j151_iptucadtaxaexe)
            REFERENCES iptucadtaxaexe;
SQL;

        $this->execute($sql);
    }

    private function removeTabela()
    {
        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS iptutaxacalv CASCADE;
            DROP TABLE IF EXISTS iptutaxanump CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS iptutaxacalv_j152_codigo_seq;
            DROP SEQUENCE IF EXISTS iptutaxanump_j151_codigo_seq;
SQL;
        $this->execute($sql);
    }

}
