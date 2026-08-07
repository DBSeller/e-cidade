<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19986CorrecaoFormulaRelatorioAnexo14 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

update orcparamseqorcparamseqcoluna set o116_formula = '' where o116_codparamrel = 201 and o116_codseq = 13 and o116_ordem = 1;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->recatebim - (L[6]->desppag + L[6]->rp_apagar )' where o116_codparamrel = 201 and o116_codseq = 13 and o116_ordem = 2;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[13]->vlrexanter + L[13]->vlrexatual' where o116_codparamrel = 201 and o116_codseq = 13 and o116_ordem = 3;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


    }
}
