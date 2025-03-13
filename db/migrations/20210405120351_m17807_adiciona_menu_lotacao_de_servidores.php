<?php

use Classes\PostgresMigration;

class M17807AdicionaMenuLotacaoDeServidores extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228480, 'Lotação de Servidores', 'Lotação de Servidores', 'edu1_lotacaoservidores001.php', '1',
                    '1', 'Lotação de servidores', 'true');

            delete from db_menu
            where id_item_filho = 228480 AND modulo = 7159;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (3333, 228480, 23, 7159);
SQL

        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu
            where id_item_filho = 228480;

            delete from db_itensmenu
            where id_item = 228480;
SQL
        );
    }
}


