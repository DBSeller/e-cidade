<?php

use Classes\PostgresMigration;

class M17939RelatorioEmissaoProgramaTematico extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values (228497 ,'PPA' ,'PPA' ,'' ,'1' ,'1' ,'Relatórios do PPA' ,'true' ),
       (228498 ,'Programas Estratégicos - Temáticos' ,'Programas Estratégicos - Temáticos' ,'pla2_programas_estrategicos.php' ,'1' ,'1' ,'Emissão dos Programas Temáticos do PPA.' ,'true' );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values (228363, 228497, 2, 228358),
       (228497, 228498, 1, 228358);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho in (228497, 228498);
delete from db_itensmenu where id_item in (228497, 228498);
SQL
        );
    }
}
