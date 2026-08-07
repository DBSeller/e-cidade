<?php

use Classes\PostgresMigration;

class M18348Menu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228521 ,'Projeções da Despesa por Elemento' ,'Projeções da Despesa por Elemento' ,'pla2_por_elemento.php' ,'1' ,'1' ,'Projeções da Despesa por Elemento' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228497 ,228521 ,9 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228521 AND modulo = 228358;
delete from db_itensmenu where id_item = 228521;


SQL
        );
    }
}
