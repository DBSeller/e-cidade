<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20451AjusteConplanoinfocomplementarND extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
              WHERE c70_Codlan = codigo_lancamento limit 1
'
where c121_sequencial = 5;
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
        //
    }
}
