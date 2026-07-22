<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\Layout;
use relatorioContabil;

/**
 * Class AnexoV
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\Layout
 */
class AnexoV extends Layout
{

    /**
     *
     */
    const HEADER_1 = 'RELATÓRIO DE GESTÃO FISCAL';
    /**
     *
     */
    const HEADER_2 = 'DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA E DOS RESTOS A PAGAR';
    /**
     *
     */
    const HEADER_3 = 'ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL';

    /**
     * @var string
     */
    protected $assinatura = 'GF';

    /**
     *
     */
    protected function montar()
    {
        $xInicial = 10;
        $yInicial = 40;
        $iLarguraAtual = $xInicial;
        $iAlturaAtual = $yInicial;

        $this->pdf->Rect($xInicial, $yInicial, 58, 29, 'DF');

        $iLarguraAtual += 55; // identificacao de recurso
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 20, 29, 'DF');

        $this->pdf->Rect($iLarguraAtual + 20, 48, 25, 21, 'DF');
        $this->pdf->Rect($iLarguraAtual + 40, 48, 25, 21, 'DF');

        $iLarguraAtual += 20; // disponibilidade de caixa bruta
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 100, 4, 'DF');
        $iAlturaAtual += 4;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 50, 4, 'DF');

        $iLarguraAtual += 40;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 20, 25, 'DF');
        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 20, 25, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 20, 29, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 29, 29, 'DF');

        $iLarguraAtual += 29;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 20, 29, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 26, 29, 'DF');

        $iLarguraAtual += 26;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 26, 29, 'DF');


        $this->pdf->Cell(0, 2, "", "", 1, "");
        $this->pdf->SetFontSize(6);
        $this->pdf->Cell(142, 3, "RGF - ANEXO 5 (LRF, Art. 55, Inciso III, alínea 'a')", "", 0, "L");
        $this->pdf->Cell(135, 3, "R$ 1,00", "", 1, "R");

        $yInicial = $this->pdf->GetY();
        $xInicial = $this->pdf->GetX();

        $this->pdf->SetBold(1);
        $this->pdf->SetFontSize(5);

        $iLarguraLinha = 55;
        $iLarguraAtual = $xInicial;

        $this->pdf->MultiCell($iLarguraLinha, 4, 'IDENTIFICAÇÃO DOS RECURSOS', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 20;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'DISPONIBILIDADE DE CAIXA BRUTA', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 80;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'OBRIGAÇÕES FINANCEIRAS', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 20;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'INSUFICIÊNCIA FINANCEIRA VERIFICADA NO CONSÓRCIO PÚBLICO', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 29;
        $this->pdf->MultiCell(
            $iLarguraLinha,
            4,
            'DISPONIBILIDADE DE CAIXA LÍQUIDA (ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO) ¹',
            '',
            'C'
        );
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 20;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 26;
        $this->pdf->MultiCell(
            $iLarguraLinha,
            4,
            'EMPENHOS NÃO LIQUIDADOS CANCELADOS (NÃO INSCRITOS POR INSUFICIÊNCIA FINANCEIRA)',
            '',
            'C'
        );

        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);
        $iLarguraLinha = 26;
        $this->pdf->MultiCell(
            $iLarguraLinha,
            4,
            'DISPONIBILIDADE DE CAIXA LÍQUIDA (APÓS A INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO)',
            '',
            'C'
        );

        /**
         * 2º linha
         */
        $yInicial = 44;
        $iLarguraAtual = $this->pdf->GetX() + 75;
        $iLarguraLinha = 40;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($this->pdf->GetX() + 75);
        $this->pdf->MultiCell($iLarguraLinha, 4, 'Restos a Pagar Liquidados e Não Pagos ', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 20;
        $this->pdf->MultiCell(
            $iLarguraLinha,
            4,
            'Restos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores',
            '',
            'C'
        );
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'Demais Obrigações Financeiras', '', 'C');

        $yInicial = 48;
        $iLarguraAtual = $this->pdf->GetX() + 75;
        $iLarguraLinha = 20;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'De Exercícios Anteriores', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'Do Exercício', '', 'C');

        $yInicial = 65;
//        $iLarguraAtual = $this->pdf->GetX() + 50;
        $this->pdf->SetY($yInicial);
//        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->cell(55, 4, '', '', 0);
        $this->pdf->Cell(20, 4, '(a) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(b) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(c) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(d) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(e) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(f) ', '', 0, 'C');
        $this->pdf->Cell(29, 4, '(g) = (a - (b + c + d + e) - f)', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(h)', '', 0, 'C');
        $this->pdf->Cell(26, 4, '', '', 0, 'C');
        $this->pdf->Cell(26, 4, '(i) = (g - h)', '', 1, 'C');

        $yInicial = 69;
        $this->pdf->SetBold(0);
        $this->pdf->SetY($yInicial);


        foreach ($this->relatorio->getDados() as $item) {
            $this->pdf->SetFont('Arial', '', 5);

            if ($item->totalizar) {
                $this->pdf->SetFont('Arial', 'B', 6);
            }

            $descricao = relatorioContabil::getIdentacao($item->nivel) . $item->descricao;

            $iLinhas = $this->pdf->NbLines(55, $descricao);
            $yAntes = $this->pdf->GetY();

            $iAlturaLinha = 5 * $iLinhas;

            $this->pdf->MultiCell(55, 5, $descricao, 1);
            $this->pdf->SetY($yAntes);
            $this->pdf->SetX($this->pdf->GetX() + 55);
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->disp_caixa), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->exanterior), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->vlrexatual), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->rp_nprocexant), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->financeira), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->insuficiencia_financeira), 1, 0, 'R');
            $this->pdf->Cell(29, $iAlturaLinha, $this->formatValue($item->disp_caixa_liquida), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->rp_empenhado_nao_processado), 1, 0, 'R');
            $this->pdf->Cell(26, $iAlturaLinha, $this->formatValue($item->empenho_nao_liquidado_cancelado), 1, 0, 'R');
            $this->pdf->Cell(26, $iAlturaLinha, $this->formatValue($item->disp_caixa_liquida_apos), 1, 1, 'R');
        }
    }

    /**
     * @param $value
     * @return string
     */
    protected function formatValue($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
