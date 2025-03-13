<?php

use Classes\PostgresMigration;

class M15938CriaMenuGerarSigapFiscal extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        insert into db_itensmenu
            ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
            values ( 228255 ,'Gerar SIGAP Fiscal' ,'Gerar SIGAP Fiscal' ,'con4_gerarsigap_fiscal.php' ,'1' ,'1' ,'Geração SIGAP Fiscal XML' ,'true' );
        insert into db_menu
            ( id_item ,id_item_filho ,menusequencia ,modulo )
            values ( 8467 ,228255 ,5 ,209 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228255 AND modulo = 209;
            delete from db_itensmenu where id_item = 228255;
        ");
    }
}
