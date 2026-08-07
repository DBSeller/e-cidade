<?php

use Classes\PostgresMigration;

class M15958SchemaPortalServidor extends PostgresMigration
{
    public function up()
    {
        $this->execute("CREATE SCHEMA IF NOT EXISTS portaldoservidor;");
    }

    public function down()
    {
    }
}
