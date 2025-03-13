<?php

use Classes\PostgresMigration;

class M10169AlteracaoContratualMenu extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10576 ,'Alteração de Contrato de Trabalho' ,'S-2206 Alteração de Contrato de Trabalho' ,'eso02_preenchimentoesocial001.php?formularioTipo=18' ,'1' ,'1' ,'Monta e processa os dados do formulário do eSocial.' ,'true' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10220 ,10576 ,14 ,10216 );
            ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 10576 AND modulo = 10216;
            DELETE FROM db_itensmenu WHERE id_item = 10576;
        ";
        $this->execute($sql);
    }
}
