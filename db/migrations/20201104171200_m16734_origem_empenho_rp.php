<?php

use Classes\PostgresMigration;

class M16734OrigemEmpenhoRp extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        with empenhos_ajustar as (
            SELECT e60_numemp,
                   o15_codigo,
                   o15_complemento
            FROM empempenho
            join empresto on empresto.e91_numemp = empempenho.e60_numemp
            join orctiporec ON orctiporec.o15_codigo = empresto.e91_recurso
            WHERE NOT exists
                (SELECT 1
                 FROM origemcomplementorecurso
                 WHERE o206_numero = e60_numemp
                   AND o206_origem = 10)
             and e91_anousu = 2020
        ), preparar_insert as (
            select 10, e60_numemp, o15_codigo, o15_complemento
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
