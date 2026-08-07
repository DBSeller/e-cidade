<?php

use Classes\PostgresMigration;

class M10397 extends PostgresMigration
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
        $this->execute("
        
        insert into db_syscampo values(1009666,'j146_data','date','Data da rejeição','null', 'Data da rejeição',10,'f','f','f',1,'text','Data da rejeição');
        insert into db_syscampo values(1009667,'j144_matriculanova','bool','Matricula Nova','f', 'Matricula Nova',1,'f','f','f',5,'text','');
      
        insert into db_sysarqcamp values(1010219,1009667,6,0);
        insert into db_sysarqcamp values(1010254,1009666,3,0);

        ALTER TABLE  atualizacaoiptuschemamatricula  add  j144_matriculanova boolean default false;
        ALTER TABLE  atualizacaoiptuschemamotivorejeicao add   j146_data date;

        ");


    }

    public function down()
    {

        $this->execute("
                 DELETE  FROM db_sysarqcamp WHERE  codcam IN (1009666, 1009667 );
                 
                 DELETE  FROM db_syscampo WHERE  codcam IN (1009666, 1009667 );
                  
                 ALTER TABLE  atualizacaoiptuschemamatricula  drop  j144_matriculanova ;
                 ALTER TABLE  atualizacaoiptuschemamotivorejeicao drop   j146_data ;
        ");

    }

}
