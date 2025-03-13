<?php

use Classes\PostgresMigration;

class M10397NovaMatriculaColuna extends PostgresMigration
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
        insert into db_syscampo values(1009669,'nova_matricula','int8','Campo para guardar o vinculo da matricula temporaria com matricula oficial gerada','0', '',11,'t','f','f',1,'text','');
        insert into db_sysarqcamp values(1010260,1009669,3,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010260,991,1,991);
        
        
        ALTER TABLE civitasinfoscomplementar ALTER COLUMN matricula type bigint;
        
        ALTER TABLE civitasinfoscomplementar ADD PRIMARY KEY (matricula);
        
        ALTER TABLE civitasinfoscomplementar ADD nova_matricula  integer;
      ");



    }

    public function down()
    {

       $this->execute("
             DELETE FROM  db_sysarqcamp  WHERE  codcam =  1009669;
             DELETE FROM  db_syscampo WHERE codcam = 1009669;
             DELETE FROM  db_sysprikey  WHERE codarq = 1010260 AND codcam = 991 AND sequen = 1;  
             
             ALTER TABLE civitasinfoscomplementar  DROP CONSTRAINT matricula;
             
             ALTER TABLE civitasinfoscomplementar DROP nova_matricula ;     
             ALTER TABLE civitasinfoscomplementar ALTER COLUMN matricula type integer;
       ");

    }
}
