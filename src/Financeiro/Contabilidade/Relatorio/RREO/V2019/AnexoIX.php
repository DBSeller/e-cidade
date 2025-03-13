<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

/**
 * Class AnexoIX
  * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019
 */
class AnexoIX extends \AnexoIXRREO_2015
{
    const CODIGO_RELATORIO = 202;

    /**
     * AnexoIX constructor.
     *
     * @param $iAnoUsu
     * @param $iCodigoRelatorio
     * @param $iCodigoPeriodo
     */
    public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
    {

        parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
        $this->oPdf     = new \PDFDocument();
        $this->iAltura  = 3;
        $this->iLargura = $this->oPdf->getAvailWidth() - 10;
    }

    /**
     * @throws \Exception
     */
    protected function processarInformacoes()
    {
        $this->aLinhas = $this->getDados();
        if ($this->getAno() >= 2020) {
            $this->aLinhas[9]->colunas[0]->o116_formula = 'L[1]->prevatu - L[8]->dot_atual';
            $this->aLinhas[9]->colunas[1]->o116_formula = 'L[1]->recrealiza - L[8]->empenhado';
            $this->aLinhas[9]->colunas[2]->o116_formula = 'L[1]->saldo - L[8]->saldo';
            $this->processarFormulaDaLinha(9);
        }
        $this->processaTotalizadores($this->aLinhas);
    }

    /**
     * @throws \ParameterException
     * @throws \Exception
     */
    public function emitir()
    {
        $this->processarInformacoes();
        $this->configurarRelatorio();

        $label = 'RREO - ANEXO 9 (LRF, art.53, § 1o, inciso I)';
        $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, $label, 'B', 0, "L", 0);
        $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'Em Reais', 'B', 1, "R", 0);

        $this->escreverReceitas();
        $this->escreverDespesas();
        $this->escreverRegraOuro();

        /**
         * Assinaturas e nota explicativa
         */
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->setBold(false);
        $iAlturaAssinatura = 26;
        $this->notaExplicativa($this->oPdf, array($this->oPdf, 'AddPage'), $iAlturaAssinatura);
        $this->oRelatorioLegal->assinatura($this->oPdf, 'LRF', false);

        $this->oPdf->showPDF("AnexoIXRREO_" . time());
    }

    protected function escreverDespesas()
    {

        $this->oPdf->setBold(true);
        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.27, $this->iAltura * 3, 'DESPESAS', 'TBR', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.13, $this->iAltura * 1.5, "DOTAÇÃO ATUALIZADA\n(d)", 'TBRL', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.45, $this->iAltura * 1.5, "DESPESAS EMPENHADAS\n(e)", 'TBRL', 'C');
        $this->oPdf->setAutoNewLineMulticell(true);
        $label = "SALDO NÃO EXECUTADO \n (f) = (d - e)";
        $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 1.5, $label, 'TBL', 'C');

        $this->oPdf->setBold(false);
        for ($iLinha = self::LINHA_INICIO_DESPESAS; $iLinha <= 8; $iLinha++) {
            $oLinha = $this->aLinhas[$iLinha];
            $this->oPdf->Cell($this->iLargura * 0.27, $this->iAltura, $oLinha->descricao, 'BR', 0, "L", 0);
            $valor = db_formatar($oLinha->dot_atual, 'f');
            $this->oPdf->Cell($this->iLargura * 0.13, $this->iAltura, $valor, 'BRL', 0, "R", 0);
            $valor = db_formatar($oLinha->empenhado, 'f');
            $this->oPdf->Cell($this->iLargura * 0.45, $this->iAltura, $valor, 'BRL', 0, "R", 0);
            $valor = db_formatar($oLinha->saldo, 'f');
            $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, $valor, 'BL', 1, "R", 0);
        }
        $this->oPdf->ln(4);
    }

    /**
     * Escreve a regra de ouro de acordo com o ano.
     */
    protected function escreverRegraOuro()
    {
        if ($this->getAno() >= 2020) {
            $this->regraDeOuro2020();
        }

        if ($this->getAno() <= 2019) {
            $this->regraDeOuro2019();
        }
    }

    /**
     * Imprime a linha da regra de ouro de 2020.
     */
    protected function regraDeOuro2020()
    {

        $oLinha = $this->aLinhas[9];
        $oLinha->descricao = str_replace('(III) = (I - II)', '(III) = (II - I)', $oLinha->descricao);
        $this->oPdf->setBold(true);
        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.26, $this->iAltura * 1.5, $oLinha->descricao, 'TBR', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 3, '(d - a)', 'TBRL', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.45, $this->iAltura * 3, '(e - b)', 'TBRL', 'C');
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 3, '(f - c)', 'TBL', 'C');

        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLargura * 0.26, $this->iAltura, '', 'BR', 0, "L", 0);
        $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->dotatu, 'f'), 'BRL', 0, "R", 0);
        $this->oPdf->Cell($this->iLargura * 0.45, $this->iAltura, db_formatar($oLinha->despemp, 'f'), 'BRL', 0, "R", 0);
        $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
        $this->oPdf->ln(2);
    }

    /**
     * Imprime a linha da regra de ouro de 2019.
     */
    protected function regraDeOuro2019()
    {
        $oLinha = $this->aLinhas[9];
        $this->oPdf->setBold(true);
        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.26, $this->iAltura * 1.5, $oLinha->descricao, 'TBR', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 3, '(a - d)', 'TBRL', 'C');
        $this->oPdf->MultiCell($this->iLargura * 0.45, $this->iAltura * 3, '(b - e)', 'TBRL', 'C');
//        $this->oPdf->MultiCell($this->iLargura * 0.13, $this->iAltura * 3, '-', 'TBL', 'C');
//        $this->oPdf->MultiCell($this->iLargura * 0.18, $this->iAltura * 3, '-', 'TBL', 'C');
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 3, '(c - f)', 'TBL', 'C');

        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLargura * 0.26, $this->iAltura, '', 'BR', 0, "L", 0);
        $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->dotatu, 'f'), 'BRL', 0, "R", 0);
        $this->oPdf->Cell($this->iLargura * 0.45, $this->iAltura, db_formatar($oLinha->despemp, 'f'), 'BRL', 0, "R", 0);
//        $this->oPdf->Cell($this->iLargura * 0.13, $this->iAltura, '-', 'BRL', 0, 'C', 0);
//        $this->oPdf->Cell($this->iLargura * 0.18, $this->iAltura, '-', 'BRL', 0, 'C', 0);
        $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
        $this->oPdf->ln(2);
    }
}
