<?php

use Classes\PostgresMigration;

class M18420Menu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values ( 228522 ,'Demonstrativo das Projeções da Despesa' ,'Demonstrativo das Projeções da Despesa' ,'pla2_projecao_despesa.php' ,'1' ,'1' ,'Demonstrativo das Projeções da Despesa' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228497 ,228522 ,10 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228522 AND modulo = 228358;
delete from db_itensmenu where id_item = 228522;
SQL
        );
    }
}
