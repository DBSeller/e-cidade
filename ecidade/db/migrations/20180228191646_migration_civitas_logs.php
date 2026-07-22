<?php

use Classes\PostgresMigration;

class MigrationCivitasLogs extends PostgresMigration
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
       $sSql =  "
       
            insert into db_syscampo values(1009649,'rq01_descricao','text','Campo para guardar descrição/logs do civitas','', '',1000,'f','t','f',0,'text','');
            
            delete from db_sysarqcamp where codarq = 1010236;
            
            insert into db_sysarqcamp values(1010236, 1009514, 1, 1000698);
            insert into db_sysarqcamp values(1010236, 1009515, 2, 0);
            insert into db_sysarqcamp values(1010236, 1009516, 3, 0);
            insert into db_sysarqcamp values(1010236, 1009649, 4, 0);
           
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10508 ,'Consulta situação da Importação do civitas' ,'Consulta situação da Importação do Recadastramento' ,'cad4_situacaocivitasrequisicao001.php' ,'1' ,'1' ,'Tela de consulta de Requisição do civitas' ,'true' );
            delete from db_menu where id_item_filho = 10508 AND modulo = 578;
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,10508 ,185 ,578 ); 
         
           ALTER TABLE requisicaocivitas  ADD COLUMN rq01_descricao  text;
      
       ";

       $this->execute($sSql);
    }


    public function down()
    {
       $sSql =  "
            DELETE FROM  db_sysarqcamp WHERE codarq = 1010236;
             
            DELETE FROM  db_syscampo  WHERE  codcam   = 1009649;
             
            DELETE FROM db_sysarqcamp WHERE codarq = 1010236 AND codcam =1009515; 
            DELETE FROM db_sysarqcamp WHERE codarq = 1010236 AND codcam = 1009516; 
            DELETE FROM db_sysarqcamp WHERE codarq = 1010236 AND codcam = 1009649;
                        
            DELETE FROM db_itensmenu WHERE  id_item = 10508;
              
            DELETE FROM db_menu  WHERE id_item = 31 AND  id_item_filho = 10508 AND  menusequencia = 185 AND  modulo = 578;
            
            ALTER TABLE requisicaocivitas  DROP COLUMN rq01_descricao;
       ";

       $this->execute($sSql);
    }
}
