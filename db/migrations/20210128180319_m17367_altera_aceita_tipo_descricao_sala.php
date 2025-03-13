<?php

use Classes\PostgresMigration;

class M17367AlteraAceitaTipoDescricaoSala extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_syscampo set aceitatipo = 3 where codcam = 1008240");
    }

    public function down()
    {
        $this->execute("update db_syscampo set aceitatipo = 0 where codcam = 1008240");
    }
}
