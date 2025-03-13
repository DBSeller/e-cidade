<?php

use Classes\PostgresMigration;

class M13679AlteracaoMenu extends PostgresMigration
{
    public function up()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228122 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10388 ,228122 ,9 ,2323 );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228122 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,228122 ,14 ,2323 );
        ";
        $this->execute($sql);
    }

}
