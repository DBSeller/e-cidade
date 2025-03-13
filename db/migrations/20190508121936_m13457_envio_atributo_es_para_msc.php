<?php

use Classes\PostgresMigration;

class M13457EnvioAtributoEsParaMsc extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP

update conplanoinfocomplementar
   set c121_sql = 'select case when (finalidade_empenho.c119_tipo is null and finalidade_dotacao.c119_tipo is null) then \'0\'
            when (c75_codlan is not null) then finalidade_empenho.c119_tipo
            when (c73_codlan is not null) then finalidade_dotacao.c119_tipo
        end as tipo
from conlancam
         left join conlancamemp on c75_codlan = c70_codlan
         left join empempenho on c75_numemp  = e60_numemp
         left join siconfidotacaofinalidade AS finalidade_empenho on finalidade_empenho.c119_coddot = e60_coddot
    and finalidade_empenho.c119_anousu = e60_anousu
         left join conlancamdot on c75_codlan = c73_codlan
         left join siconfidotacaofinalidade  finalidade_dotacao on finalidade_empenho.c119_coddot = c73_coddot
    and finalidade_empenho.c119_anousu = c73_anousu
where c70_codlan = codigo_lancamento limit 1'
 where c121_sigla = 'ES';

update infocomplementarvalor
   set c123_valor = '0'
 where c123_valor = ''
   and c123_infocomplementar = 50 
   and c123_conplanosistema = 1;                                                                       

update conplanoatributosaldo
   set c125_hashcontaatributos = replace(c125_hashcontaatributos, '|#ES', '|0#ES')
 where c125_hashcontaatributos ilike '%|#ES%'
   and c125_anousu = 2019;
   
SQL_UP
);
    }


    public function down()
    {

    }

}
