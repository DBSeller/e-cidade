<?php

use Classes\PostgresMigration;

class M10528AdicionaTipoEmissao extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into caixa.cadtipomod values(28, 'EMISSAO RECIBO COM CUSTAS DBPREF');
            insert into caixa.cadtipomod values(29, 'EMISSAO CARNE COM CUSTAS DBPREF');
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            delete from caixa.cadtipomod where k46_sequencial = 28;
            delete from caixa.cadtipomod where k46_sequencial = 29;
SQL;
        $this->execute($sql);
    }
}
