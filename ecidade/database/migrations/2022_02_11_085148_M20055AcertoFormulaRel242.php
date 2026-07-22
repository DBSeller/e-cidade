<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20055AcertoFormulaRel242 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update orcparamseqorcparamseqcoluna
   set o116_formula = 'L[18]->vlrexanter + F[15] + F[16] + F[17]'
 where o116_codseq = 14 and o116_codparamrel = 242 and o116_ordem = 2;
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
