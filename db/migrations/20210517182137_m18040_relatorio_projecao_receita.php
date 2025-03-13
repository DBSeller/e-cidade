<?php

use Classes\PostgresMigration;

class M18040RelatorioProjecaoReceita extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (228504, 'Demonstrativo das Projeções da Receita', 'Demonstrativo das Projeções da Receita', 'pla2_projecao_receita.php', '1', '1', 'Demonstrativo das Projeções da Receita', 'true');
insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (228497, 228504, 3, 228358);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228504 AND modulo = 228358;
delete from db_itensmenu where id_item = 228504;
SQL
        );
    }
}
