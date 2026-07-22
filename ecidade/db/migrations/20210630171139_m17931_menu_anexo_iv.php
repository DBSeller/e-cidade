<?php

use Classes\PostgresMigration;

class M17931MenuAnexoIv extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228527 ,'Dem. IV - Evolução do Patrimônio Líquido' ,'Evolução do Patrimônio Líquido' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=3' ,'1' ,'1' ,'Evolução do Patrimônio Líquido' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228507 ,228527 ,4 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228527 AND modulo = 228358;
delete from db_itensmenu where id_item = 228527;
SQL
        );
    }
}
