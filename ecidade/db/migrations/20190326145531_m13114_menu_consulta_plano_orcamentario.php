<?php

use Classes\PostgresMigration;

class M13114MenuConsultaPlanoOrcamentario extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228106 ,'Plano Orçamentário' ,'Consulta do Plano Orçamentário vinculado a dotação.' ,'orc3_consultaplanoorcamentario001.php' ,'1' ,'1' ,'Plano Orçamentário' ,'true' );
delete from db_menu where id_item_filho = 228106 AND modulo = 116;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,228106 ,188 ,116 );
SQL_UP
);

    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN
delete from db_menu where id_item_filho = 228106;
delete from db_itensmenu where id_item = 228106;
SQL_DOWN
        );
    }
}

