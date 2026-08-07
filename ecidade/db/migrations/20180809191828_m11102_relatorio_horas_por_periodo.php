<?php

use Classes\PostgresMigration;

class M11102RelatorioHorasPorPeriodo extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
    }

    public function down()
    {
        $this->downMenu();
    }

    private function upMenu()
    {
        $sql = <<<SQL
          insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10565 ,'Relatório de Horas por Período' ,'Relatório de Horas por Período' ,'rec2_horasporperiodo001.php' ,'1' ,'1' ,'Relatório de Horas por Período do ponto eletronico' ,'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10388 ,10565 ,8 ,2323 );
SQL;
        $this->execute($sql);
    }

    private function downMenu()
    {
        $sql = <<<SQL
          delete from db_menu where id_item_filho = 10565 and modulo = 2323 and id_item = 10388;
          delete from db_itensmenu where id_item = 10565;
SQL;
        $this->execute($sql);
    }
}
