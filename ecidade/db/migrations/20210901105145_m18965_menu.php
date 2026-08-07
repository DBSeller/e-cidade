<?php

use Classes\PostgresMigration;

class M18965Menu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228567 ,'Balancete da Despesa por Complemento' ,'Balancete da Despesa por Complemento' ,'con2_balancete_despesa_complemento001.php' ,'1' ,'1' ,'Balancete da Despesa por Complemento' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4065 ,228567 ,15 ,209 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228567 AND modulo = 209;
delete from db_itensmenu where id_item = 228567;
SQL
        );
    }
}
