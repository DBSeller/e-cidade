<?php

use Classes\PostgresMigration;

class M10754ContraChequeBanrisul extends PostgresMigration
{
    public function up()
    {
        $aSql = array();

        $aSql[] = " insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                     values ( 10587 ,'Contra Cheque Banrisul' ,'Emissão Contra Cheque Banrisul' ,'pes2_contracheque_banrisul002.php' ,'1' ,'1' ,'Emissão de contra cheques para o Banrisul' ,'true' );
                   ";

        $aSql[] = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8166 ,10587 ,2 ,952 );";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }

    public function down()
    {
        $aSql = array();

        $aSql[] = "delete from db_menu where id_item_filho = 10587 AND modulo = 952;";
        $aSql[] = "delete from db_itensmenu where  id_item = 10587;";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }

}
