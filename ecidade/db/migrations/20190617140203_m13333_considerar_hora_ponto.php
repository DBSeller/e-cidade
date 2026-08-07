<?php

use Classes\PostgresMigration;

class M13333ConsiderarHoraPonto extends PostgresMigration
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
        $this->createTableDicionarioDados();
        $this->createEstrutura();
    }

    public function down()
    {
        $this->dropTableDicionarioDados();
        $this->dropEstrutura();
    }

    public function createTableDicionarioDados()
    {
        $sql = <<<SQL
        
        insert into db_syscampo values(1010556,'rh207_considerarhorariotrabalhado','bool','Campo para identificar para considerar as horas trabalhadas','f', 'Considerar o horário trabalhado',1,'f','f','f',5,'text','Considerar o horário trabalhado');
        insert into db_sysarqcamp values(1010211,1010556,1,0);
SQL;
        $this->execute($sql);
    }

    public function createEstrutura()
    {
        $sql = <<<SQL

        alter table recursoshumanos.pontoeletronicoevento add column rh207_considerarhorariotrabalhado boolean default false;

SQL;
        $this->execute($sql);
    }

    public function dropTableDicionarioDados()
    {
        $sql = <<<SQL
        
        delete from db_sysarqcamp where codarq = 1010211;
        delete from db_syscampo where codcam = 1010556;
        
SQL;
        $this->execute($sql);

    }

    public function dropEstrutura()
    {
        $sql = <<<SQL

        alter table recursoshumanos.pontoeletronicoevento drop column rh207_considerarhorariotrabalhado;

SQL;
        $this->execute($sql);
    }
   
}
