<?php

use Classes\PostgresMigration;

class M17931Menu extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228525 ,'Dem III - Metas Atuais Comparadas três execícios anteriores' ,'Dem III - Metas Atuais Comparadas três execícios anteriores' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=3' ,'1' ,'1' ,'Dem III - Metas Atu. Comp. Três Exe. Ant' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228507 ,228525 ,3 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228525 AND modulo = 228358;
delete from db_itensmenu where id_item = 228525;
SQL
        );
    }
}
