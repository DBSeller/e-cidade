<?php

use Classes\PostgresMigration;

class M16198AcertoEmpenhoComplemento extends PostgresMigration
{
    public function up()
    {
        $this->atualizaComplemento();
    }

    public function down()
    {

    }

    public function atualizaComplemento()
    {
        $sql = <<<SQL
            update orcamento.orctiporec set o15_complemento = 0 where o15_complemento is null;       
SQL;
        $this->execute($sql);
    }
}
