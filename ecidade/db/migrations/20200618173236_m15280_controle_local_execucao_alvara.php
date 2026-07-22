<?php

use Classes\PostgresMigration;

class M15280ControleLocalExecucaoAlvara extends PostgresMigration
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

    public function upEstrutura()
    {
        $this->execute(
        <<<SQL
        -- Cria tabela formalocalvara
        CREATE TABLE issqn.formalocalvara (
            "q167_sequencial" INT NOT NULL,
            "q167_descricao" VARCHAR(50) NOT NULL,
            "q167_data_validade" DATE,
            CONSTRAINT "formalocalvara_pk" PRIMARY KEY ("q167_sequencial")
        );

        -- Cria campo do sequencial da formalocalvara na issbase
        ALTER TABLE issqn.issbase ADD COLUMN q167_formalocalvara INTEGER;
        ALTER TABLE issqn.issbase add CONSTRAINT "issbase_formalocalvara_fk" FOREIGN KEY ("q167_formalocalvara") REFERENCES "formalocalvara"("q167_sequencial");

        -- Cria Sequence
        CREATE SEQUENCE issqn.formalocalvara_q167_sequencial_seq;

        INSERT INTO issqn.formalocalvara (q167_sequencial,q167_descricao) VALUES ('1','Localizado');
        INSERT INTO issqn.formalocalvara (q167_sequencial,q167_descricao) VALUES ('2','Não Localizado');
        SELECT setval('issqn.formalocalvara_q167_sequencial_seq', 2, true);
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(
        <<<SQL
        -- Cadastro da Tabela
        INSERT INTO db_sysarquivo VALUES (1010590, 'formalocalvara', 'Tabela que guarda local de execução de atividade de uma inscrição.', 'q167', '2020-06-22', 'formalocalvara', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (3,1010590);

        -- Cadastro dos Campos
        INSERT INTO db_syscampo VALUES(1011613,'q167_sequencial','int4','Sequencial da tabela formalocalvara','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        INSERT INTO db_syscampo VALUES(1011614,'q167_descricao','varchar(50)','Descrição do Forma de Localização','', 'Descrição',50,'f','f','f',0,'text','Descrição');
        INSERT INTO db_syscampo VALUES(1011616,'q167_data_validade','date','Data de Validade','', 'Data de Validade',10,'f','f','f',1,'text','Data de Validade');

        INSERT INTO db_syscampo VALUES(1011615,'q167_formalocalvara','int4','Sequencial da tabela formalocalvara','0', 'Forma de Localização',10,'f','f','f',1,'text','Forma de Localização');

        -- Cria chave primária na Tabela formalocalvara
        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010590,1011613,1,1011613);

        -- Atribui os Campos na Tabela
        INSERT INTO db_sysarqcamp VALUES(1010590,1011613,1,0);
        -- insert into db_sysarqcamp values(1010590,1011613,1,1000949);

        INSERT INTO db_sysarqcamp VALUES(1010590,1011614,2,0);
        INSERT INTO db_sysarqcamp VALUES(1010590,1011616,3,0);

        -- Atribui os Campos na Tabela issbase
        INSERT INTO db_sysarqcamp VALUES(41,1011615,16,0);

        -- Cria FK na tabela issbase que referencia a tabela formalocalvara
        insert into db_sysforkey values(41,1011615,1,1010590,0);

        -- Cadastro de Sequencia
        INSERT INTO db_syssequencia VALUES(1000949, 'formalocalvara_q167_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        UPDATE db_sysarqcamp SET codsequencia = 1000949 WHERE codarq = 1010590 AND codcam = 1011613;

        -- Cria Menu de cadastro no módulo ISSQN
        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228265 ,'Forma de Localização Alvará' ,'Controle Forma de Localização' ,'cad1_formalocalvara.php' ,'1' ,'1' ,'Cadastro de Forma de Localização de atividade do alvará' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228265 ,291 ,40 );

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(
        <<<SQL
        -- Remove campo que referencia tabela formalocalvara
        ALTER TABLE issqn.issbase DROP COLUMN q167_formalocalvara;

        -- Remove tabela formalocalvara
        DROP TABLE IF EXISTS issqn.formalocalvara;

        -- Remove Sequence
        DROP SEQUENCE issqn.formalocalvara_q167_sequencial_seq;
SQL
        );
    }


    public function downDicionario()
    {
        $this->execute(
        <<<SQL
        -- Remove chage estrangeira da tabela issbase
        DELETE FROM db_sysforkey WHERE codcam = 1011615;

        -- Remove Campos
        DELETE FROM db_sysarqcamp WHERE codcam IN (1011613, 1011614, 1011615, 1011616);
        DELETE FROM db_sysprikey WHERE codcam = 1011613;
        DELETE FROM db_syscampo WHERE codcam IN (1011613, 1011614, 1011615, 1011616);

        -- Remove Sequence
        DELETE FROM db_syssequencia WHERE codsequencia = 1000949;

        -- Remove Tabela
        DELETE FROM db_sysarqmod WHERE codarq = 1010590;
        DELETE FROM db_sysarquivo WHERE codarq = 1010590;

        -- Remove Menu
        DELETE FROM db_itensmenu WHERE id_item IN (228265);
        DELETE FROM db_menu WHERE id_item_filho = 228265 AND modulo = 40;
SQL
        );
    }
}
