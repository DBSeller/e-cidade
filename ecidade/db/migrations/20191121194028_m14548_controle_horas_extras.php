<?php

use Classes\PostgresMigration;

class M14548ControleHorasExtras extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionarioDadosControleHorasExtras();
        $this->adicionaDicionarioDadosControleRubricas();
        $this->adicionaDicionarioDadosControleMatriculas();


        $this->criaTabelas();
    }

    public function down()
    {
        $this->removeDicionarioDadosControleMatriculas();
        $this->removeDicionarioDadosControleRubricas();
        $this->removeDicionarioDadosControleHorasExtras();

        $this->removeTabelas();
    }

    private function adicionaDicionarioDadosControleHorasExtras()
    {
        $sql = "
            insert into db_sysarquivo values (1010477, 'controlehorasextras', 'Armazena informações pertinentes a liberação de horas extras mensais para os servidores.', 'rh232', '2019-11-21', 'controlehorasextras', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010477);
            insert into db_syscampo values(1010785,'rh232_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010786,'rh232_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1010787,'rh232_selecao','int4','Seleção','0', 'Seleção',10,'t','f','f',1,'text','Seleção');
            insert into db_syscampo values(1010788,'rh232_ano','int4','Ano','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1010789,'rh232_mes','int4','Mês','0', 'Mês',10,'f','f','f',1,'text','Mês');
            insert into db_sysarqcamp values(1010477,1010785,1,0);
            insert into db_sysarqcamp values(1010477,1010786,2,0);
            insert into db_sysarqcamp values(1010477,1010787,3,0);
            insert into db_sysarqcamp values(1010477,1010788,4,0);
            insert into db_sysarqcamp values(1010477,1010789,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010477,1010785,1,1010785);
            insert into db_sysforkey values(1010477,1010787,1,591,0);
            insert into db_sysforkey values(1010477,1010786,2,591,0);
            insert into db_sysindices values(1008496,'controlehorasextras_rh232_sequencial_in',1010477,'0');
            insert into db_syscadind values(1008496,1010785,1);
            insert into db_syssequencia values(1000852, 'controlehorasextras_rh232_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000852 where codarq = 1010477 and codcam = 1010785;
        ";
        $this->execute($sql);
    }

    private function removeDicionarioDadosControleHorasExtras()
    {
        $sql = "
            delete from db_syssequencia where codsequencia = 1000852;
            delete from db_syscadind where codind = 1008496;
            delete from db_sysindices where codind = 1008496; 
            delete from db_sysforkey where codarq = 1010477;
            delete from db_sysprikey where codarq = 1010477;
            delete from db_sysarqcamp where codarq = 1010477;
            delete from db_syscampo where codcam in (1010785, 1010786, 1010787, 1010788, 1010789);
            delete from db_sysarqmod where codarq = 1010477;
            delete from db_sysarquivo where codarq = 1010477;
        ";
        $this->execute($sql);
    }

    private function adicionaDicionarioDadosControleRubricas()
    {
        $sql = "
            insert into db_sysarquivo values (1010478, 'controlehorasextrasrubricas', 'Armazena informações das rubricas liberadas no controle de horas extras.', 'rh233', '2019-11-22', 'controlehorasextrasrubricas', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010478);
            insert into db_syscampo values(1010790,'rh233_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010791,'rh233_controlehorasextras','int4','Controle Horas Extras','0', 'Controle Horas Extras',10,'f','f','f',1,'text','Controle Horas Extras');
            insert into db_syscampo values(1010792,'rh233_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1010793,'rh233_rubrica','int4','Rubrica','0', 'Rubrica',10,'f','f','f',1,'text','Rubrica');
            insert into db_syscampo values(1010794,'rh233_permite_exclusao','bool','Permite exclusão','f', 'Permite exclusão',1,'f','f','f',5,'text','Permite exclusão');
            insert into db_syscampodef values(1010794,'t','');
            insert into db_sysarqcamp values(1010478,1010790,1,0);
            insert into db_sysarqcamp values(1010478,1010791,2,0);
            insert into db_sysarqcamp values(1010478,1010792,3,0);
            insert into db_sysarqcamp values(1010478,1010793,4,0);
            insert into db_sysarqcamp values(1010478,1010794,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010478,1010790,1,1010790);
            insert into db_sysforkey values(1010478,1010793,1,1177,0);
            insert into db_sysforkey values(1010478,1010792,2,1177,0);
            insert into db_sysforkey values(1010478,1010791,1,1010477,0);
            insert into db_sysindices values(1008498,'controlehorasextrasrubricas_rh233_sequencial_in',1010478,'0');
            insert into db_syscadind values(1008498,1010790,1);
            insert into db_syssequencia values(1000853, 'controlehorasextrasrubricas_rh233_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000853 where codarq = 1010478 and codcam = 1010790;
            update db_syscampo set nomecam = 'rh233_rubrica', conteudo = 'char(4)', descricao = 'Rubrica', valorinicial = '0', rotulo = 'Rubrica', nulo = 'f', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Rubrica' where codcam = 1010793;
        ";
        $this->execute($sql);
    }

    private function removeDicionarioDadosControleRubricas()
    {
        $sql = "
            delete from db_syssequencia where codsequencia = 1000853;
            delete from db_syscadind where codind = 1008498;
            delete from db_sysindices where codind = 1008498;
            delete from db_sysforkey where codarq = 1010478;
            delete from db_sysprikey where codarq = 1010478;
            delete from db_sysarqcamp where codarq = 1010478;
            delete from db_syscampodef where codcam in (1010790, 1010791, 1010792, 1010793, 1010794);
            delete from db_syscampo where codcam in (1010790, 1010791, 1010792, 1010793, 1010794);
            delete from db_sysarqmod where codarq = 1010478;
            delete from db_sysarquivo where codarq = 1010478;
        ";

        $this->execute($sql);
    }

    private function adicionaDicionarioDadosControleMatriculas()
    {
        $sql = "
            insert into db_sysarquivo values (1010480, 'controlehorasextrasmatriculas', 'Armazena o período e a quantidade de horas extras liberadas para o servidor.', 'rh234', '2019-11-22', 'controlehorasextrasmatriculas', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010480);
            insert into db_syscampo values(1010795,'rh234_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010796,'rh234_matricula','int4','Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1010797,'rh234_ano','int4','Ano','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1010798,'rh234_mes','int4','Mês','0', 'Mês',10,'f','f','f',1,'text','Mês');
            insert into db_syscampo values(1010800,'rh234_horas_liberadas','varchar(255)','Horas Extras Liberadas','', 'Horas Extras Liberadas',255,'f','f','f',0,'text','Horas Extras Liberadas');
            insert into db_syscampo values(1010801,'rh234_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_sysarqcamp values(1010480,1010795,1,0);
            insert into db_sysarqcamp values(1010480,1010801,2,0);
            insert into db_sysarqcamp values(1010480,1010796,3,0);
            insert into db_sysarqcamp values(1010480,1010797,4,0);
            insert into db_sysarqcamp values(1010480,1010798,5,0);
            insert into db_sysarqcamp values(1010480,1010800,6,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010480,1010795,1,1010795);
            insert into db_sysforkey values(1010480,1010796,1,1153,0);
            insert into db_sysindices values(1008499,'controlehorasextrasmatriculas_rh234_sequencial_in',1010480,'0');
            insert into db_syscadind values(1008499,1010795,1);
            insert into db_syssequencia values(1000854, 'controlehorasextrasmatriculas_rh234_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000854 where codarq = 1010480 and codcam = 1010795;
        ";

        $this->execute($sql);
    }

    private function removeDicionarioDadosControleMatriculas()
    {
        $sql = "
            delete from db_syssequencia where codsequencia = 1000854;
            delete from db_syscadind where codind = 1008499;
            delete from db_sysindices where codind = 1008499;
            delete from db_sysforkey where codarq = 1010480;
            delete from db_sysprikey where codarq = 1010480;
            delete from db_sysarqcamp where codarq = 1010480;
            delete from db_syscampo where codcam in (1010795, 1010796, 1010797, 1010798, 1010800, 1010801);
            delete from db_sysarqmod where codarq = 1010480;
            delete from db_sysarquivo where codarq = 1010480;
        ";

        $this->execute($sql);
    }

    private function criaTabelas()
    {
        $sql = "
            -- SEQUENCES
            CREATE SEQUENCE pessoal.controlehorasextrasrubricas_rh233_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE SEQUENCE pessoal.controlehorasextras_rh232_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE SEQUENCE pessoal.controlehorasextrasmatriculas_rh234_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            -- TABLES
            CREATE TABLE pessoal.controlehorasextrasrubricas(
            rh233_sequencial		int4 NOT NULL default nextval('controlehorasextrasrubricas_rh233_sequencial_seq'),
            rh233_controlehorasextras		int4 NOT NULL default 0,
            rh233_instituicao		int4 NOT NULL default 0,
            rh233_rubrica		char(4) NOT NULL default 0,
            rh233_permite_exclusao		bool default 'f',
            CONSTRAINT controlehorasextrasrubricas_sequ_pk PRIMARY KEY (rh233_sequencial));
            
            CREATE TABLE pessoal.controlehorasextras(
            rh232_sequencial		int4 NOT NULL default nextval('controlehorasextras_rh232_sequencial_seq'),
            rh232_instituicao		int4 NOT NULL default 0,
            rh232_selecao		int4  default 0,
            rh232_ano		int4 NOT NULL default 0,
            rh232_mes		int4 default 0,
            CONSTRAINT controlehorasextras_sequ_pk PRIMARY KEY (rh232_sequencial));
            
            CREATE TABLE pessoal.controlehorasextrasmatriculas(
            rh234_sequencial		int4 NOT NULL default nextval('controlehorasextrasmatriculas_rh234_sequencial_seq'),
            rh234_instituicao		int4 NOT NULL default 0,
            rh234_matricula		int4 NOT NULL default 0,
            rh234_ano		int4 NOT NULL default 0,
            rh234_mes		int4 NOT NULL default 0,
            rh234_horas_liberadas		varchar(255) ,
            CONSTRAINT controlehorasextrasmatriculas_sequ_pk PRIMARY KEY (rh234_sequencial));
            
            -- FKS
            ALTER TABLE controlehorasextrasrubricas
            ADD CONSTRAINT controlehorasextrasrubricas_rubrica_instituicao_fk FOREIGN KEY (rh233_rubrica,rh233_instituicao)
            REFERENCES rhrubricas;
            
            ALTER TABLE controlehorasextrasrubricas
            ADD CONSTRAINT controlehorasextrasrubricas_controlehorasextras_fk FOREIGN KEY (rh233_controlehorasextras)
            REFERENCES controlehorasextras;
            
            ALTER TABLE controlehorasextras
            ADD CONSTRAINT controlehorasextras_selecao_instituicao_fk FOREIGN KEY (rh232_selecao,rh232_instituicao)
            REFERENCES selecao;
            
            ALTER TABLE controlehorasextrasmatriculas
            ADD CONSTRAINT controlehorasextrasmatriculas_matricula_fk FOREIGN KEY (rh234_matricula)
            REFERENCES rhpessoal;
            
            -- INDEXES
            CREATE  INDEX controlehorasextrasrubricas_rh233_sequencial_in ON controlehorasextrasrubricas(rh233_sequencial);
            CREATE  INDEX controlehorasextras_rh232_sequencial_in ON controlehorasextras(rh232_sequencial);
            CREATE  INDEX controlehorasextrasmatriculas_rh234_sequencial_in ON controlehorasextrasmatriculas(rh234_sequencial);
        ";
        $this->execute($sql);
    }

    private function removeTabelas()
    {
        $sql = "
            DROP TABLE IF EXISTS controlehorasextrasrubricas;
            DROP TABLE IF EXISTS controlehorasextrasmatriculas;
            DROP TABLE IF EXISTS controlehorasextras;

            DROP SEQUENCE IF EXISTS controlehorasextrasrubricas_rh233_sequencial_seq;
            DROP SEQUENCE IF EXISTS controlehorasextrasmatriculas_rh234_sequencial_seq;
            DROP SEQUENCE IF EXISTS controlehorasextras_rh232_sequencial_seq;            
        ";
        $this->execute($sql);
    }
}
