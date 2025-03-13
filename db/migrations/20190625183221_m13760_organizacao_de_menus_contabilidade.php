<?php

use Classes\PostgresMigration;

class M13760OrganizacaoDeMenusContabilidade extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP


insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228145 ,'Retificação de Lançamentos' ,'Retificação de Lançamentos' ,'' ,'1' ,'1' ,'Retificação de Lançamentos' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228145 ,17 ,209 );

delete from db_menu where id_item_filho = 3938 AND modulo = 209;
delete from db_menu where id_item_filho = 228144 AND modulo = 209;
insert into db_menu values(228145,3938,1,209);
insert into db_menu values(228145,228144,2,209);

update db_itensmenu set descricao = 'Retificação por reprocessamento' where id_item = 3938;
update db_itensmenu set descricao = 'Retificação por alteração' where id_item = 228144;

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 3938 AND modulo = 209;
delete from db_menu where id_item_filho = 228144 AND modulo = 209;
delete from db_menu where id_item_filho = 228145 AND modulo = 209;
insert into db_menu values (4197, 3938, 5, 209);
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228144 ,16 ,209 );
delete from db_itensmenu where id_item = 228145;

SQL_DOWN
);

    }
}
