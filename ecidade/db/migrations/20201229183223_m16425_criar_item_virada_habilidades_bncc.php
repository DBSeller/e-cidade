<?php

use Classes\PostgresMigration;

class M16425CriarItemViradaHabilidadesBncc extends PostgresMigration
{
    public function up()
    {
        $sql = "INSERT INTO db_viradacaditem VALUES (36, 'HABILIDADES BNCC');";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DELETE FROM db_viradacaditem WHERE c33_sequencial = 36;";
        $this->execute($sql);
    }
}
