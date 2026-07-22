<?php

use Classes\PostgresMigration;

class M12077CalculoAtuarialLumens extends PostgresMigration
{
    public function up()
    {
        $aSql = array();

        $aSql[] = "insert into db_itensmenu ( id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente )
                                   values ( 10590 ,'Lumens', 'Rotina de cálculo atuarial para empresa Lumens',
                                            'pes4_calculoatuarialgeracaoarquivos001.php', '1', '1', 'Rotina de cálculo atuarial para empresa Lumens', 'true' );
                ";

        $aSql[] = "insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 268991 ,10590 ,7 ,952 );";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }

    public function down()
    {
        $aSql = array();

        $aSql[] = "delete from db_menu where id_item_filho = 10590 AND modulo = 952;";
        $aSql[] = "delete from db_itensmenu where  id_item = 10590;";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }
}
