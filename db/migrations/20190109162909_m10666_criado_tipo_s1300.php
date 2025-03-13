<?php

use Classes\PostgresMigration;

class M10666CriadoTipoS1300 extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into recursoshumanos.esocialformulariotipo values (29, 'S-1300 - Contribuição Sindical Patronal')");
    }

    public function down()
    {
        $this->execute("delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 29");
    }
}
