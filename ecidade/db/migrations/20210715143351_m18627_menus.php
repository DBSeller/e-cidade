<?php

use Classes\PostgresMigration;

class M18627Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
update db_itensmenu set libcliente = 'true' where id_item = 228362;

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228540 ,'Integração Orçamento' ,'Integração Orçamento' ,'' ,'1' ,'1' ,'Integração Orçamento' ,'true' ),
       ( 228541 ,'Exportar' ,'Exportar' ,'pla4_exportar.php?tipo=LOA' ,'1' ,'1' ,'Gera o orçamento em cima da LOA' ,'true' ),
       ( 228542 ,'Cancela Exportação' ,'Cancela Exportação' ,'pla4_cancelaexportacao.php?tipo=LOA' ,'1' ,'1' ,'Cancela Exportação' ,'true' );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values ( 228365 ,228540 ,6 ,228358 ),
       (228540, 228541, 1, 228358),
       (228540, 228542, 2, 228358);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho in (228540, 228541, 228542);
delete from db_itensmenu where id_item in (228540, 228541, 228542);
update db_itensmenu set libcliente = 'false' where id_item = 228362;
SQL
        );
    }
}
