<?php

use Classes\PostgresMigration;

class M18069RelatorioConsignados extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228503 ,'Retorno das Consignações' ,'Retorno das Consignações' ,'pes2_RetornoConsignados001.php' ,'1' ,'1' ,'Relatório Consignados' ,'false' );
            delete from db_menu where id_item_filho = 228503 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1797 ,228503 ,60 ,952 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228503 AND modulo = 952;
            delete from db_itensmenu where id_item = 228503;
SQL
        );
    }
}
