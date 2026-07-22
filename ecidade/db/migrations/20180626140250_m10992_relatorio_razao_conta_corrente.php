<?php

use Classes\PostgresMigration;

class M10992RelatorioRazaoContaCorrente extends PostgresMigration
{
    public function up()
    {
        $sql = " 
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10539 ,'Razão por Conta Corrente' ,'Razão por Conta Corrente' ,'con2_razaocontasatributos.php' ,'1' ,'1' ,'Relatório de Razão por Conta Corrente' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3583 ,10539 ,10 ,209 );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 10539 AND modulo = 209;
            delete from db_itensmenu where id_item = 10539;
        ";
        $this->execute($sql);
    }
}
