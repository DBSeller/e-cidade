<?php

use Classes\PostgresMigration;

class M10962CorrecaoFormulaAnexoIvRreo extends PostgresMigration
{
    public function up()
    {
        $this->execute("
          UPDATE orcparamseqorcparamseqcoluna
          SET o116_formula = '((L[1]->recbiexant + L[30]->recbiexant) - L[28]->recbiexant)'
          WHERE o116_codparamrel = 176
            AND o116_codseq = 34
            AND o116_orcparamseqcoluna = 185;
        ");
    }

    public function down()
    {
        $this->execute("
          UPDATE orcparamseqorcparamseqcoluna
          SET o116_formula = 'F[1] + F[30] - L[28]->recbiexant'
          WHERE o116_codparamrel = 176
            AND o116_codseq = 34
            AND o116_orcparamseqcoluna = 185;
        ");
    }
}
