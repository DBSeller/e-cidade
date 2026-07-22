<?php

use Classes\PostgresMigration;

class M15512Sliptipooperacao extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
insert into sliptipooperacao (k152_sequencial, k152_descricao) values (16, 'Movimento financeiro folha - Inclusão');
SQL;
        $this->execute($sql);

        $sqlMenu = <<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228241 ,'Movimento financeiro da Folha' ,'Movimento financeiro da folha' ,'cai4_movimentofinanceirofolha001.php' ,'1' ,'1' ,'Movimento financeiro da folha' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7791 ,228241 ,5 ,39 );
SQL;
        $this->execute($sqlMenu);

        $sqlConhist = <<<SQL
        insert into conhist (c50_codhist,c50_compl,c50_descr) values (995, false, 'TRANSFERÊNCIA BANCARIA P/ RECURSO PRÓPRIO' );
        insert into conhist (c50_codhist,c50_compl,c50_descr) values (996, false, 'TRANSFERÊNCIA BANCARIA P/ RECURSO VINCULADO' );
SQL;
        $this->execute($sqlConhist);

    }

    public function down()
    {
        $sql = <<<SQL
        delete from sliptipooperacao where k152_sequencial = 16;
        delete from conhist where c50_codhist in (995, 996);
SQL;
        $this->execute($sql);

        $sqlMenu = <<<SQL
        delete from db_menu where id_item_filho = 228241 AND modulo = 39;
        delete from db_itensmenu where id_item = 228241;
SQL;
        $this->execute($sqlMenu);




    }
}
