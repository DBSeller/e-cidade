<?php

use Classes\PostgresMigration;

class M17908MenuRelatorioOperacoesTef extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228560 ,'Operações TEF' ,'Operações TEF' ,'tes2_operacoestef.php' ,'1' ,'1' ,'Relatório de operações TEF.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228560 ,838 ,39 );
SQL
        );
    }
    public function down()
    {
        $this->execute(<<<SQL
            delete from db_itensmenu where id_item = 228560;
            delete from db_menu where id_item_filho = 228560 AND modulo = 39;
SQL
        );
    }
}
