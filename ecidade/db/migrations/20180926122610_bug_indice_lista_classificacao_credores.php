<?php

use Classes\PostgresMigration;

class BugIndiceListaClassificacaoCredores extends PostgresMigration
{
    public function up()
    {
        $this->execute("create index classificacaocredoresempenho_empempenho_in on classificacaocredoresempenho (cc31_empempenho);");
        $this->execute("create index classificacaocredoresempenho_classificacaocredores_in on classificacaocredoresempenho (cc31_classificacaocredores);");
    }

    public function down()
    {
        $this->execute("drop index classificacaocredoresempenho_empempenho_in");
        $this->execute("drop index classificacaocredoresempenho_classificacaocredores_in");
    }
}
