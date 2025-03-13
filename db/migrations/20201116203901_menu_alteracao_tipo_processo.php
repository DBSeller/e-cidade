<?php

use Classes\PostgresMigration;

class MenuAlteracaoTipoProcesso extends PostgresMigration
{
   public function up(){
       $sql = <<<SQL
     insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228338 ,'Alterar tipo de processo' ,'Alterar tipo de processo' ,'prot4_tipo_processo.001.php' ,'1' ,'1' ,'Alterar tipo de processo exemplo (manual para eletrônico) ' ,'true' );
     insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 2162 ,228338 ,4 ,604 );
SQL;

       $this->execute($sql);
   }

   public function down(){
       $sql = <<<SQL
        DELETE FROM db_itensmenu WHERE id_item = 228338;
        DELETE FROM db_menu WHERE  id_item_filho = 228338;

SQL;
       $this->execute($sql);

   }
}
