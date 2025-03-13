<?php

use Classes\PostgresMigration;

class M16124AlvaraEventosMenus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228266 ,'Ordem de Serviço' ,'Ordem de Serviço' ,'iss4_ordemservico001.php' ,'1' ,'1' ,'Rotina para inserção e edição de ordem de serviço e fiscais da ordem de serviço' ,'true' );
            delete from db_menu where id_item_filho = 228266 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228266 ,514 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228267 ,'Alvará de Eventos' ,'Alvará de Eventos' ,'' ,'1' ,'1' ,'Agrupador para rotinas de alvara de eventos' ,'true' );
            delete from db_menu where id_item_filho = 228267 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228267 ,515 ,40 );
            delete from db_menu where id_item_filho = 228266 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228267 ,228266 ,1 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228268 ,'Liberação de Alvará de Eventos' ,'Liberação de Alvará de Eventos' ,'iss4_alvaraeventos001.php' ,'1' ,'1' ,'Rotina para inserção e geração de alvará de eventos' ,'true' );
            delete from db_menu where id_item_filho = 228268 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228267 ,228268 ,2 ,40 );

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            delete from db_menu where id_item_filho = 228267 AND modulo = 40;
            delete from db_menu where id_item_filho = 228266 AND modulo = 40;
            delete from db_menu where id_item_filho = 228268 AND modulo = 40;
            delete from db_itensmenu where id_item in (228267, 228266, 228268);

SQL
        );
    }
}
