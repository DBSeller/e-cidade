<?php

use Classes\PostgresMigration;

class M18718Menu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228559 ,'Consulta por Conta do PCASP' ,'Consulta de Lançamento por Conta do PCASP' ,'con3_consultalancamentopcasp.php' ,'1' ,'1' ,'Efetua consulta de Lançamento por Conta do PCASP' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3397 ,228559 ,10 ,209 );
SQL
        );    
    }
    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228559 AND modulo = 209;
delete from db_itensmenu where id_item = 228559;
SQL
        );  
    }
}
