<?php

use Classes\PostgresMigration;

class M13822OrganizacaoMenusEncerramentoAbertura extends PostgresMigration
{
    public function down() {}

    public function up()
    {
        $this->execute(<<<SQL_UP


insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
     values ( 228186 ,
             'Encerramento do Exercício' ,
             'Rotinas referente ao encerramento do exercício' ,
             '' ,
             '1' ,
             '1' ,
             'Rotinas referente ao encerramento do exercício' ,
             'true' );
delete from db_menu where id_item_filho = 228186 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9828 ,228186 ,8 ,209 );

update db_menu set menusequencia = 1 where id_item = 9828 and modulo = 209 and id_item_filho = 3386;
update db_menu set menusequencia = 2 where id_item = 9828 and modulo = 209 and id_item_filho = 9312;
update db_menu set menusequencia = 3 where id_item = 9828 and modulo = 209 and id_item_filho = 9414;
update db_menu set menusequencia = 4 where id_item = 9828 and modulo = 209 and id_item_filho = 228186;
update db_menu set menusequencia = 5 where id_item = 9828 and modulo = 209 and id_item_filho = 9475;
update db_menu set menusequencia = 6 where id_item = 9828 and modulo = 209 and id_item_filho = 9818;
update db_menu set menusequencia = 7 where id_item = 9828 and modulo = 209 and id_item_filho = 10414;

delete from db_menu where id_item_filho = 228167 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228186 ,228167 ,1 ,209 );
delete from db_menu where id_item_filho = 228174 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228186 ,228174 ,2 ,209 );




SQL_UP
);
    }

}
