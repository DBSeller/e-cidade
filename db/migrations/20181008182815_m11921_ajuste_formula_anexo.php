<?php

use Classes\PostgresMigration;

class M11921AjusteFormulaAnexo extends PostgresMigration
{
    public function up()
    {
        $sql = "
            UPDATE orcparamseqorcparamseqcoluna 
            SET o116_formula = '#saldo_final'
            WHERE o116_codparamrel = 175 
              AND o116_orcparamseqcoluna = 146
              AND o116_codseq = 79;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            UPDATE orcparamseqorcparamseqcoluna 
            SET o116_formula = ''
            WHERE o116_codparamrel = 175 
              AND o116_orcparamseqcoluna = 146
              AND o116_codseq = 79;
        ";

        $this->execute($sql);
    }
}
