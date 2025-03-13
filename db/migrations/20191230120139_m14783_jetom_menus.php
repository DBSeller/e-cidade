<?php

use Classes\PostgresMigration;

class M14783JetomMenus extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228188 ,'Jetons' ,'Jetons' ,'' ,'1' ,'1' ,'Grupo de Menus do Jetom.' ,'true' );
            delete from db_menu where id_item_filho = 228188 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,228188 ,134 ,952 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228189 ,'Funções' ,'Funções' ,'pes4_jetomfuncao.php' ,'1' ,'1' ,'Menu de inclusão/alteração/exclusão das funções do Jetom.' ,'true' );
            delete from db_menu where id_item_filho = 228189 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228188 ,228189 ,1 ,952 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228193 ,'Comissões' ,'Comissões dos Jetons' ,'' ,'1' ,'1' ,'Grupo de menus das comissões dos Jetons.' ,'true' );
            delete from db_menu where id_item_filho = 228193 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228188 ,228193 ,2 ,952 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228194 ,'Cadastros' ,'Cadastros da Comissão do Jetom' ,'pes4_jetomcomissao.php' ,'1' ,'1' ,'Menu de cadastros da comissão do Jetom.' ,'true' );
            delete from db_menu where id_item_filho = 228194 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228193 ,228194 ,1 ,952 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228197 ,'Sessão' ,'Cadastro de Sessão da Comissão' ,'pes4_jetomsessao.php' ,'1' ,'1' ,'Lançamento da comissão do Jetom.' ,'true' );
            delete from db_menu where id_item_filho = 228197 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228193 ,228197 ,4 ,952 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228198 ,'Processamento' ,'Processamento de Sessões da Comissão do Jetom' ,'pes4_jetomprocessamento.php' ,'1' ,'1' ,'Processamento da comissão do Jetom para a folha de pagamento.' ,'true' );
            delete from db_menu where id_item_filho = 228198 AND modulo = 952;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228193 ,228198 ,5 ,952 );

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            delete from db_menu where id_item in (228198, 228197, 228194, 228193, 228189, 228188);
            delete from db_itensmenu where id_item in (228198, 228197, 228196, 228195, 228194, 228193, 228192, 228191 ,228190, 228189, 228188);
SQL;
        $this->execute($sql);
    }
}
