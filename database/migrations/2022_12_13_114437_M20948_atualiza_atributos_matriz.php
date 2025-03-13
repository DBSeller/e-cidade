<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948AtualizaAtributosMatriz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

update conplanoinfocomplementar set c121_sql = 'with origem_lancamento as (
   select c70_codlan, c70_anousu, c75_numemp, c73_coddot
   FROM conlancam
   INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
   INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
   LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
   LEFT JOIN conlancamdot ON c73_codlan = c70_codlan
   where c70_codlan = codigo_lancamento
), desdobramento as  (
   select origem_lancamento.*, conta
     from origem_lancamento
     join conlancamele ON c67_codlan = c70_codlan
     join conplanoorcamento on conplanoorcamento.c60_codcon = conlancamele.c67_codele
          and conplanoorcamento.c60_anousu = origem_lancamento.c70_anousu
     join planodespesaconplanoorcamento on conplanoorcamento_codigo = conplanoorcamento.c60_codigo
     join planodespesa on planodespesa.id = planodespesaconplanoorcamento.planodespesa_id
            and planodespesa.uniao is true
), elemento as (

   select origem_lancamento.*, conta
     from origem_lancamento
     join conlancamdot on c73_codlan = c70_codlan
     join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot
          and orcdotacao.o58_anousu = conlancamdot.c73_anousu
     join conplanoorcamento on conplanoorcamento.c60_codcon = orcdotacao.o58_codele
          and conplanoorcamento.c60_anousu = origem_lancamento.c70_anousu
     join planodespesaconplanoorcamento on conplanoorcamento_codigo = conplanoorcamento.c60_codigo
     join planodespesa on planodespesa.id = planodespesaconplanoorcamento.planodespesa_id
          and planodespesa.uniao is true
)
select
   case
     when c75_numemp is not null
       then (select conta
               from desdobramento
               join origem_lancamento on origem_lancamento.c70_codlan = desdobramento.c70_codlan
            )
     else (select conta
             from elemento
             join origem_lancamento on origem_lancamento.c70_codlan = elemento.c70_codlan
          )
   end as infocomplementar_valor
 from origem_lancamento
 limit 1' where c121_sigla = 'ND';

update conplanoinfocomplementar set c121_sql = '
 select distinct planoreceita.conta as infocomplementar_valor
   from contabilidade.conlancam
   join contabilidade.conlancamdoc on c71_codlan = c70_codlan
   join contabilidade.conhistdoc on c53_coddoc = c71_coddoc
   join contabilidade.conlancamrec on c74_codlan = c70_codlan
   join orcamento.orcreceita on c74_codrec = o70_codrec
        and c74_anousu = o70_anousu
   join conplanoorcamento on conplanoorcamento.c60_codcon = orcreceita.o70_codfon
        and conplanoorcamento.c60_anousu = orcreceita.o70_anousu
   join planoreceitaconplanoorcamento on conplanoorcamento_codigo = conplanoorcamento.c60_codigo
   join planoreceita on planoreceita.id = planoreceitaconplanoorcamento.planoreceita_id
        and planoreceita.uniao is true
where c70_codlan = codigo_lancamento limit 1' where c121_sigla = 'NR';
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update conplanoinfocomplementar set c121_sql = '
SELECT distinct (CASE WHEN c75_codlan IS NOT NULL THEN conlanele.o56_elemento::varchar
                  ELSE eledot.o56_elemento::varchar END) AS infocomplementar_valor
  FROM conlancam
INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
LEFT JOIN empempenho ON c75_numemp = e60_numemp
LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
                  AND e60_anousu = dotemp.o58_anousu
LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
                   AND dotemp.o58_anousu = eleemp.o56_anousu
LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
                    AND c73_anousu = dotlan.o58_anousu
LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
                    AND dotlan.o58_anousu = eledot.o56_anousu
left join conlancamele   on c67_codlan = c70_codlan
left join orcelemento conlanele on c67_codele = conlanele.o56_codele
                       and c70_anousu = conlanele.o56_anousu
WHERE c70_Codlan = codigo_lancamento limit 1' where c121_sigla = 'ND';

update conplanoinfocomplementar set c121_sql = '
SELECT distinct orcfontes.o57_fonte AS infocomplementar_valor
  FROM contabilidade.conlancam
  INNER JOIN contabilidade.conlancamdoc ON c71_codlan = c70_codlan
  INNER JOIN contabilidade.conhistdoc ON c53_coddoc = c71_coddoc
  LEFT JOIN contabilidade.conlancamrec ON c74_codlan = c70_codlan
  LEFT JOIN orcamento.orcreceita ON c74_codrec = o70_codrec
                                AND c74_anousu = o70_anousu
  LEFT JOIN orcamento.orcfontes  ON o57_codfon = o70_codfon
                                    AND o57_anousu = o70_anousu
WHERE c70_Codlan = codigo_lancamento limit 1' where c121_sigla = 'NR';
SQL
        );
    }
}
