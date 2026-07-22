<?php

use Classes\PostgresMigration;

class M16344RelatorioMovimentacaoEmpenho extends PostgresMigration
{
    public function up()
    {
        $this->upOrigemComplementoRecurso();
    }

    public function upOrigemComplementoRecurso() {
        $this->execute(
        <<<STRING1

			INSERT INTO origemcomplementorecurso ( o206_sequencial, o206_origem, o206_numero, o206_recurso, o206_complementorecurso)
            select nextval('origemcomplementorecurso_o206_sequencial_seq') as seq, 
                   100::int        as origem, 
                   e56_autori      as numero, 
                   o58_codigo      as recurso, 
                   o15_complemento as complemento 
              from empautidot inner join orcdotacao 
                on e56_anousu = o58_anousu 
               and e56_coddot = o58_coddot inner join orctiporec 
                on o58_codigo = o15_codigo 
             where e56_anousu = 2020 
               and e56_autori not in (select o206_numero from origemcomplementorecurso where o206_origem = 100);

			INSERT INTO origemcomplementorecurso ( o206_sequencial, o206_origem, o206_numero, o206_recurso, o206_complementorecurso)
            select nextval('origemcomplementorecurso_o206_sequencial_seq') as seq, 
                   1::int          as origem, 
                   e60_numemp      as numero, 
                   o58_codigo      as recurso, 
                   o15_complemento as complemento 
              from empempenho inner join orcdotacao 
                on e60_anousu = o58_anousu 
               and e60_coddot = o58_coddot inner join orctiporec 
                on o58_codigo = o15_codigo 
             where e60_anousu = 2020 
               and e60_numemp not in (select o206_numero from origemcomplementorecurso where o206_origem = 1); 

STRING1

        );
    }

    public function down()
    {
    }

}


