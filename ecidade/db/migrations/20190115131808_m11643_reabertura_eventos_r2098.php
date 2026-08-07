<?php

use Classes\PostgresMigration;

class M11643ReaberturaEventosR2098 extends PostgresMigration
{
    public function up()
    {
        $this->incluirFormulario();
    }

    public function down()
    {
        $this->removerFormulario();
    }

    private function incluirFormulario()
    {
        $sql =
<<<SQL
        INSERT INTO esocialformulariotipo 
        VALUES (33, 'R-2098 - Reabertura de Eventos Periódicos');
SQL;
        $this->execute($sql);
    }

    private function removerFormulario()
    {
        $sql =
<<<SQL
        DELETE FROM esocialformulariotipo WHERE rh209_sequencial = 33;
SQL;
        $this->execute($sql);
    }
}
