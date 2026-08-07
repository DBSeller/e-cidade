<?php

use Classes\PostgresMigration;

class M14073TabelasWorkflow extends PostgresMigration
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
        $this->upTabela();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downTabela();
        $this->downDicionario();
    }

    private function upTabela()
    {
        $this->execute(<<<SQL

            CREATE TABLE configuracoes.workflowmodulo(

                db173_workflow integer NOT NULL,
                db173_modulo integer NOT NULL,
                PRIMARY KEY (db173_workflow, db173_modulo)

            );

            ALTER TABLE workflowmodulo
         ADD CONSTRAINT workflowmodulo_workflow_fk FOREIGN KEY (db173_workflow)
             REFERENCES workflow;

            ALTER TABLE workflowmodulo
         ADD CONSTRAINT workflowmodulo_modulo_fk FOREIGN KEY (db173_modulo)
             REFERENCES db_sysmodulo;
SQL
        );
    }

    private function downTabela()
    {
        $this->execute(<<<SQL

            DROP TABLE workflowmodulo;

SQL
        );
    }

    private function upDicionario()
    {

         $this->execute(<<<SQL

            insert into db_sysarquivo values (1010467, 'workflowmodulo', 'Tabela de ligação entre workflow e modulo do sistema', 'db173', '2019-09-04', 'Workflow Módulo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010467);
            insert into db_syscampo values(1010698,'db173_workflow','int4','Coluna que guarda o id do workflow','0', 'Workflow',1,'f','f','f',1,'text','Workflow');
            insert into db_syscampo values(1010699,'db173_modulo','int4','Coluna que o módulo do sistema','0', 'Modulo ',1,'f','f','f',1,'text','Modulo ');
            delete from db_sysarqcamp where codarq = 1010467;
            insert into db_sysarqcamp values(1010467,1010698,1,0);
            insert into db_sysarqcamp values(1010467,1010699,2,0);
            delete from db_sysprikey where codarq = 1010467;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010467,1010699,1,1010698);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010467,1010698,2,1010698);
            delete from db_sysforkey where codarq = 1010467 and referen = 0;
            delete from db_sysforkey where codarq = 1010467 and referen = 0;
            insert into db_sysforkey values(1010467,1010698,1,3155,0);
            delete from db_sysforkey where codarq = 1010467 and referen = 0;
            insert into db_sysforkey values(1010467,1010699,1,148,0);

SQL
        );

    }

    private function downDicionario()
    {

         $this->execute(<<<SQL

            DELETE FROM db_sysforkey where codarq = 1010467;
            DELETE FROM db_sysprikey where codarq = 1010467;
            DELETE FROM db_sysarqcamp where codarq = 1010467;
            DELETE FROM db_syscampo where codcam in (1010698, 1010699);
            DELETE FROM db_sysarqmod where codarq = 1010467;
            DELETE FROM db_sysarquivo where codarq = 1010467;

SQL
        );

    }
}
