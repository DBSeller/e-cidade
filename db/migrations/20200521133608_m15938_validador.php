<?php

use Classes\PostgresMigration;

class M15938Validador extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228256, 'Validador XML', 'Validador XML', 'con4_validador_sigap004.php', '1', '1', 'Validador XML', 'false');

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (8467, 228256, 6, 209);
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228256 AND modulo = 209;
            delete from db_itensmenu where id_item = 228256;
        ");
    }
}
