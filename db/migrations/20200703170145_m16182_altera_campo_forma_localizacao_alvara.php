<?php

use Classes\PostgresMigration;

class M16182AlteraCampoFormaLocalizacaoAlvara extends PostgresMigration
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
            -- Remove os vinculos e o campo q02_formalocalvara do dicionario de dados
            delete from db_sysforkey where codcam = 1011615;
            delete from db_sysarqcamp where codcam = 1011615;
            delete from db_syscampodef where codcam = 1011615;
            delete from db_syscampodep where codcam = 1011615;
            delete from db_syscampo where codcam = 1011615;

            -- Cria campo q02_formalocalvara
            insert into db_syscampo values(1011655,'q02_formalocalvara','int4','Sequencial da tabela formalocalvara','0', 'Forma de Localização',10,'f','f','f',1,'text','Forma de Localização');

            -- Vincula o campo q02_formalocalvara na tabela issbase
            insert into db_sysarqcamp values(41,1011655,16,0);
            insert into db_sysforkey values(41,1011655,1,1010590,0);

SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(
        <<<SQL
        ALTER TABLE issqn.issbase DROP COLUMN q167_formalocalvara;
        ALTER TABLE issqn.issbase ADD COLUMN q02_formalocalvara INTEGER DEFAULT 1;

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(
        <<<SQL
            delete from db_sysforkey where codcam = 1011655;
            delete from db_sysarqcamp where codcam = 1011655;
            delete from db_syscampodef where codcam = 1011655;
            delete from db_syscampodep where codcam = 1011655;
            delete from db_syscampo where codcam = 1011655;

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(
        <<<SQL
        ALTER TABLE issqn.issbase RENAME COLUMN q02_formalocalvara TO q167_formalocalvara;

SQL
        );
    }

}
