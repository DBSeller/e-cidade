<?php

use Classes\PostgresMigration;

class M17933Menu extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228529 ,'Dem. V - Origem e Aplicação dos Recursos Obtidos com a Alienação de Ativos' ,'Dem. V - Origem e Aplicação dos Recursos Obtidos com a Alienação de Ativos' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=5' ,'1' ,'1' ,'ORIGEM E APLICAÇÃO DOS RECURSOS OBTIDOS COM A ALIENAÇÃO DE ATIVOS' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228507 ,228529 ,5 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228529 AND modulo = 228358;
delete from db_itensmenu where id_item = 228529;
SQL
        );
    }
}
