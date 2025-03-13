<?php

use Classes\PostgresMigration;

class M18965AjusteRecurso extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
create temporary table w_acerto_conlancamcomplementorecurso as
 select c70_codlan, c75_numemp, c70_valor, o201_orctiporec, o201_complemento, o206_recurso,  o206_complementorecurso
   from conlancam
   join conlancamemp on c75_codlan = c70_codlan
   join conlancamcomplementorecurso on o201_codlan = c70_codlan
   join origemcomplementorecurso on o206_origem = 1 and o206_numero = c75_numemp
 where c70_anousu = 2021
   and o201_orctiporec != o206_recurso
   and not exists(select 1 from empresto where e91_numemp = c75_numemp)

union all

 select c70_codlan, c75_numemp, c70_valor, o201_orctiporec, o201_complemento, o206_recurso,  o206_complementorecurso
  from conlancam
  join conlancamemp on c75_codlan = c70_codlan
  join empresto on e91_numemp = c75_numemp
               and e91_anousu = c70_anousu
  join conlancamcomplementorecurso on o201_codlan = c70_codlan
  join origemcomplementorecurso on o206_origem = 10
                               and o206_numero = c75_numemp
  where c70_anousu = 2021
  and o201_orctiporec != o206_recurso;

update conlancamcomplementorecurso set o201_orctiporec = o206_recurso, o201_complemento = o206_complementorecurso
  from w_acerto_conlancamcomplementorecurso
 where o201_codlan = w_acerto_conlancamcomplementorecurso.c70_codlan;
SQL
);
    }
}
