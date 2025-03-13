<?php

use Classes\PostgresMigration;

class CivitasInfosComplementar extends PostgresMigration
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
        $sSql = "
            insert into  db_syscampo values(1009638,'codigo_api','varchar(200)','Codigo unico recebido pela webservice do civitas na carga de dados','0', '',200,'f','f','f',0,'text','');
            insert into db_sysarquivo values (1010260, 'civitasinfoscomplementar', 'Tabela que guarda informacoes complementares. ', '', '2018-02-15', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (2,1010260);
            delete from db_sysarqcamp where codarq = 1010260;
            insert into db_sysarqcamp values(1010260,991,1,0);
            insert into db_sysarqcamp values(1010260,1009638,2,0);
            insert into db_sysindices values(1008254,'matricula_indice',1010260,'1');
            insert into db_syscadind values(1008254,991,1);
            
            CREATE TABLE IF NOT EXISTS cadastro.civitasinfoscomplementar( matricula integer not null,  codigo_api varchar(200)) ;
            
        ";

        $this->execute($sSql);

    }

    public function down()
    {

        $sSql = "
          
             DELETE FROM  db_sysarqcamp WHERE codarq = 1010260;
             DELETE FROM  db_syscampo  WHERE  codcam   = 1009638;
             DELETE  FROM db_sysarqmod  WHERE  codmod = 2 and  codarq = 1010260 ;
             DELETE FROM  db_sysarquivo  WHERE  codarq = 1010260; 
             
             
             DELETE FROM db_sysindices  where codind = 1008254;
             DELETE FROM db_syscadind   where codind = 1008254;
      
        ";

        $this->execute($sSql);
    }
}
