<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\Layout;
use relatorioContabil;

/**
 * Class AnexoV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\Layout
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

        $this->pdf->Rect($xInicial, $yInicial, 58, 25, 'DF');

        $iLarguraAtual += 58;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 20, 25, 'DF');

        $this->pdf->Rect($iLarguraAtual + 20, 48, 25, 17, 'DF');
        $this->pdf->Rect($iLarguraAtual + 45, 48, 25, 17, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 100, 4, 'DF');
        $iAlturaAtual += 4;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 50, 4, 'DF');

        $iLarguraAtual += 50;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 25, 21, 'DF');
        $iLarguraAtual += 25;
        $this->pdf->Rect($iLarguraAtual, $iAlturaAtual, 25, 21, 'DF');

        $iLarguraAtual += 25;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 20, 25, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 29, 25, 'DF');

        $iLarguraAtual += 29;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 20, 25, 'DF');

        $iLarguraAtual += 20;
        $this->pdf->Rect($iLarguraAtual, $yInicial, 29, 25, 'DF');

        $this->pdf->Cell(0, 2, "", "", 1, "");
        $this->pdf->SetFontSize(6);
        $this->pdf->Cell(142, 3, "RGF - ANEXO 5 (LRF, Art. 55, Inciso III, alínea 'a')", "", 0, "L");
        $this->pdf->Cell(135, 3, "R$ 1,00", "", 1, "R");

        $yInicial = $this->pdf->GetY();
        $xInicial = $this->pdf->GetX();

        $this->pdf->SetBold(1);
        $this->pdf->SetFontSize(5);

        $iLarguraLinha = 58;
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

        $iLarguraLinha = 100;
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
        $this->pdf->MultiCell($iLarguraLinha, 4,
            'DISPONIBILIDADE DE CAIXA LÍQUIDA (ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO) ¹',
            '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 20;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 29;
        $this->pdf->MultiCell($iLarguraLinha, 4,
            'EMPENHOS NÃO LIQUIDADOS CANCELADOS (NÃO INSCRITOS POR INSUFICIÊNCIA FINANCEIRA)', '', 'C');

        /**
         * 2º linha
         */
        $yInicial = 44;
        $iLarguraAtual = $this->pdf->GetX() + 78;
        $iLarguraLinha = 50;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($this->pdf->GetX() + 78);
        $this->pdf->MultiCell($iLarguraLinha, 4, 'Restos a Pagar Liquidados e Não Pagos ', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $iLarguraLinha = 25;
        $this->pdf->MultiCell($iLarguraLinha, 4, 'Restos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores',
            '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'Demais Obrigações Financeiras', '', 'C');

        $yInicial = 48;
        $iLarguraAtual = $this->pdf->GetX() + 78;
        $iLarguraLinha = 25;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'De Exercícios Anteriores', '', 'C');
        $iLarguraAtual += $iLarguraLinha;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->MultiCell($iLarguraLinha, 4, 'Do Exercício', '', 'C');

        $yInicial = 60;
        $iLarguraAtual = $this->pdf->GetX() + 58;
        $this->pdf->SetY($yInicial);
        $this->pdf->SetX($iLarguraAtual);

        $this->pdf->Cell(20, 4, '(a) ', '', 0, 'C');
        $this->pdf->Cell(25, 4, '(b) ', '', 0, 'C');
        $this->pdf->Cell(25, 4, '(c) ', '', 0, 'C');
        $this->pdf->Cell(25, 4, '(d) ', '', 0, 'C');
        $this->pdf->Cell(25, 4, '(e) ', '', 0, 'C');
        $this->pdf->Cell(20, 4, '(f) ', '', 0, 'C');
        $this->pdf->Cell(29, 4, '(g) = (a - (b + c + d + e) - f)', '', 1, 'C');

        $yInicial = 65;
        $this->pdf->SetBold(0);
        $this->pdf->SetY($yInicial);

        foreach ($this->relatorio->getDados() as $item) {
            $this->pdf->SetFont('Arial', '', 5);

            if ($item->totalizar) {
                $this->pdf->SetFont('Arial', 'B', 6);
            }

            $descricao = relatorioContabil::getIdentacao($item->nivel) . $item->descricao;

            $iLinhas = $this->pdf->NbLines(58, $descricao);
            $yAntes = $this->pdf->GetY();

            $iAlturaLinha = 5 * $iLinhas;

            $this->pdf->MultiCell(58, 5, $descricao, 1);
            $this->pdf->SetY($yAntes);
            $this->pdf->SetX($this->pdf->GetX() + 58);
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->disp_caixa), 1, 0, 'R');
            $this->pdf->Cell(25, $iAlturaLinha, $this->formatValue($item->exanterior), 1, 0, 'R');
            $this->pdf->Cell(25, $iAlturaLinha, $this->formatValue($item->vlrexatual), 1, 0, 'R');
            $this->pdf->Cell(25, $iAlturaLinha, $this->formatValue($item->rp_nprocexant), 1, 0, 'R');
            $this->pdf->Cell(25, $iAlturaLinha, $this->formatValue($item->financeira), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->insuficiencia_financeira), 1, 0, 'R');
            $this->pdf->Cell(29, $iAlturaLinha, $this->formatValue($item->disp_caixa_liquida), 1, 0, 'R');
            $this->pdf->Cell(20, $iAlturaLinha, $this->formatValue($item->rp_empenhado_nao_processado), 1, 0, 'R');
            $this->pdf->Cell(29, $iAlturaLinha, $this->formatValue($item->empenho_nao_liquidado_cancelado), 1, 1, 'R');
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
