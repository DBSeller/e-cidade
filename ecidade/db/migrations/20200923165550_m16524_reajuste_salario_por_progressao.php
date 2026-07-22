<?php

use Classes\PostgresMigration;

class M16524ReajusteSalarioPorProgressao extends PostgresMigration
{
    public function up()
    {
        $Sql = <<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228327, 'Por Progressão', 'pes1_reajusteprog001.php', 'pes1_reajusteprog001.php', '1', '1', 'Por Progressão',
                'true');


            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (5136, 228327, 3, 952);

SQL;
        $this->execute($Sql);
    }

    public function down()
    {
        $Sql = <<<SQL
            delete from db_menu where id_item_filho = 228327 AND modulo = 952;
            delete from db_itensmenu where id_item = 228327;
SQL;
        $this->execute($Sql);
    }

}
