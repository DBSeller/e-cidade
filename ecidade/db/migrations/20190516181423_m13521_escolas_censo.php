<?php

use Classes\PostgresMigration;

class M13521EscolasCenso extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO turmacensoetapa 
            SELECT nextval('turmacensoetapa_ed132_codigo_seq'), ed57_i_codigo, 22, 2019 
            FROM turma WHERE NOT exists (
                SELECT ed132_turma FROM turmacensoetapa WHERE ed132_turma = ed57_i_codigo AND ed132_ano = 2019
                );
        ";
        $this->execute($sql);

    }
}
