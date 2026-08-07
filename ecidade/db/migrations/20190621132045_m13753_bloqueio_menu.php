<?php

use Classes\PostgresMigration;

class M13753BloqueioMenu extends PostgresMigration
{

    public function up()
    {
        $this->execute("update db_itensmenu set libcliente = 'false' where id_item = 5577;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set libcliente = 'true' where id_item = 5577;");
    }
}
