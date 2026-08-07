<?php

use Classes\PostgresMigration;

class M17153InativarRecursoMigracao extends PostgresMigration
{
    public function change()
    {
        $this->execute("
            update orcamento.orctiporec set o15_datalimite = '2019-12-31'
             where o15_codigo = 0 and o15_datalimite is null
        ");
    }
}
