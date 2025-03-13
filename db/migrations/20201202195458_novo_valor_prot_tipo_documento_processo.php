<?php

use Classes\PostgresMigration;

class NovoValorProtTipoDocumentoProcesso extends PostgresMigration
{


    public function up()
    {
        $this->dicionarioDeDadosUP();
        $this->execute("SELECT setval('formareclamacao_p42_sequencial_seq',1000);");
    }

    public function down()
    {
        $this->dicionarioDeDadosDOWN();
    }

    private function dicionarioDeDadosUP(){
        $this->execute("
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228352 ,'Forma Reclamação' ,'Forma Reclamação' ,'ouv4_formareclamacao001.php' ,'1' ,'1' ,'Cadastro edição e exclusão de Forma Reclamação' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7842 ,228352 ,8 ,7837 );
        ");
    }

    private function dicionarioDeDadosDOWN(){
        $this->execute("
        DELETE FROM db_menu WHERE  id_item =  7842 and id_item_filho =228352;
        DELETE FROM db_itensmenu WHERE id_item = 228352;
        ");

    }
}
