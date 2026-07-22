<?php

use Classes\PostgresMigration;

class M10839CorrecaoFormulasAnexoViiiRreo extends PostgresMigration
{
    function up()
    {
        $sSql = "
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->previni - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 1;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->prevatu - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 2;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->recatebim - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 3;
            
            update orcparamseqorcparamseqcoluna set o116_formula = '(L[54]->liquidado_atebim+L[55]->liquidado_atebim+L[57]->liquidado_atebim+L[58]->liquidado_atebim) - (F[66])' where o116_codparamrel = 179 and o116_codseq = 67 and o116_ordem = 1;
            
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[75]->dotini + L[76]->dotini + L[78]->dotini + L[79]->dotini + L[81]->dotini + L[82]->dotini + L[83]->dotini + L[84]->dotini + L[85]->dotini + L[86]->dotini' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 1;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[75]->dotatu + L[76]->dotatu + L[78]->dotatu + L[79]->dotatu + L[81]->dotatu + L[82]->dotatu + L[83]->dotatu + L[84]->dotatu + L[85]->dotatu + L[86]->dotatu' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 2;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[75]->empenhado_atebim + L[76]->empenhado_atebim + L[78]->empenhado_atebim + L[79]->empenhado_atebim + L[81]->empenhado_atebim + L[82]->empenhado_atebim + L[83]->empenhado_atebim + L[84]->empenhado_atebim + L[85]->empenhado_atebim + L[86]->empenhado_atebim' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 3;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[75]->liquidado_atebim + L[76]->liquidado_atebim + L[78]->liquidado_atebim + L[79]->liquidado_atebim + L[81]->liquidado_atebim + L[82]->liquidado_atebim + L[83]->liquidado_atebim + L[84]->liquidado_atebim + L[85]->liquidado_atebim + L[86]->liquidado_atebim' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 4;
        ";
        $this->execute($sSql);
    }

    function down()
    {
        $sSql = "
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->previni - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 1;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->prevatu - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 2;
            update orcparamseqorcparamseqcoluna set o116_formula = 'L[49]->recatebim - (F[41])' where o116_codparamrel = 179 and o116_codseq = 52 and o116_ordem = 3;
            
            update orcparamseqorcparamseqcoluna set o116_formula = 'F[59] - F[66]' where o116_codparamrel = 179 and o116_codseq = 67 and o116_ordem = 1;
            
            update orcparamseqorcparamseqcoluna set o116_formula = 'F[73] + F[80] + L[83]->dotini + L[84]->dotini + L[85]->dotini + L[86]->dotini' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 1;
            update orcparamseqorcparamseqcoluna set o116_formula = 'F[73] + F[80] + L[83]->dotatu + L[84]->dotatu + L[85]->dotatu + L[86]->dotatu' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 2;
            update orcparamseqorcparamseqcoluna set o116_formula = 'F[73] + F[80] + L[83]->empenhado_atebim + L[84]->empenhado_atebim + L[85]->empenhado_atebim + L[86]->empenhado_atebim' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 3;
            update orcparamseqorcparamseqcoluna set o116_formula = 'F[73] + F[80] + L[83]->liquidado_atebim + L[84]->liquidado_atebim + L[85]->liquidado_atebim + L[86]->liquidado_atebim' where o116_codparamrel = 179 and o116_codseq = 87 and o116_ordem = 4;
        ";
        $this->execute($sSql);
    }
}
