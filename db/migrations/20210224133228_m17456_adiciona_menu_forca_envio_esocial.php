<?php

use Classes\PostgresMigration;

class M17456AdicionaMenuForcaEnvioEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228373 ,'Reenviar Eventos para o eSocial' ,'Rotina de re-envio dos eventos do eSocial' ,'eso01_agendamentoenvioforcado.php' ,'1' ,'1' ,'Rotina de re-envio dos eventos do eSocial' ,'true' );
                insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228373 ,520 ,10216 );";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228373 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228373;";
        $this->execute($sql);
    }
}
