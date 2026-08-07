<?php

use Classes\PostgresMigration;

class M13281AnexoXiiFormula extends PostgresMigration
{
    public function up()
    {
        $sql = "update orcparamseqorcparamseqcoluna 
                set o116_formula = '(F[36])-(F[47])' 
                where o116_codparamrel = 194 and o116_codseq = 48
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "update orcparamseqorcparamseqcoluna 
                set o116_formula = 'F[36]-F[47]' 
                where o116_codparamrel = 194 and o116_codseq = 48
        ";
        $this->execute($sql);
    }
}
