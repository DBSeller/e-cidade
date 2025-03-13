<?php

use Classes\PostgresMigration;

class M13876ProrrogacaoLicensaMaternidade extends PostgresMigration
{
    public function up()
    {
        $sql = "INSERT INTO situacaoafastamento VALUES(9, 'Prorrogação Licença Maternidade')";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DELETE FROM situacaoafastamento WHERE rh166_sequencial = 9";
        $this->execute($sql);
    }
}
