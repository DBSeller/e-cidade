<?php

use Classes\PostgresMigration;

class M13822CriacaoMenu extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228167 ,'Encerramento de Exercício Contábil' ,'Encerramento de Exercício Contábil' ,'con4_processaencerramentocontabil.php' ,'1' ,'1' ,'Encerramento de Exercício Contábil' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228167 ,18 ,209 );
SQL;
        $this->execute($sql);

    }

    public function down()
    {

        $sql = <<<SQL
delete from db_menu where id_item_filho = 228167 AND modulo = 209;
delete from db_itensmenu where id_item  = 228167;
SQL;
        $this->execute($sql);

    }

}
