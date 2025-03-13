<?php

use Classes\PostgresMigration;

class M19313LiberaMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set libcliente = true where id_item = 228583;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set libcliente = false where id_item = 228583;");
    }
}
