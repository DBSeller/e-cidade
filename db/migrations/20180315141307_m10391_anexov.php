<?php

use Classes\PostgresMigration;

class M10391Anexov extends PostgresMigration
{
    public function up()
    {
        $sql =  " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = '(L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) > 0 ? L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) : 0)' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 178; ";

        $sql .= " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = '(L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) > 0 ? L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) : 0)' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 57; ";

        $sql .= " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = '(L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) > 0 ? L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) : 0)' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 56; ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql =  " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = 'L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) > 0 ? L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) : 0' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 178; ";

        $sql .= " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = 'L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) > 0 ? L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) : 0' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 57; ";

        $sql .= " UPDATE orcparamseqorcparamseqcoluna ";
        $sql .= " SET o116_formula = 'L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) > 0 ? L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) : 0' ";
        $sql .= " WHERE o116_codparamrel = 162 ";
        $sql .= "   AND o116_codseq = 7 ";
        $sql .= "   AND o116_orcparamseqcoluna = 56; ";
        $this->execute($sql);
    }
}
