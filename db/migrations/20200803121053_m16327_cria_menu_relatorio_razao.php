<?php

use Classes\PostgresMigration;

class M16327CriaMenuRelatorioRazao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228312, 'Razão da Despesa', 'Razão da Despesa', 'con2_razaodespesa_pv001.php', '1', '1', 'Razão da Despesa', 'true'),
                (228313, 'Razão da Receita', 'Razão da Receita', 'con2_razaoreceita_pv001.php', '1', '1', 'Razão da Receita', 'true');

delete from db_menu where id_item_filho = 228312 AND modulo = 209;
delete from db_menu where id_item_filho = 228313 AND modulo = 209;

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (3583, 228312, 11, 209),
                   (3583, 228313, 12, 209);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228312 AND modulo = 209;
delete from db_menu where id_item_filho = 228313 AND modulo = 209;

delete from db_itensmenu where id_item in (228312, 228313);
SQL
        );
    }
}
