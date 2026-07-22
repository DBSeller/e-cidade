<?php

use Classes\PostgresMigration;

class M9993MenuConsultaEventoEsocial extends PostgresMigration
{
    public function up()
    {
        $sSql  = "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10489 ,'Situação de Eventos' ,'Consulta de Situação de Eventos' ,'eso02_situacaoevento001.php' ,'1' ,'1' ,'Consuta de Situação dos eventos enviados ao eSocial' ,'true' );";
        $sSql .= "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,10489 ,184 ,10216 );";

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql  = "delete from db_menu where id_item_filho = 10489 AND modulo = 10216;";
        $sSql .= "delete from db_itensmenu where id_item = 10489;";
        $this->execute($sSql);
    }
}
