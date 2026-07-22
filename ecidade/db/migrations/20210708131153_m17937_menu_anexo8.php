<?php

use Classes\PostgresMigration;

class M17937MenuAnexo8 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228532 ,'Dem. VIII - Margem de Expansão das Despesas Obrigatórias de Caráter Continuado' ,'Dem. VIII - Margem de Expansão das Despesas Obrigatórias de Caráter Continuado' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=8' ,'1' ,'1' ,'Dem. VIII - Margem de Expansão das Despesas Obrigatórias de Caráter Continuado' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228507 ,228532 ,8 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228532 AND modulo = 228358;
delete from db_itensmenu where id_item = 228532;
SQL
        );
    }
}
