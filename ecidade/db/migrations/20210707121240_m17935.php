<?php

use Classes\PostgresMigration;

class M17935 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228530 ,'Dem. VI - Avaliação da Situação Financeira e Atuarial do RPPS' ,'Dem. VI - Avaliação da Situação Financeira e Atuarial do RPPS' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=6' ,'1' ,'1' ,'Dem. VI - Avaliação da Situação Financeira e Atuarial do RPPS' ,'false' ),
       ( 228531 ,'Dem. VII - Estimativa e Compensação da Renúncia de Receita' ,'Dem. VII - Estimativa e Compensação da Renúncia de Receita' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=7' ,'1' ,'1' ,'Dem. VII - Estimativa e Compensação da Renúncia de Receita' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values ( 228507 ,228530 ,6 ,228358 ),
       ( 228507 ,228531 ,7 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho in (228530, 228531) AND modulo = 228358;
delete from db_itensmenu where id_item in (228530, 228531);
SQL
        );
    }
}
