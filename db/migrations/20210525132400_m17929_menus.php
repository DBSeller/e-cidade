<?php

use Classes\PostgresMigration;

class M17929Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values (228507 ,'Anexo de Metas e Riscos Fiscais' ,'Anexo de Metas e Riscos Fiscais' ,'' ,'1' ,'1' ,'Relatórios da LDO' ,'true' ),
       (228508 ,'Dem I - Metas Anuais' ,'Dem I - Metas Anuais' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=1' ,'1' ,'1' ,'Emissão das metas anuais' ,'true' ),
       (228516, 'PIB', 'PIB', 'pla1_pib001.php?tipo=LDO', '1', '1', 'Cadastro do PIB', 'true');

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values ( 228363 ,228507 ,3 ,228358 ),
       ( 228507 ,228508 ,1 ,228358 ),
       ( 228359 ,228516 ,4 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where modulo = 228358 and id_item_filho in (228507, 228508, 228516);
delete from db_itensmenu where id_item in (228507, 228508, 228516);
SQL
        );
    }
}
