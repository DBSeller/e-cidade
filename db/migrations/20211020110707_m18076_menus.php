<?php

use Classes\PostgresMigration;

class M18076Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values (228581, 'Cronograma de Desembolso' ,'Cronograma de Desembolso' ,'' ,'1' , '1', 'Cronograma de Desembolso', 'true'),
       (228582, 'Receita', 'Receita', 'pla4_cronogramadesembolso001.php?cronograma=receita', '1', '1', 'Cronograma de desembolso da receita', 'true'),
       (228583, 'Despesa', 'Despesa', 'pla4_cronogramadesembolso001.php?cronograma=despesa', '1', '1', 'Cronograma de desembolso da despesa', 'false');

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values (228365, 228581, 7, 228358),
       (228581, 228582, 1, 228358),
       (228581, 228583, 2, 228358);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho in (228581, 228582, 228583) AND modulo = 228358;
delete from db_itensmenu where id_item in (228581, 228582, 228583);
SQL
        );
    }
}
