<?php

use Classes\PostgresMigration;

class M17382RelatorioRubricaEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228372 ,'Relatório Comparativo de Configuração de Rubricas' ,'Relatório Comparativo de Configuração de Rubricas' ,'eso03_comparativorubrica001.php' ,'1' ,'1' ,'Relatório Comparativo de Configuração de Rubricas' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228372 ,829 ,10216 );

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            delete from db_menu where id_item_filho = 228372 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228372;
SQL;
        $this->execute($sql);
    }
}
