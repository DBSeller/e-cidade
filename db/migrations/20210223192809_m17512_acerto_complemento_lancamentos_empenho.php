<?php

use Classes\PostgresMigration;

class M17512AcertoComplementoLancamentosEmpenho extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<SQL

          create temp table w_correto as
          select *
            from conlancamemp
          inner join conlancamdoc on c71_codlan = c75_codlan
          inner join conlancamcomplementorecurso on c75_codlan = o201_codlan
          where c71_coddoc = 1
          and extract(year from c71_data) :: int in (2020, 2021);


          update conlancamcomplementorecurso
             set o201_complemento = empcompl.o201_complemento
          from (
              select conlancamcomplementorecurso.o201_sequencial as sequencial, w_correto.o201_complemento
               from conlancamcomplementorecurso
               inner join conlancamemp on o201_codlan = c75_codlan
               inner join w_correto on  w_correto.c75_numemp = conlancamemp.c75_numemp
          ) as empcompl where empcompl.sequencial = o201_sequencial;

SQL;
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<SQL

         drop table w_correto;
SQL;

        $this->execute($sSql);

    }
}
