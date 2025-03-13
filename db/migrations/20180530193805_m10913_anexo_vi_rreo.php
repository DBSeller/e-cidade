<?php

use Classes\PostgresMigration;

class M10913AnexoViRreo extends PostgresMigration
{
    public function up()
    {
        $this->execute(
<<<SQL
    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
SQL
        );
    }

    public function down()
    {
        $this->execute(
<<<SQL
    update orcparamseqorcparamseqcoluna 
       set o116_formula = 'L[68]->saldo_bimestre_anterior - L[68]->saldo_bimestre_atual'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 6;

    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 7;

    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 8;

    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 9;

    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 10;

    update orcparamseqorcparamseqcoluna 
       set o116_formula = '(L[69]->saldo - L[70]->saldo - L[71]->saldo + L[72]->saldo + L[73]->saldo)'
     where o116_codparamrel = 177
       and o116_codseq = 74
       and o116_periodo = 11;
SQL
        );
    }
}
