<?php

use Classes\PostgresMigration;

class M13822MenuConsistenciaInformacoes extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228174 ,'Consistência do Encerramento do Exercício' ,'Consistência do Encerramento do Exercício' ,'con4_consistenciainformacoes.php?tipo=100' ,'1' ,'1' ,'Consistência do Encerramento do Exercício' ,'true' );
delete from db_menu where id_item_filho = 228174 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228174 ,19 ,209 );

SQL_UP
);
    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228174 AND modulo = 209;
delete from db_itensmenu where id_item = 228174;

SQL_DOWN
);
    }
}
