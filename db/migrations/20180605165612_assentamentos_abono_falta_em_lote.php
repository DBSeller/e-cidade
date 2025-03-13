<?php

use Classes\PostgresMigration;

class AssentamentosAbonoFaltaEmLote extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10524 ,'Lançamento de Assentamentos de Abono Falta em Lote' ,'Lançamento de Assentamentos de Abono Falta em Lote' ,'rec4_lancamentoabonofaltaemlote001.php' ,'1' ,'1' ,'Rotina para lançamento de assentamentos, do tipo abono falta, em lote.' ,'true' );
delete from db_menu where id_item_filho = 10524 AND modulo = 2323;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,10524 ,8 ,2323 );
SQL_UP
        );
    }


    public function down()
    {
        $this->execute(
          <<<SQL_DOWN
delete from db_menu where id_item_filho = 10524 AND modulo = 2323;
delete from db_itensmenu where id_item = 10524;
SQL_DOWN
        );
    }
}
