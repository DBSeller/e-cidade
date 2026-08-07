<?php

use Classes\PostgresMigration;

class M13980IntegracaoLuckmann extends PostgresMigration
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
        $this->createEstrutura();
    }    
         
    public function down()
    {    
        
        $this->dropDicionarioDados();
        $this->dropEstrutura();
    }    
         
    public function createDicionarioDados()
    {    
        
        $sql = <<<SQL
            
            insert into db_syscampo values(1010694,'la49_integracao','int4','Campo integracao','0', 'la49_integracao',10,'t','f','f',1,'text','la49_integracao');
            delete from db_sysarqcamp where codarq = 2909;
            insert into db_sysarqcamp values(2909,16575,1,1818);
            insert into db_sysarqcamp values(2909,16576,2,0);
            insert into db_sysarqcamp values(2909,17925,3,0);
            insert into db_sysarqcamp values(2909,1010672,4,0);
            insert into db_sysarqcamp values(2909,1010694,5,0); 
SQL;
        $this->execute($sql);
    }    
         
    public function createEstrutura()
    {    
        $sql = <<<SQL
        
            alter table laboratorio.lab_parametros add column la49_integracao int4 null;
            
SQL;
        $this->execute($sql);
         
    }    
         
    public function dropDicionarioDados()
    {    
        
        $sql = <<<SQL

            delete from db_sysarqcamp where codcam = 1010694;
            delete from db_syscampo where codcam = 1010694;
SQL;
        $this->execute($sql);
    }    
         
    public function dropEstrutura()
    {    
        
        $sql = <<<SQL
            
            alter table laboratorio.lab_parametros drop column la49_integracao;
SQL;
        $this->execute($sql);                                                                                        
    } 
}