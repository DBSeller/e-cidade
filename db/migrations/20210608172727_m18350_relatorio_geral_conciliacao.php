<?php

use Classes\PostgresMigration;

class M18350RelatorioGeralConciliacao extends PostgresMigration
{

    public function up()
    {
        $sSql = <<<SQL


insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228519 ,'Relatório Gerencial Conciliação' ,'Relatório Gerencial Conciliação' ,'cai2_relatoriogeralconciliacao001.php' ,'1' ,'1' ,'Relatório Gerencial Conciliação' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3951 ,228519 ,13 ,39 );

SQL;
      $this->execute($sSql);

    }




    public function down()
    {
        $sSql = <<<SQL
delete from db_menu where id_item_filho = 228519 AND modulo = 39;
delete from db_itensmenu where id_item = 228519;


SQL;
      $this->execute($sSql);

    }





}
