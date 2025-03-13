<?php

use Classes\PostgresMigration;

class M10222DeParaSiconfi extends PostgresMigration
{
    public function up()
    {
        $this->criaMenu();
        $this->addDicionarioDados();
        $this->alteraTabela();
    }

    public function down()
    {
        $this->removeMenu();
        $this->removeDicionarioDados();
        $this->downAlteraTabela();
    }


    private function criaMenu()
    {
        $this->execute(<<<MENU
          insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10496 ,'Matriz de Saldos Contábeis' ,'Processamento e exportação da matriz de saldo contábil' ,'' ,'1' ,'1' ,'Processamento e exportação da matriz de saldo contábil' ,'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3332 ,10496 ,30 ,209 );
MENU
);
    }

    private function removeMenu()
    {
        $this->execute(<<<MENU
          delete from db_menu where id_item_filho = 10496 AND modulo = 209;
          delete from db_itensmenu where id_item = 10496;
MENU
        );
    }


    private function addDicionarioDados()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1009634,'db21_codigosiconfi','varchar(6)','Código do tipo de instituição conforme layout do SICONFI.','', 'Código do SICONFI',6,'t','f','f',0,'text','Código do SICONFI');
            insert into db_syscampo values(1009636,'o15_codigosiconfi','varchar(5)','Código do recurso conforme layout do SICONFI.','', 'Código do SICONFI',5,'t','f','f',0,'text','Código do SICONFI');
            insert into db_sysarqcamp values(749,1009636,8,0);
            insert into db_sysarqcamp values(1536,1009634,4,0);
SQL
        );
    }

    private function alteraTabela()
    {
        $this->execute(<<<SQL
            alter table db_tipoinstit add column db21_codigosiconfi varchar(6);
            alter table orctiporec add column o15_codigosiconfi varchar(5);
SQL
        );

    }

    private function removeDicionarioDados()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codarq = 1536 and codcam = 1009634;
            delete from db_sysarqcamp where codarq = 749 and codcam = 1009636;
            delete from db_syscampo where codcam = 1009634;
            delete from db_syscampo where codcam = 1009636;
SQL
        );
    }

    private function downAlteraTabela()
    {
        $this->execute(<<<SQL
            alter table db_tipoinstit drop column db21_codigosiconfi;
            alter table orctiporec drop column o15_codigosiconfi;
SQL
        );
    }

}
