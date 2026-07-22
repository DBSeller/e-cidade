<?php

use Classes\PostgresMigration;

class M11191ReprocessaSaldoInicialContaCorrente extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228069 ,'Reprocessar Saldo Inicial' ,'Reprocessar Saldo Inicial' ,'con4_reprocessasaldoinicialcontacorrente001.php' ,'1' ,'1' ,'Reprocessa o saldo inicial de um conta corrente.' ,'true' );
delete from db_menu where id_item_filho = 228069 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228063 ,228069 ,2 ,209 );

SQL_UP
);

    }

    public function down()
    {

        $this->execute("delete from db_menu where id_item_filho = 228069");
        $this->execute("delete from db_itensmenu where id_item = 228069");

    }
}
