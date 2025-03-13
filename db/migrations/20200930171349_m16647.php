<?php

use Classes\PostgresMigration;

class M16647 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu
            VALUES (228328,
                    'Relatório Exercício x Procedência - Cobrança Administrativa',
                    'arr2_exercproced001.php',
                    'arr2_exercproced001.php',
                    1,
                    1,
                    'Relatório Exercício x Procedência - Cobrança Administrativa',
                    'true');


            INSERT INTO db_menu
            VALUES (30,
                    228328,
                    826,
                    1985522);
                    ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 228328;
            DELETE FROM db_itensmenu WHERE id_item = 228328;
        ";

        $this->execute($sql);
    }
}
