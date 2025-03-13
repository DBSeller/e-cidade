<?php

use Classes\PostgresMigration;

class M10927Cnab240Finalidade extends PostgresMigration
{
    public function up()
    {
        $sSql = "insert into finalidadepagamentofundeb values (0 , '00', 'Não se aplica');";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "delete from finalidadepagamentofundeb where e151_sequencial = 0";
        $this->execute($sSql);
    }
}
