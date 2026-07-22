<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20001AjusteFormulaFluxoCaixaLinhaIntergovernamentais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL


update orcparamseqorcparamseqcoluna set o116_formula = 'L[44]->vlrexanter+L[45]->vlrexanter+L[46]->vlrexanter'
 where o116_codparamrel = 242
   and o116_orcparamseqcoluna = 467
   and o116_codseq = 43
   and o116_ordem = 2;


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
        //
    }
}
