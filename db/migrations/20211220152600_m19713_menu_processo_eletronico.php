<?php

use Classes\PostgresMigration;

class M19713MenuProcessoEletronico extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

         update db_menu set id_item_filho = 228606 where id_item = 228605 and id_item_filho = 228605;
SQL;

        $this->execute($sql);

    }


    public function down()
    {

        $sql = <<<SQL

        update db_menu set id_item_filho = 228606 where id_item = 228605 and id_item_filho = 228605;
SQL;

                $this->execute($sql);

    }


}
