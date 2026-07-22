<?php

use Classes\PostgresMigration;

class M18780AdiocionaCampoAcordoItem extends PostgresMigration
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
        $this->execute("
       insert into db_syscampo 
       values(1013465,'ac20_servicoquantidade','bool','Este Campo define se o tipo de item do acordo, quando serviço, terá sua forma de controle por quantidade, ou por valor total.','f', 'Serviço Controlado por Quantidade',1,'f','f','f',5,'text','Serviço Controlado por Quantidade');
       
       insert into db_sysarqcamp 
       values(2837,1013465,12,0); 
       
       alter table acordoitem 
         add column ac20_servicoquantidade boolean default false;");
    }

    public function down()
    {  
        $this->execute("
        delete 
          from db_sysarqcamp 
         where codcam = 1013465; 

        delete 
          from db_syscampo 
         where codcam = 1013465;

        alter table acordoitem 
         drop column ac20_servicoquantidade;
         ");

    }
    
    

}
