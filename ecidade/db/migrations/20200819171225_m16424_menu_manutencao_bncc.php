<?php

use Classes\PostgresMigration;

class M16424MenuManutencaoBncc extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228320 ,'Manutenção no Referencial Curricular / BNCC' ,'Manutenção no Referencial Curricular / BNCC' ,'edu1_manutencao_bncc001.php' ,'1' ,'1' ,'Manutenção no Referencial Curricular / BNCC' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228203 ,228320 ,3 ,7159 );

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        delete from db_menu where id_item_filho = 228320 AND modulo = 7159;
        delete from db_itensmenu where id_item = 228320;

SQL
        );

    }
}
