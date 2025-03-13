<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoXI as AnexoXI2018;

class AnexoXI extends AnexoXI2018
{
    /**
     * Código do Relatório
     * @type integer
     */
    const CODIGO_RELATORIO = 201;

    /**
     * @var \stdClass[]
     */
    protected $aLinhas;


    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $this->aLinhas = $this->carregarLinhasRelatorio();
        $this->processarFormulaColunaSaldo();
        $this->processarColunaRestosAPagar();
        $this->processaTotalizadores($this->aLinhas);
        return $this->aLinhas;
    }

    /**
     * Processa a coluna de SALDO para as linhas pré-definidas
     */
    private function processarFormulaColunaSaldo()
    {

        $linhasProcessar = array(8, 9, 10, 12);
        foreach ($linhasProcessar as $codigoLinha) {
            $stdLinha = $this->aLinhas[$codigoLinha];
            $this->aLinhas[$codigoLinha]->saldo = ($stdLinha->dot_atual - $stdLinha->despemp);
        }
    }

    private function processarColunaRestosAPagar()
    {

        $linhasDespesa = array();
        for ($linhaDespesa = 6; $linhaDespesa <= 12; $linhaDespesa++) {
            $this->aLinhas[$linhaDespesa]->rp_apagar = 0;


            if (in_array($linhaDespesa, array(8, 9, 10, 12))) {
                $this->aLinhas[$linhaDespesa]->colunas[6]->o116_formula= "(#vlrpag + #vlrpagnproc)";
                $linhasDespesa[] = $linhaDespesa;
                $this->processaValorManualPorLinhaEColuna($linhaDespesa, 5);
            }
        }
        $this->executarRestosPagar($linhasDespesa, 5);
    }


    public function emitir()
    {

        $this->oPdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
        $this->iAltura = 4;
        $this->iLargura = $this->iLargura = $this->oPdf->getAvailWidth() - 10;

        $this->aLinhas = $this->getDados();
        $aListaInstituicoes = $this->getInstituicoes(true);
        if (count($aListaInstituicoes) > 1) {
            $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
            $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oPrefeitura) . " - CONSOLIDAÇÃO";
        } else {
            $oInstituicao = current($aListaInstituicoes);
            $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
        }

        $this->oPdf->Open();
        $this->oPdf->AliasNbPages();
        $this->oPdf->SetAutoPageBreak(false);
        $this->oPdf->SetFillColor(235);
        $this->oPdf->addHeaderDescription($sDescricaoInstituicao);

        if (count($aListaInstituicoes) == 1) {
            $oInstituicao = current($aListaInstituicoes);

            if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
                $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
            }
        }

        $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
        $this->oPdf->addHeaderDescription("DEMONSTRATIVO DA RECEITA DE ALIENAÇÃO DE ATIVOS E APLICAÇÃO DOS RECURSOS");
        $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
        $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");
        $this->oPdf->AddPage();
        $this->oPdf->SetFont('arial', 'b', 5);

        $this->oPdf->Cell(
            $this->iLargura * 0.50,
            $this->iAltura,
            'RREO - ANEXO 11 (LRF, art. 53, § 1º, inciso III)',
            'B',
            0,
            "L",
            0
        );
        $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'Em Reais', 'B', 1, "R", 0);


        $this->escreverReceitas();
        $this->escreverDespesas();
        $this->escreverSaldoFinanceiro();

        /**
         * Assinaturas e nota explicativa
         */
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->setBold(false);
        $iAlturaAssinatura = 26;

        $this->oPdf->SetAutoPageBreak(true, 10);
        $this->notaExplicativa($this->oPdf, array($this->oPdf, 'AddPage'), $iAlturaAssinatura);
        $this->oPdf->SetAutoPageBreak(false, 10);
        $this->oRelatorioLegal->assinatura($this->oPdf, 'LRF', false);

        $this->oPdf->showPDF("AnexoXIRREO_" . time());
    }

    private function escreverReceitas()
    {

        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'RECEITAS', 'TBR', "C", 1);
        $this->oPdf->MultiCell(
            $this->iLargura * 0.10,
            $this->iAltura * 1.5,
            "PREVISÃO ATUALIZADA\n(a)",
            'TBRL',
            "C",
            1
        );
        $this->oPdf->MultiCell(
            $this->iLargura * 0.50,
            $this->iAltura * 1.5,
            "RECEITAS REALIZADAS\n(b)",
            'TBRL',
            "C",
            1
        );
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "SALDO\n(c) = (a - b)", 'TBL', 'C', 1);

        $this->oPdf->setBold(false);
        for ($iLinha = 1; $iLinha <= 5; $iLinha++) {
            $oLinha = $this->aLinhas[$iLinha];

            $sDescricao = \relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
            $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $sDescricao, 'BR', 0, "L", 0);
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->prevatu, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.50,
                $this->iAltura,
                db_formatar($oLinha->recatebim, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->saldo, 'f'),
                'BL',
                1,
                "R",
                0
            );
        }
        $this->oPdf->ln(4);
    }

    private function escreverDespesas()
    {

        $this->oPdf->setBold(true);
        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'DESPESAS', 'TBR', 'C', 1);
        $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "DOTAÇÃO ATUALIZADA\n(d)", 'TBRL', 'C', 1);
        $this->oPdf->MultiCell(
            $this->iLargura * 0.10,
            $this->iAltura * 1.5,
            "DESPESAS EMPENHADAS\n(e)",
            'TBRL',
            'C',
            1
        );
        $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 3, 'DESPESAS LIQUIDADAS', 'TBRL', 'C', 1);
        $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "DESPESAS\nPAGAS\n(f)", 'TBRL', 'C', 1);
        $this->oPdf->MultiCell(
            $this->iLargura * 0.10,
            $this->iAltura * 0.75,
            "DESPESAS\nINSCRITAS EM RESTOS A PAGAR\nNÃO PROCESSADOS",
            'TBRL',
            'C',
            1
        );
        $this->oPdf->MultiCell(
            $this->iLargura * 0.10,
            $this->iAltura,
            "PAGAMENTO DE RESTOS A PAGAR\n(g)",
            'TBRL',
            'C',
            1
        );
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->MultiCell(
            $this->iLargura * 0.10,
            $this->iAltura * 1.5,
            "SALDO\n(h) = (d - e)",
            'TBL',
            'C',
            1
        );

        $this->oPdf->setBold(false);
        for ($iLinha = 6; $iLinha <= 12; $iLinha++) {
            $oLinha = $this->aLinhas[$iLinha];
            $sDescricao = \relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
            $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $sDescricao, 'BR', 0, "L", 0);
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->dot_atual, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->despemp, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->despliq, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->desppag, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->insc_rp_np, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->rp_apagar, 'f'),
                'BRL',
                0,
                "R",
                0
            );
            $this->oPdf->Cell(
                $this->iLargura * 0.10,
                $this->iAltura,
                db_formatar($oLinha->saldo, 'f'),
                'BL',
                1,
                "R",
                0
            );
        }
        $this->oPdf->ln(4);
    }

    private function escreverSaldoFinanceiro()
    {

        $iAnoAnterior = $this->iAnoUsu - 1;
        $this->oPdf->setBold(true);
        $this->oPdf->setAutoNewLineMulticell(false);
        $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'SALDO FINANCEIRO A APLICAR', 'TBR', 'C', 1);
        $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "{$iAnoAnterior}\n(i)", 'TBRL', 'C', 1);
        $texto = "{$this->iAnoUsu}\n(j) = (Ib - (IIf+ IIg))";
        $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 1.5, $texto, 'TBRL', 'C', 1);
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->imprimirLinhaSaldoAtual();

        $oLinha = $this->aLinhas[13];
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $oLinha->descricao, 'TBR', 0, 'L');
        $this->oPdf->Cell(
            $this->iLargura * 0.10,
            $this->iAltura,
            db_formatar($oLinha->vlrexanter, 'f'),
            'TBRL',
            0,
            'R'
        );
        $this->oPdf->Cell(
            $this->iLargura * 0.50,
            $this->iAltura,
            db_formatar($oLinha->vlrexatual, 'f'),
            'TBRL',
            0,
            'R'
        );
        $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'TBL', 1, 'R');
        $this->oPdf->ln(4);
    }
}
