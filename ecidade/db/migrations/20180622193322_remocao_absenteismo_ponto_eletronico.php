<?php

use Classes\PostgresMigration;

class RemocaoAbsenteismoPontoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
delete from db_menu where id_item_filho = 10531 AND modulo = 2323;
delete from db_itensmenu where id_item = 10531;
SQL_UP
        );
    }

    public function down()
    {
    }
}
