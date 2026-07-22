<?php

use Classes\PostgresMigration;

class M18976CriaItemMenuExclusaoHistoricoPlaca extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228569 ,'Exclusão do Histórico de Placa' ,'Exclui histórico de placa ' ,'pat4_exclusaohistoricoplaca003.php' ,'1' ,'1' ,'Rotina que permite a exclusão do histórico de placa.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228569 ,545 ,439 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228569;
            delete from db_menu where id_item_filho = 228569 AND modulo = 439;
        ");
    }
}
