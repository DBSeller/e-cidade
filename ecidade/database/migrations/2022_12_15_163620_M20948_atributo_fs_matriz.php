<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948AtributoFsMatriz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $doEmpenho = "lpad(femp.o52_siconfi, 2, \'0\')::varchar||lpad(sfemp.o53_siconfi, 3, \'0\')::varchar";
        $doLancamento = "lpad(flanc.o52_siconfi, 2, \'0\')::varchar||lpad(sfemp.o53_siconfi, 3, \'0\')::varchar";
        DB::connection()->getPdo()->exec(<<<SQL
update conplanoinfocomplementar set c121_sql = '
SELECT distinct (CASE WHEN c75_codlan IS NOT NULL THEN $doEmpenho ELSE $doLancamento END) AS infocomplementar_valor
from conlancam
inner join conlancamdoc on c71_codlan = c70_codlan
inner join conhistdoc on c53_coddoc = c71_coddoc
left join conlancamemp on c75_codlan = c70_codlan
left join empempenho on c75_numemp = e60_numemp
left join orcdotacao dotemp on e60_coddot = dotemp.o58_coddot
                           and e60_anousu = dotemp.o58_anousu
left join orcfuncao femp on femp.o52_funcao = dotemp.o58_funcao
left join orcsubfuncao sfemp on sfemp.o53_subfuncao = dotemp.o58_subfuncao

left join conlancamdot on c73_codlan = c70_codlan
left join orcdotacao dotlan on c73_coddot = dotlan.o58_coddot
                           and c73_anousu = dotlan.o58_anousu
left join orcfuncao flanc on flanc.o52_funcao = dotemp.o58_funcao
left join orcsubfuncao sflanc on sflanc.o53_subfuncao = dotemp.o58_subfuncao

where c70_codlan = codigo_lancamento limit 1
'
where c121_sigla = 'FS'
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
        $doEmpenho = "lpad(dotemp.o58_funcao, 2, \'0\')::varchar||lpad(dotemp.o58_subfuncao, 3, \'0\')::varchar";
        $doLancamento = "lpad(dotlan.o58_funcao, 2, \'0\')::varchar||lpad(dotlan.o58_subfuncao, 3, \'0\')::varchar";

        DB::connection()->getPdo()->exec(<<<SQL
update conplanoinfocomplementar set c121_sql = '
SELECT distinct (CASE WHEN c75_codlan IS NOT NULL THEN $doEmpenho ELSE $doLancamento END) AS infocomplementar_valor
  FROM conlancam
       INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
       INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
       LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
       LEFT JOIN empempenho ON c75_numemp = e60_numemp
       LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
                                  AND e60_anousu = dotemp.o58_anousu
       LEFT JOIN conlancamdot ON c73_codlan = c70_codlan
       LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot
                                  AND c73_anousu = dotlan.o58_anousu
 WHERE c70_Codlan = codigo_lancamento limit 1
 '
where c121_sigla = 'FS'
SQL
        );
    }
}
