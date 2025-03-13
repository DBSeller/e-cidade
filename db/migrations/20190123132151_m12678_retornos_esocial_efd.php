<?php

use Classes\PostgresMigration;

class M12678RetornosEsocialEfd extends PostgresMigration
{
    public function up()
    {
        $this->incluirMenus();
    }

    public function down()
    {
        $this->excluirMenus();
    }

    public function incluirMenus()
    {
        $sql =
<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228094 ,'Eventos de Retorno' ,'Consulta dos eventos de retorno enviados pelo EFD-Reinf.' ,'sped04_retornoevento001.php?integracao=1' ,'1' ,'1' ,'Rotina que permite emitir relatórios dos eventos de retorno enviados pelo EFD-Reinf.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228094 ,476 ,228077 );

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228095 ,'Eventos de Retorno' ,'Consulta dos eventos de retorno enviados pelo eSocial.' ,'sped04_retornoevento001.php?integracao=2' ,'1' ,'1' ,'Rotina que permite emitir relatórios dos eventos de retorno enviados pelo eSocial.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228095 ,477 ,10216 );
SQL;
        $this->execute($sql);
    }

    public function excluirMenus()
    {
        $sql =
<<<SQL
        delete from db_menu where id_item_filho = 228094 AND modulo = 228077;
        delete from db_itensmenu where id_item = 228094;

        delete from db_menu where id_item_filho = 228095 AND modulo = 10216;
        delete from db_itensmenu where id_item = 228095;
SQL;
        $this->execute($sql);
    }

}
