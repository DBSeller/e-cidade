<?php

use Classes\PostgresMigration;

class M16497AjusteConlancamRecurso extends PostgresMigration
{
    public function change()
    {
        $this->execute("
            with recuros_ajustar as (

                select o201_codlan as lancamento, o15_codigo, o15_recurso, o15_complemento, o201_complemento
                  from conlancamrecurso
                  join orctiporec on orctiporec.o15_codigo = conlancamrecurso.c130_orctiporec
                  join conlancamcomplementorecurso on conlancamcomplementorecurso.o201_codlan = conlancamrecurso.c130_conlancam
                 where o15_complemento != o201_complemento

            ), recurso_certo as (

                select lancamento,
                       orctiporec.o15_codigo as certo
                  from orctiporec
                  join recuros_ajustar on recuros_ajustar.o15_recurso = orctiporec.o15_recurso
                     and recuros_ajustar.o201_complemento = orctiporec.o15_complemento

            ) update conlancamrecurso set c130_orctiporec = certo
                from recurso_certo
               where conlancamrecurso.c130_conlancam = recurso_certo.lancamento;
        ");
    }
}
