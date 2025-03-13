<?php

use Classes\PostgresMigration;

class M11191MenuRelacaoContaCorrente extends PostgresMigration
{
    public function up()
    {
        $this->execute(
            <<<SQL_UP
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228062 ,'Relação de Conta Corrente' ,'Relação de Conta Corrente' ,'con2_contasporcontacorrente001.php' ,'1' ,'1' ,'Relação de Conta Corrente' ,'true' );
delete from db_menu where id_item_filho = 228062 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4152 ,228062 ,18 ,209 );

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228067 ,'Nota de Lançamento' ,'Nota de Lançamento' ,'con2_notadelancamento001.php' ,'1' ,'1' ,'Nota de Lançamento' ,'true' );
delete from db_menu where id_item_filho = 228067 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,228067 ,52 ,209 );

SQL_UP
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL_DOWN
delete from db_menu where id_item_filho = 228062 AND modulo = 209;
delete from db_itensmenu where id_item = 228062;

delete from db_menu where id_item_filho = 228067 AND modulo = 209;
delete from db_itensmenu where id_item = 228067;

SQL_DOWN
        );
    }
}
