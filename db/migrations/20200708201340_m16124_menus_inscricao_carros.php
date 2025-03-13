<?php

use Classes\PostgresMigration;

class M16124MenusInscricaoCarros extends PostgresMigration
{
    public function up()
    {
        $this->upMenus();
    }

    public function down()
    {
        $this->downMenus();
    }

    private function upMenus()
    {
        $this->execute(<<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228275 ,'Inscrição de veículos' ,'Inscrição de veículos' ,'' ,'1' ,'1' ,'Agrupador de funções de incrição de veiculos' ,'true' );
            delete from db_menu where id_item_filho = 228275 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228275 ,293 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228276 ,'Baixa inscrição de veículos' ,'Baixa de inscrição de veículos' ,'' ,'1' ,'1' ,'Agrupador para funções de Baixa de inscrição de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228276 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228276 ,516 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228277 ,'Cálculo de inscrição de veículos' ,'Cálculo de inscrição de veículos' ,'' ,'1' ,'1' ,'Agrupador para funções de Cálculo de inscrição de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228277 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228277 ,517 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228278 ,'Inclusão' ,'Inclusão' ,'iss01_inscricaoveiculos001.php' ,'1' ,'1' ,'Menu de Inclusão de veiculos' ,'true' );
            delete from db_menu where id_item_filho = 228278 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228275 ,228278 ,1 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228279 ,'Alteração' ,'Alteração' ,'iss01_inscricaoveiculos002.php' ,'1' ,'1' ,'Menu de Edição de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228279 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228275 ,228279 ,2 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228280 ,'Exclusão' ,'Exclusão' ,'iss01_inscricaoveiculos003.php' ,'1' ,'1' ,'Menu de Exclusão de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228280 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228275 ,228280 ,3 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228281 ,'Inclusão' ,'Inclusão de baixa de veículos' ,'iss04_baixainscricaoveiculo001.php' ,'1' ,'1' ,'Menu de Inclusão de baixa de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228281 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228276 ,228281 ,1 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228282 ,'Exclusão' ,'Exclusão de baixa de veículos' ,'iss04_baixainscricaoveiculo003.php' ,'1' ,'1' ,'Menu de Exclusão de baixa de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228282 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228276 ,228282 ,2 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228283 ,'Individual' ,'Individual (por inscrição)' ,'iss04_calculoinscricaoveiculoindividual001.php' ,'1' ,'1' ,'Cálculo individual para inscrição de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228283 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228277 ,228283 ,1 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228284 ,'Geral' ,'Calculo geral para inscrições de veículos' ,'iss04_calculoinscricaoveiculogeral001.php' ,'1' ,'1' ,'Cálculo geral para inscrição de veículos' ,'true' );
            delete from db_menu where id_item_filho = 228284 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228277 ,228284 ,2 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228290 ,'Inscrição de veículo' ,'Conulsta de inscrição de veículo' ,'iss03_inscricaoveiculos001.php' ,'1' ,'1' ,'Apresentação dos dados da inscrição de veículos.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,228290 ,190 ,40 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228291 ,'Liberação de inscrição de veículo' ,'Liberação de inscrição de veículos' ,'iss04_liberacaoinscricaoveiculo_001.php' ,'1' ,'1' ,'Liberação de inscrição de veículos.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228291 ,519 ,40 );
SQL
        );
    }

    private function downMenus()
    {
        $this->execute(<<<SQL

            delete from db_menu where id_item_filho in (228275, 228276, 228277, 228278, 228279, 228280, 228281, 228282, 228283, 228284, 228290, 228291);
            delete from db_itensmenu where id_item in (228275, 228276, 228277, 228278, 228279, 228280, 228281, 228282, 228283, 228284, 228290, 228291);
SQL
        );
    }
}
