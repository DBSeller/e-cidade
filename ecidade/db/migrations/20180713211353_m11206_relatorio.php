<?php

use Classes\PostgresMigration;

class M11206Relatorio extends PostgresMigration
{
    public function up()
    {

        $aSql = array();
        $aSql [] = "insert 
                       into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                            values (10545 ,'Relatório Líquido de Férias' ,'Relatório Líquido de Férias' ,'pes2_feriasliquido001.php' ,'1' ,'1' ,'Relatório Líquido de Férias' ,'true' );";
        $aSql [] = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (5703 , 10545, 6, 952);";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }
    }

    public function down()
    {

        $aSql = array();
        $aSql [] = "DELETE FROM db_menu  WHERE  id_item =5703  AND id_item_filho = 10545;";
        $aSql [] = "DELETE FROM db_itensmenu  WHERE id_item= 10545";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }
    }
}
