<?php

use Classes\PostgresMigration;

class M17791AdequacaoAnexoRreoXiv extends PostgresMigration
{

    public function up()
    {
      $sSql = <<<SQL

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228478 ,'[Ed 11] Anexo XIV - Dem. Simplificado do RREO' ,'[Ed 11] Anexo XIV - Dem. Simplificado do RREO' ,'con2_lrfanexoxviii0001.php' ,'1' ,'1' ,' Anexo XIV - Demostrativo simplificado' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8033 ,228478 ,19 ,209 );

        update orcparamseqorcparamseqcoluna set o116_formula = '' where o116_codparamrel = 245 and o116_codseq = 68;


SQL;

      $this->execute($sSql);
    }



    public function down()
    {

        $sSql = <<<SQL

         delete from db_menu where id_item = 8033 and id_item_filho = 228478;
         delete from db_itensmenu where id_item = 228478;

SQL;

              $this->execute($sSql);
    }

}
