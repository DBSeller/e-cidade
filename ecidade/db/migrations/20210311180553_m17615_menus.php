<?php

use Classes\PostgresMigration;

class M17615Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228395 ,'Receita' ,'Manutenção da Receita' ,'pla4_receita.php' ,'1' ,'1' ,'Manutenção da Receita' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228375 ,228395 ,2 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228395 AND modulo = 228358;
delete from db_itensmenu where id_item = 228395;
SQL
        );
    }
}
