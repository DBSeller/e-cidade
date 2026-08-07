<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22237RemoveColunasDuplicadasAnexosLegais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create temp table colunas_duplicadas as
select count(*), max(o116_sequencial), o116_codseq, o116_orcparamseqcoluna, o116_periodo
  from orcparamseqorcparamseqcoluna
 WHERE o116_codparamrel in (262, 259)
 group by o116_codseq, o116_orcparamseqcoluna, o116_periodo
 having count(*) > 1;

delete from orcparamseqorcparamseqcolunavalor
 using colunas_duplicadas
 where colunas_duplicadas.max = o117_orcparamseqorcparamseqcoluna;

delete from orcparamseqorcparamseqcoluna
 using colunas_duplicadas
 where colunas_duplicadas.max = o116_sequencial;
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
