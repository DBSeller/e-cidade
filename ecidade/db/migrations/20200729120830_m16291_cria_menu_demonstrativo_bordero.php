<?php

use Classes\PostgresMigration;

class M16291CriaMenuDemonstrativoBordero extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu (
                        id_item,
                        descricao,
                        help,
                        funcao,
                        itemativo,
                        manutencao,
                        desctec,
                        libcliente)
                    values
                    (
                        228305,
                        'Demonstrativo de Borderô Sintético/Analítico',
                        'Demonstrativo de Borderô Sintético/Analítico',
                        'cai2_demonstrativo_bordero001.php',
                        '1',
                        '1',
                        'Demonstrativo de Borderô Sintético/Analítico',
                        'true'
                    );
            delete from db_menu where id_item_filho = 228305 AND modulo = 39;
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (147885, 228305, 4, 39);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228305 AND modulo = 39;
            delete from db_itensmenu where id_item = 228305;
SQL
        );
    }
}
