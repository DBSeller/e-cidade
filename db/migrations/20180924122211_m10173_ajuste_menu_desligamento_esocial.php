<?php

use Classes\PostgresMigration;

class M10173AjusteMenuDesligamentoEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "
            update db_itensmenu set funcao = '' where funcao = 'con4_cargaformulariorescisao001.php';
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( nextval('db_itensmenu_id_item_seq') ,'Trabalhador Com Vínculo' ,'Trabalhador Com Vínculo' ,'con4_cargaformulariorescisao001.php' ,'1' ,'1' ,'Carga de dados para envio do desligamento para trabalhador com vínculo' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10569 ,currval('db_itensmenu_id_item_seq') ,2 ,10216 );
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item = (select id_item from db_itensmenu where funcao = 'con4_cargaformulariorescisao001.php');
            delete from db_itensmenu where funcao = 'con4_cargaformulariorescisao001.php';
        ";

        $this->execute($sql);
    }
}
