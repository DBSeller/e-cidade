<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2022\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\Layout;

class AnexoX extends \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoX
{
    // phpcs:disable
    const HEADER_1 = 'RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA';
    const HEADER_2 = 'DEMONSTRATIVO DA PROJEÇÃO ATUARIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES E DAS PENSÕES E INATIVOS MILITARES';
    const HEADER_3 = "ORÇAMENTO DA SEGURIDADE SOCIAL";
    // phpcs:enable

    /**
     * Monta o cabeçalho do relatório de acordo com a linha informada.
     * @param int $linha
     */
    protected function montaCabecalho($linha)
    {
        $desricaoLinha = "FUNDO EM CAPITALIZAÇÃO (PLANO PREVIDENCIÁRIO)";

        if ($linha == 2) {
            $desricaoLinha = "FUNDO EM REPARTIÇÃO (PLANO FINANCEIRO)";
        }
        $this->pdf->SetFont('arial', 'b', 6);
        $this->pdf->Cell(191, $this->iAltura, $desricaoLinha, "T", 1, "C", 0);

        $this->pdf->Cell(23, $this->iAltura, "", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "RECEITAS", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "DESPESAS", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "RESULTADO", "TLR", 0, "C", 0);
        $this->pdf->Cell(48, $this->iAltura, "SALDO FINANCEIRO", "TL", 1, "C", 0);

        $this->pdf->Cell(23, $this->iAltura, "EXERCÍCIO", "R", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS", "LR", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS", "LR", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIO", "LR", 0, "C", 0);
        $this->pdf->Cell(48, $this->iAltura, "DO EXERCÍCIO", "L", 1, "C", 0);

        $this->pdf->Cell(23, $this->iAltura, "", "R", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "(a)", "L", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "(b)", "L", 0, "C", 0);
        $this->pdf->Cell(40, $this->iAltura, "(c)=(a-b)", "L", 0, "C", 0);
        $this->pdf->Cell(48, $this->iAltura, "(d)=('d' exercício anterior)+(c)", "L", 1, "C", 0);
        $this->pdf->SetFont('arial', '', 6);
    }
}
