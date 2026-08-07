<?php

use Classes\PostgresMigration;

class M18444Menu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values (228523, 'Projeções da Despesa - Sintético', 'Projeções da Despesa - Sintético', 'pla2_projecao_despesa_sintetico.php', '1' ,'1' ,'Projeções da Despesa - Sintético' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228497 ,228523 ,11 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228523 AND modulo = 228358;
delete from db_itensmenu where id_item = 228523;
SQL
        );
    }
}
