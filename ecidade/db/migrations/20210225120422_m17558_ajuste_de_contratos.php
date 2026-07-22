<?php

use Classes\PostgresMigration;

class M17558AjusteDeContratos extends PostgresMigration
{

    public function up()
    {


        $sSql = <<<SQL

           insert into db_itensmenu( id_item ,
                                     descricao ,
                                     help ,
                                     funcao ,
                                     itemativo ,
                                     manutencao ,
                                     desctec ,
                                     libcliente )
                                     values (
                                         228374 ,
                                         'Ajuste de Contratos' ,
                                         'Ajuste de Contratos' ,
                                         'con4_ajustecontratos001.php' ,
                                         '1' ,
                                         '1' ,
                                         'Ajuste de Contratos importação de planilhas de ajuste' ,'true' );

           insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228374 ,20 ,209 );

SQL;

      $this->execute($sSql);



    }


    public function down()
    {

        $sSql = <<<SQL

          delete from db_menu where id_item_filho = 228374 AND modulo = 209;
          delete from db_itensmenu where id_item = 228374;

SQL;

       $this->execute($sSql);
    }
}
