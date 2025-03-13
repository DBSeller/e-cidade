<?php

use Classes\PostgresMigration;

class M13552Turmas extends PostgresMigration
{
    public function up()
    {
        $sql  = "
            DELETE
            FROM turmacensoetapa
            WHERE ed132_codigo IN (
                SELECT ed132_codigo
                FROM turmacensoetapa AS a
                WHERE a.ed132_ano = 2019 AND a.ed132_censoetapa = '22' AND exists(
                        SELECT 1 FROM turmacensoetapa AS b WHERE b.ed132_turma = a.ed132_turma AND b.ed132_ano <> 2019
                    )
            )";

        $this->execute($sql);
    }
}
