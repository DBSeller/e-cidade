<?php

use Classes\PostgresMigration;

class M16071CriandoCamposDoProcessoNaTabelaItbi extends PostgresMigration
{
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
    $sql = <<<SQL
        insert into db_syscampo values(1011710,'it01_processo','varchar(255)','Processo','', 'Processo',255,'t','f','f',0,'text','Processo');
        insert into db_syscampo values(1011711,'it01_tituprocesso','varchar(255)','Descreve o titular do processo','', 'Titular Do Processo',255,'t','t','f',0,'text','Titular Do Processo');
        insert into db_syscampo values(1011712,'it01_dtprocesso','date','Descreve a data do processo.','null', 'Data do Processo',10,'t','f','f',1,'text','Data do Processo');

        insert into db_sysarqcamp values(792,1011712,19,0);
        insert into db_sysarqcamp values(792,1011711,20,0);
        insert into db_sysarqcamp values(792,1011710,21,0);
SQL;
    $this->execute($sql);
   }

   public function downDicionario()
   {
     $sql = <<<SQL
        delete from db_sysarqcamp where codcam in(1011710, 1011711, 1011712);
        delete from db_syscampo where codcam in(1011710, 1011711, 1011712);
SQL;
        $this->execute($sql);
   }

   public function upEstrutura()
   {
       $sql = <<<SQL
        alter table itbi.itbi add column it01_processo varchar(255);
        alter table itbi.itbi add column it01_tituprocesso varchar(255);
        alter table itbi.itbi add column it01_dtprocesso date DEFAULT null;
SQL;
        $this->execute($sql);
   }

   public function downEstrutura()
   {
       $sql = <<<SQL
        alter table itbi.itbi drop column it01_processo;
        alter table itbi.itbi drop column it01_tituprocesso;
        alter table itbi.itbi drop column it01_dtprocesso;

SQL;
        $this->execute($sql);
   }


   
}
