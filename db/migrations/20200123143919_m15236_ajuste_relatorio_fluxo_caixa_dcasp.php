<?php

use Classes\PostgresMigration;

class M15236AjusteRelatorioFluxoCaixaDcasp extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP

update orcparamseqorcparamseqcoluna
   set o116_formula = '(F[21])+(F[31])'
 where o116_codparamrel = 189 and o116_codseq = 32;


SQL_UP
);

    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

update orcparamseqorcparamseqcoluna
   set o116_formula = '(F[11])+(F[21])+(F[31])'
 where o116_codparamrel = 189 and o116_codseq = 32;


SQL_DOWN
);
    }
}
