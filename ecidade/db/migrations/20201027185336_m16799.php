<?php

use Classes\PostgresMigration;

class M16799 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        with empenhos_ajustar as (
            SELECT e60_numemp,
                   o15_codigo,
                   o15_complemento
            FROM empempenho
            JOIN orcdotacao ON e60_anousu = o58_anousu
            AND e60_coddot = o58_coddot
            JOIN orctiporec ON o15_codigo = o58_codigo
            WHERE NOT exists
                (SELECT 1
                 FROM origemcomplementorecurso
                 WHERE o206_numero = e60_numemp
                   AND o206_origem = 1)
        ), preparar_insert as (
            select 1, e60_numemp, o15_codigo, o15_complemento
              from empenhos_ajustar
        )
        insert into origemcomplementorecurso
            (o206_origem, o206_numero, o206_recurso, o206_complementorecurso)
          select * from preparar_insert
        ");
    }

    public function down()
    {

    }
}
