<?php

use Classes\PostgresMigration;

class M19035MenuResumoReceita extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228573 ,'Resumo da Projeção da Receita' ,'Resumo da Projeção da Receita' ,'pla2_resumo_projecao_receita001.php' ,'1' ,'1' ,'Resumo da Projeção da Receita' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228497 ,228573 ,12 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228573 AND modulo = 228358;
delete from db_itensmenu where id_item = 228573;
SQL
        );
    }
}
