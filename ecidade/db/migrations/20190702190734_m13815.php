<?php

use Classes\PostgresMigration;

class M13815 extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set libcliente = false where id_item = 9729;");
    }
}
