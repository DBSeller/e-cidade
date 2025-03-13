<?php

use Classes\PostgresMigration;

class M11422CriacaoIndiceJornadaservidor extends PostgresMigration
{

    public function up()
    {
        $sql = "create index jornadaservidor_rh212_matricula_data_in on jornadaservidor(rh212_matricula, rh212_data);";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "drop index jornadaservidor_rh212_matricula_data_in;";
        $this->execute($sql);
    }
}
