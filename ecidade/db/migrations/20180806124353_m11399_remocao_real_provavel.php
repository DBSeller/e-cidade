<?php

use Classes\PostgresMigration;

class M11399RemocaoRealProvavel extends PostgresMigration
{
    public function up()
    {
        $sql  = "alter table previsaodespesa drop c333_provavel;";
        $sql .= "alter table previsaodespesa drop c333_real;";

        $this->execute($sql);
    }

    public function down()
    {
        $sql  = "alter table previsaodespesa add  c333_provavel numeric(15, 2) not null default 0;";
        $sql .= "alter table previsaodespesa add  c333_real numeric(15, 2) not null default 0;";

        $this->execute($sql);
    }
}
