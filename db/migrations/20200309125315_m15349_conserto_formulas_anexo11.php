<?php

use Classes\PostgresMigration;

class M15349ConsertoFormulasAnexo11 extends PostgresMigration
{
    public function down()
    {
    }

    public function up()
    {
        $this->execute(<<<SQL_UP

update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->prevatu - L[1]->recatebim' where o116_codparamrel = 201 and o116_codseq = 1 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_inicial_prevadic - #saldo_arrecadado' where o116_codparamrel = 201 and o116_codseq = 2 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_inicial_prevadic - #saldo_arrecadado' where o116_codparamrel = 201 and o116_codseq = 3 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_inicial_prevadic - #saldo_arrecadado' where o116_codparamrel = 201 and o116_codseq = 4 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_inicial_prevadic - #saldo_arrecadado' where o116_codparamrel = 201 and o116_codseq = 5 and o116_ordem = 3;

SQL_UP
);
    }
}
