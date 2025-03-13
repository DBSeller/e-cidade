<?php

use Classes\PostgresMigration;

class M18314RelatorioConsignacoesFolha extends PostgresMigration
{

    public function up()
    {

      $sql = <<<SQL

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228517 ,'Retenções e Consignações Folha' ,'Retenções e Consignações Folha' ,'pes2_retconsigfolha001.php' ,'1' ,'1' ,'Retenções e Consignações Folha' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4189 ,228517 ,15 ,209 );


SQL;
      $this->execute($sql);
    }


    public function down()
    {

      $sql = <<<SQL

        delete from db_menu where id_item_filho = 228517 AND modulo = 209;
        delete from db_itensmenu where id_item = 228517;
SQL;
      $this->execute($sql);
    }
}
