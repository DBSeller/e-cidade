<?php

use Classes\PostgresMigration;

class M18986CriarItemMenuRelatorioVacinas extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228577, 'Vacinação', 'Vacinação', 'edu2_registrovacinas.php', '1', '1', 'Relatório de Vacinação do RH da escola', 'true');

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
                values (1101111, 228577, 12, 7159),
                       (1101111, 228577, 13, 1100747);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228577;
            delete from db_itensmenu where id_item = 228577;
SQL
        );
    }
}
