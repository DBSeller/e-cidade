<?php

use Classes\PostgresMigration;

class M16424RemoveUniqueBnccInfantil extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        DROP INDEX IF EXISTS bncceducacaoinfantil_codigo_in;
        CREATE INDEX bncceducacaoinfantil_codigo_in ON escola.bncceducacaoinfantil(ed147_codigo);
        ");
    }

    public function down()
    {
        $this->execute("
        DROP INDEX IF EXISTS bncceducacaoinfantil_codigo_in;
        CREATE UNIQUE INDEX bncceducacaoinfantil_codigo_in ON escola.bncceducacaoinfantil(ed147_codigo);
        ");
    }
}
