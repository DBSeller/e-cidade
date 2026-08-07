<?php

use Classes\PostgresMigration;

class M17869ExclusaoLoteEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228482 ,'Exclusão em Lote' ,'Exclusão de Eventos em Lote' ,'eso01_exclusaolote001.php' ,'1' ,'1' ,'Exclusão de eventos em Lote para o eSocial' ,'true' );
            delete from db_menu where id_item_filho = 228482 AND modulo = 10216;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228482 ,19 ,10216 );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228482 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228482;
        ";
        $this->execute($sql);
    }
}
