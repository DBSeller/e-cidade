<?php

use Classes\PostgresMigration;

class M13950MriacaoMenu extends PostgresMigration
{
  public function up()
  {
      $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228151 ,'Balancete da MSC' ,'Balancete da MSC' ,'con2_balancetemsc001.php' ,'1' ,'1' ,'Balancete da MSC' ,'false' );
update db_itensmenu set id_item = 228151 , descricao = 'Balancete da MSC' , help = 'Balancete da MSC' , funcao = 'con2_balancetemsc001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Balancete da MSC' , libcliente = 'true' where id_item = 228151;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4065 ,228151 ,13 ,209 );
SQL;
      $this->execute($sql);
  }


  public function down()
  {
      $sql = <<<SQL
delete from db_menu where id_item_filho = 228151 AND modulo = 209;
delete from db_itensmenu where id_item = 228151; 
SQL;
      $this->execute($sql);
  }


}
