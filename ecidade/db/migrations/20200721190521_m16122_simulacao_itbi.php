<?php

use Classes\PostgresMigration;

class M16122SimulacaoItbi extends PostgresMigration
{
    public function up()
    {
    
        $this->execute(<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228300 ,'Simulação de ITBI' ,'Simulação de ITBI' ,'' ,'1' ,'1' ,'Rotina para efetuar a simulação de ITBI.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,228300 ,136 ,2544 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228301 ,'Urbano' ,'Urbano' ,'itbi_simulacaourbano001.php' ,'1' ,'1' ,'Rotina que simula o itbi urbano.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228300 ,228301 ,1 ,2544 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228302 ,'Rural' ,'Rural' ,'itbi_simulacaorural001.php' ,'1' ,'1' ,'Rotina que simula o itbi rural.' ,'true' );    
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228300 ,228302 ,2 ,2544 );
SQL
);
    }

    public function down()
    {
        $this->execute(<<<SQL
        delete from db_menu where modulo = 2544 and id_item_filho in (228300, 228301, 228302);
        delete from db_itensmenu where id_item in (228300, 228301, 228302);
SQL
);
    }
}
