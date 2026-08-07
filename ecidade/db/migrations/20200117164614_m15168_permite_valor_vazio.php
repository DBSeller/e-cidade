<?php

use Classes\PostgresMigration;

class M15168PermiteValorVazio extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->createDicionarioDados();
        $this->createEstruturaCampoPreenchimentoObrigatorio();
        $this->crateEstruturaTabelaImportacaorequisicaoInconsistencia();
        $this->createItemMenuImportacaoResultadoInconsistencia();
    }

    public function down()
    {

        $this->dropDicionarioDados();
        $this->dropEstruturaCampoPreenchimentoObrigatorio();
        $this->dropEstruturaTabelaImportacaorequisicaoInconsistencia();
        $this->dropItemMenuImportacaoResultadoInconsistencia();
    }

    public function createDicionarioDados()
    {
        $sql = <<<SQL

            -- criação do campo la25_preenchimentoobrigatorio na tabela lab_atributo
            insert into db_syscampo values(1010909,'la25_preenchimentoobrigatorio','bool','Campo para informar se o campo permite valor vazio','f', 'la25_preenchimentoobrigatorio',1,'f','f','f',5,'text','la25_preenchimentoobrigatorio');
            delete from db_sysarqcamp where codarq = 2899;
            insert into db_sysarqcamp values(2899,16489,1,1802);
            insert into db_sysarqcamp values(2899,16492,2,0);
            insert into db_sysarqcamp values(2899,16493,3,0);
            insert into db_sysarqcamp values(2899,16494,4,0);
            insert into db_sysarqcamp values(2899,16495,5,0);
            insert into db_sysarqcamp values(2899,1010782,6,0);
            insert into db_sysarqcamp values(2899,1010783,7,0);
            insert into db_sysarqcamp values(2899,1010909,8,0);

            -- criação da tabela lab_importacaorequisicaoinconsistencia
            insert into db_sysarquivo values (1010512, 'lab_importacaorequisicaoinconsistencia', 'Tabela para guardar as inconsistencias', '', '2020-01-23', 'lab_importacaorequisicaoinconsistencia', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (67,1010512);
            insert into db_syscampo values(1010954,'la64_sequencial','int4','Campo para guardar o sequencial ','0', 'la64_sequencial',10,'f','f','f',1,'text','la64_sequencial');
            insert into db_syscampo values(1010955,'la64_requisicao','int4','Campo para guardar a requisicao','0', 'la64_requisicao',10,'f','f','f',1,'text','la64_requisicao');
            insert into db_syscampo values(1010956,'la64_inconsistencias','varchar(100)','Campo para guardar as inconsistencias','', 'la64_inconsistencias',100,'f','t','f',0,'text','la64_inconsistencias');
            delete from db_sysarqcamp where codarq = 1010512;
            insert into db_sysarqcamp values(1010512,1010954,1,0);
            insert into db_sysarqcamp values(1010512,1010955,2,0);
            insert into db_sysarqcamp values(1010512,1010956,3,0);
            delete from db_sysprikey where codarq = 1010512;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010512,1010954,1,1010954);
            delete from db_sysforkey where codarq = 1010512 and referen = 0;
            insert into db_sysforkey values(1010512,1010955,1,2773,0);
            insert into db_sysindices values(1008516,'importacaorequisicaoinconsistencia_sequencial_in',1010512,'0');
            insert into db_syscadind values(1008516,1010954,1);
            insert into db_sysindices values(1008517,'importacaorequisicaoinconsistencia_requisicao_in',1010512,'0');
            insert into db_syscadind values(1008517,1010955,1);
            insert into db_syssequencia values(1000876, 'lab_importacaorequisicaoinconsistencia_la64_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000876 where codarq = 1010512 and codcam = 1010954;

SQL;
        $this->execute($sql);
    }

    public function createEstruturaCampoPreenchimentoObrigatorio()
    {

        $sql = <<<SQL

            alter table lab_atributo add column la25_preenchimentoobrigatorio boolean not null default false;
         
SQL;
        $this->execute($sql);

    }
    public function crateEstruturaTabelaImportacaorequisicaoInconsistencia()
    {

        $sql = <<<SQL

            CREATE SEQUENCE laboratorio.lab_importacaorequisicaoinconsistencia_la64_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE laboratorio.lab_importacaorequisicaoinconsistencia(
            la64_sequencial int4 default 0 NOT NULL,
            la64_requisicao int4 default 0 NOT NULL,
            la64_inconsistencias json NOT NULL,
            CONSTRAINT lab_importacaorequisicaoinconsistencia_sequ_pk PRIMARY KEY (la64_sequencial));

            ALTER TABLE laboratorio.lab_importacaorequisicaoinconsistencia
            ADD CONSTRAINT lab_importacaorequisicaoinconsistencia_requisicao_fk FOREIGN KEY (la64_requisicao)
            REFERENCES lab_requisicao;

            CREATE INDEX importacaorequisicaoinconsistencia_sequencial_in ON lab_importacaorequisicaoinconsistencia(la64_sequencial);
            CREATE INDEX importacaorequisicaoinconsistencia_requisicao_in ON lab_importacaorequisicaoinconsistencia(la64_requisicao);

SQL;
        $this->execute($sql);

    }

    public function createItemMenuImportacaoResultadoInconsistencia()
    {

        $sql = <<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228208 ,'Importação de Resultados dos Exames / Inconsistências' ,'Importação de Resultados dos Exames / Inconsistências' ,'lab2_inconsistenciasimportacaoresultado001.php' ,'1' ,'1' ,'Importação de Resultados dos Exames / Inconsistências' ,'false' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8171 ,228208 ,8 ,8167 );

SQL;
        $this->execute($sql);
    }

    public function dropDicionarioDados()
    {

        $sql = <<<SQL

            -- Exclusão do campo la25_preenchimentoobrigatorio na tabela lab_atributo
            delete from db_sysarqcamp where codarq = 2899;
            delete from db_syscampo where codcam = 1010909;

            -- Exclusão da tabela lab_importacaorequisicaoinconsistencia
            delete from db_sysarqmod where codarq = 1010512;
            delete from db_sysforkey where codarq = 1010512;
            delete from db_sysarqcamp where codarq = 1010512;
            delete from db_sysarquivo where codarq = 1010512;
            delete from db_syscampo where codcam in (1010954, 1010955, 1010956);
            delete from db_sysprikey where codarq = 1010512;
            delete from db_sysindices where codind in (1008516, 1008517);
            delete from db_syscadind where codind in (1008516, 1008517);
            delete from db_syssequencia where codsequencia = 1000876;
            
SQL;
        $this->execute($sql);
    }

    public function dropEstruturaCampoPreenchimentoObrigatorio()
    {

        $sql = <<<SQL
            
            alter table lab_atributo drop column la25_preenchimentoobrigatorio;

SQL;
        $this->execute($sql);
    }

    public function dropEstruturaTabelaImportacaorequisicaoInconsistencia()
    {

        $sql = <<<SQL
            
            DROP TABLE IF EXISTS laboratorio.lab_importacaorequisicaoinconsistencia CASCADE;
            DROP SEQUENCE IF EXISTS lab_importacaorequisicaoinconsistencia_la64_sequencial_seq;

SQL;
        $this->execute($sql);
    }

    public function dropItemMenuImportacaoResultadoInconsistencia()
    {

        $sql = <<<SQL

            delete from db_itensmenu where id_item = 228208;
            delete from db_menu where id_item_filho = 228208 AND modulo = 8167;            

SQL;
        $this->execute($sql);
    }
}
