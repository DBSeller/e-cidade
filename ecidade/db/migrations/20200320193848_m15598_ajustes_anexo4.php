<?php

use Classes\PostgresMigration;

class M15598AjustesAnexo4 extends PostgresMigration
{
    public function down() {

    }

    public function up() {
        $this->execute(<<<SQL_UP



update orcparamseq set o69_manual = false, o69_origem = 0, o69_totalizador = true where o69_codparamrel = 196 and o69_codseq in (115, 116, 112);

update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->dot_ini + L[114]->dot_ini' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 1;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->dot_atual + L[114]->dot_atual' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 2;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->emp_atebim + L[114]->emp_atebim' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->emp_atebimexant + L[114]->emp_atebimexant' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 4;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->liq_atebim + L[114]->liq_atebim' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 5;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->liq_atebimexant + L[114]->liq_atebimexant' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 6;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->rp_nproc + L[114]->rp_nproc' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 7;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[113]->rp_nprocexant + L[114]->rp_nprocexant' where o116_codparamrel = 196 and o116_codseq = 115 and o116_ordem = 8;

update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->prev_ini - (L[113]->dot_ini + L[114]->dot_ini)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 1;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->prev_atual - (L[113]->dot_atual + L[114]->dot_atual)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 2;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->rec_atebim - (L[113]->emp_atebim + L[114]->emp_atebim)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->recbiexant - (L[113]->emp_atebimexant + L[114]->emp_atebimexant)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 4;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->rec_atebim - (L[113]->liq_atebim + L[114]->liq_atebim)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 5;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->recbiexant - (L[113]->liq_atebimexant + L[114]->liq_atebimexant)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 6;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->rec_atebim - (L[113]->rp_nproc + L[114]->rp_nproc)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 7;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[112]->recbiexant - (L[113]->rp_nprocexant + L[114]->rp_nprocexant)' where o116_codparamrel = 196 and o116_codseq = 116 and o116_ordem = 8;


update orcparamseqorcparamseqcoluna set o116_formula = 'L[111]->prev_ini'   where o116_codparamrel = 196 and o116_codseq = 112 and o116_ordem = 1;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[111]->prev_atual' where o116_codparamrel = 196 and o116_codseq = 112 and o116_ordem = 2;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[111]->rec_atebim' where o116_codparamrel = 196 and o116_codseq = 112 and o116_ordem = 3;
update orcparamseqorcparamseqcoluna set o116_formula = 'L[111]->recbiexant' where o116_codparamrel = 196 and o116_codseq = 112 and o116_ordem = 4;


SQL_UP
);
    }
}
