<?php

use Classes\PostgresMigration;

class M10183Menu extends PostgresMigration
{
    public function up()
    {
        $aSql = array();

        $aSql[] = " insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) 
                     values (10580 ,'Término / Rescisão' ,'Término / Rescisão' ,'eso4_trabalhadorsemvinculotermino001.php' ,'1' ,'1' ,'Término do trabalhador sem vinculo' ,'true' );
                   ";

        $aSql[] = "insert into db_menu(id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10570 ,10580 ,2 ,10216);";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }

    public function down()
    {
        $aSql = array();

        $aSql[] = "delete from db_menu  where   id_item_filho = 10580;";
        $aSql[] = "delete from db_itensmenu where  id_item = 10580;";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }
}
