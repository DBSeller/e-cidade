<?php

use Classes\PostgresMigration;

class M17614Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228384 ,'Detalhamento da Despesa' ,'Detalhamento da Despesa' ,'pl4_detalhamento_despesa.php' ,'1' ,'1' ,'Detalhamento da Despesa' ,'true' );
delete from db_menu where id_item_filho = 228384 AND modulo = 228358;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228376 ,228384 ,4 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228384 AND modulo = 228358;
delete from db_itensmenu where id_item = 228384;
SQL
        );
    }
}
