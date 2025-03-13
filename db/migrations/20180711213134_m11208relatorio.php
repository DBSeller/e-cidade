<?php

use Classes\PostgresMigration;

class M11208relatorio extends PostgresMigration
{

    public function up()
    {

        $aSql = array();
        $aSql [] = "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                    values (10544 ,'Férias Programadas' ,'Férias Programadas ' ,'pes2_feriasprogramadas001.php' ,'1' ,'1' ,'Férias Programadas ' ,'true' );";
        $aSql [] = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (5703 ,10544 ,32 ,952 );
";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }
    }

    public function down()
    {
        $aSql = array();
        $aSql [] = "DELETE FROM db_menu  WHERE  id_item =5703  AND id_item_filho = 10544;";
        $aSql [] = "DELETE FROM db_itensmenu  WHERE id_item= 10544";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }

    }

}
