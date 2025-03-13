<?php

use Classes\PostgresMigration;

class M18722MenuNovoBalanceteReceita extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values (228553 ,'Balancete Receita por Complemento' ,'Balancete Receita por Complemento' ,'con2_balancete_receita_complemento001.php' ,'1' ,'1' ,'Emissão do balancete de receita por complemento da fonte de recurso' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4065 ,228553 ,14 ,209 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228553 AND modulo = 209;
delete from db_itensmenu where id_item = 228553
SQL
        );
    }
}
