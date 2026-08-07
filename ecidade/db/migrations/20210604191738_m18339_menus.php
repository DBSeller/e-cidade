<?php

use Classes\PostgresMigration;

class M18339Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228518 ,'Calcular Valores Sintéticos' ,'Calcular Valores Sintéticos' ,'pla4_calcular_valor_sintetico.php' ,'1' ,'1' ,'Essa rotina foi desenvolvida para os clientes que optarem informar o valor no detalhamento. Será somado os valores do detalhamento e alterado no programa, iniciativa e assim por diante' ,'false' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228376 ,228518 ,7 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228518 AND modulo = 228358;
delete from db_itensmenu where id_item = 228518;
SQL
        );
    }
}
