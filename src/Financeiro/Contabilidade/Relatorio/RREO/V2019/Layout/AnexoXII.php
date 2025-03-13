<?php
/**
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
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout;

use Check;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;
use PDFDocument;

class AnexoXII
{
    /**
     * @var ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoXII
     */
    protected $anexo;

    /**
     * @var PDFDocument
     */
    protected $pdf;

    /**
     * @var array
     */
    protected $linhas = array();

    protected $linhasRestosPagar = array();

    protected $controleRestosAPagar = array();

    const LINHA_INICIO_RECEITAS = 1;
    const LINHA_FIM_RECEITAS = 18;

    const LINHA_INICIO_RECEITAS_ADICIONAIS = 19;
    const LINHA_FIM_RECEITAS_ADICIONAIS = 27;

    const LINHA_INICIO_DESPESAS_SAUDE = 28;
    const LINHA_FIM_DESPESAS_SAUDE = 36;

    const LINHA_INICIO_DESPESAS_SAUDE_NAO_COMPUTADAS = 37;
    const LINHA_FIM_DESPESAS_SAUDE_NAO_COMPUTADAS = 47;

    const LINHA_TOTAL_DESPESAS_SAUDE = 48;

    const LINHA_PERCENTUAL_DE_APLICACAO_EM_ACOES = 49;
    const LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO = 50;

    const LINHA_INICIO_EXECUCAO_RESTOS = 51;
    const LINHA_FIM_EXECUCAO_RESTOS = 53;

    const LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR = 54;
    const LINHA_FIM_CONTROLE_RESTOS_A_PAGAR = 56;

    const LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO = 57;
    const LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO = 59;

    const LINHA_INICIO_DESPESAS_POR_SUBFUNCAO = 60;
    const LINHA_FIM_DESPESAS_POR_SUBFUNCAO = 67;

    const LINHA_RESTOS_PAGAR_INSCRITOS_INDEVIDAMENTE = 44;

    const QUADRO_RP_CANCELADO_OU_PRESCRITO = 1;
    const QUADRO_RP_PERCENTUAL_MINIMO = 2;

    public function setAnexo(InterfaceRelatorioLegal $anexo)
    {
        $this->anexo = $anexo;
        $this->linhas = $this->anexo->getDados();
        $this->linhasRestosPagar = $this->anexo->getLinhasRestosAPagar();
        $this->controleRestosAPagar = $this->anexo->getControleRestosAPagar();
    }

    public function imprimir()
    {
        $this->pdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
        $this->pdf->SetFillColor(232);
        $this->pdf->SetAutoPageBreak(false, 8);
        $this->pdf->setAutoNewLineMulticell(false);
        $this->pdf->setFontSize(6);
        $this->cabecalhoPrincipal();

        $this->pdf->setBold(true);
        $this->pdf->cell(($this->pdf->getAvailWidth() / 2), 4, "RREO ? ANEXO  12 (LC 141/2012, art. 35)");
        $this->pdf->cell(($this->pdf->getAvailWidth()), 4, "R$ 1,00", 0, 1, 'R');
        $this->pdf->setBold(false);
        $this->escreverReceitas();
        $this->pdf->ln();
        $this->escreverReceitasAdicionais();

        $this->pdf->addPage();
        $this->escreverDespesaSaude();
        $this->pdf->ln();
        $this->escreverDespesaSaudeNaoComputada();
        $this->imprimirLinhaDespesa(self::LINHA_TOTAL_DESPESAS_SAUDE, self::LINHA_TOTAL_DESPESAS_SAUDE);
        $this->pdf->ln();
        $this->escreverPercentualAplicacao();
        $this->escreverLinhaDiferenca();

        $this->pdf->addPage();
        $this->escreverExecucaoRestosPagar();
        $this->pdf->ln();
        $this->escreverControleRestosAPagarCanceladosOuPrescritos();
        $this->pdf->ln();
        $this->escreverControleRestosAPagarValorPercentual();

        $this->pdf->addPage();
        $this->escreverDespesaSaudeSubFuncao();
        $this->pdf->ln();
        $this->anexo->getNotaExplicativa($this->pdf, $this->anexo->getPeriodo()->getCodigo(), $this->pdf->getAvailWidth());

        $this->pdf->SetY(150);
        $this->escreverAssinaturas();
        $this->pdf->showPDF("AnexoXII");
    }

    protected function cabecalhoPrincipal()
    {
        $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();

        $this->pdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

        if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $this->pdf->addHeaderDescription($oInstituicao->getDescricao());
        }
        $sMesInicio = mb_strtoupper(\DBDate::getMesExtenso($this->anexo->getDataInicialPeriodo()->getMes()));
        $sMesFim = mb_strtoupper(\DBDate::getMesExtenso($this->anexo->getDataFinal()->getMes()));

        $this->pdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
        $this->pdf->addHeaderDescription("DEMONSTRATIVO DAS RECEITAS E DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE");
        $this->pdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
        $this->pdf->addHeaderDescription('JANEIRO' . ' A ' . $sMesFim . '/' . $this->anexo->getAno() . ' - BIMESTRE ' . $sMesInicio . '-' . $sMesFim);
        $this->pdf->open();
        $this->pdf->addPage();
    }

    /**
     * Escreve assinaturas.
     */
    protected function escreverAssinaturas()
    {

        $nLargura = $this->pdf->getAvailWidth() / 3;

        $oAssinatura = new \cl_assinatura();
        assinaturas($this->pdf, $oAssinatura, 'LRF');
        $this->pdf->setAutoNewLineMulticell(true);
    }

    protected function cabecalhoReceitas($titulo, $primeiraLetra, $segundaLetra)
    {
        $lBold = $this->pdf->getBold();
        $larguraPagina = $this->pdf->getAvailWidth();

        $this->pdf->setBold(true);
        $this->pdf->Cell($larguraPagina * 0.55, 8, $titulo, "TB", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.15, 8, "PREVISÃO INICIAL", "TBL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.15, 8, "PREVISÃO ATUALIZADA (" . $primeiraLetra . ")", "TBL", 0, 'C', 1);

        $iPosicaoX = $this->pdf->GetX();
        $this->pdf->Cell($larguraPagina * 0.15, 4, "RECEITAS REALIZADAS", "TBL", 1, 'C', 1);
        $this->pdf->SetX($iPosicaoX);

        $this->pdf->Cell($larguraPagina * 0.1, 4, "Até o Bimestre ($segundaLetra)", "BL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.05, 4, "% ({$segundaLetra}/{$primeiraLetra}) * 100", "TBL", 1, 'C', 1);
        $this->pdf->setBold($lBold);
    }

    /**
     * RECEITAS PARA APURAÇÃO DA APLICAÇÃO EM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE
     */
    protected function escreverReceitas()
    {
        $this->cabecalhoReceitas("RECEITAS PARA APURAÇÃO DA APLICAÇÃO EM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE", "a", "b");
        $this->imprimirReceitas(self::LINHA_INICIO_RECEITAS, self::LINHA_FIM_RECEITAS);
    }

    protected function escreverReceitasAdicionais()
    {
        $this->cabecalhoReceitas("RECEITAS ADICIONAIS PARA FINANCIAMENTO DA SAÚDE", "c", "d");
        $this->imprimirReceitas(self::LINHA_INICIO_RECEITAS_ADICIONAIS, self::LINHA_FIM_RECEITAS_ADICIONAIS);
    }

    protected function imprimirReceitas($linhaInicio, $linhaFim)
    {
        $larguraPagina = $this->pdf->getAvailWidth();
        $linha = $linhaInicio;
        while ($linha <= $linhaFim) {
            $dadosLinha = $this->linhas[$linha];
            $nPorcentagem = $dadosLinha->prevatu;

            if ($nPorcentagem) {
                $nPorcentagem = ($dadosLinha->rec_atebim / $dadosLinha->prevatu) * 100;
            }

            $borda = '';
            if ($linha == $linhaFim) {
                $borda = 'BT';
                $this->pdf->setBold(true);
            }
            $this->pdf->Cell($larguraPagina * 0.55, 4, str_repeat(' ', $dadosLinha->nivel * 2) . $dadosLinha->descricao, "{$borda}R");
            $this->pdf->Cell($larguraPagina * 0.15, 4, db_formatar($dadosLinha->previni, 'f'), "LR{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.15, 4, db_formatar($dadosLinha->prevatu, 'f'), "LR{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.1, 4, db_formatar($dadosLinha->rec_atebim, 'f'), "LR{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.05, 4, db_formatar($nPorcentagem, 'f'), "L{$borda}", 1, 'R');
            $linha++;
        }
        $this->pdf->setBold(false);
    }

    protected function cabecalhoDespesas($titulo, $formulas)
    {
        $bold = $this->pdf->getBold();
        $larguraPagina = $this->pdf->getAvailWidth();
        $altura = 4;
        $larguraTitulo = $this->anexo->ultimoPeriodo() ? 0.34 : 0.44;

        $alturaCalculada = $this->pdf->getMultiCellHeight($larguraPagina * $larguraTitulo, $altura, $titulo);
        $tituloDotacaoAtualizada = "DOTAÇÃO ATUALIZADA\n" . $formulas[0];
        $alturaDotacaoAtualizada = $this->pdf->getMultiCellHeight($larguraPagina * 0.1, $altura, $tituloDotacaoAtualizada);

        $this->pdf->setBold(true);

        $this->pdf->setAutoNewLineMulticell(false);
        $this->pdf->MultiCell($larguraPagina * $larguraTitulo, ($altura * 2) + ($altura - $alturaCalculada), $titulo, "TB", 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.1, $altura * 2, "DOTAÇÃO INICIAL", "TBL", 0, 'C', 1);

        $this->pdf->MultiCell($larguraPagina * 0.1, ($altura * 2) + ($altura - $alturaDotacaoAtualizada), $tituloDotacaoAtualizada, "TBL", 'C', 1);

        $iPosicaoX = $this->pdf->GetX();
        $iPosicaoY = $this->pdf->GetY() + 4;

        $this->pdf->Cell($larguraPagina * 0.18, $altura, "DESPESAS EMPENHADAS", "TBL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.18, $altura, "DESPESAS LIQUIDADAS", "TBL", !$this->anexo->ultimoPeriodo(), 'C', 1);

        if ($this->anexo->ultimoPeriodo()) {
            $this->pdf->setAutoNewLineMulticell(true);
            $this->pdf->MultiCell($larguraPagina * 0.1, $altura, "Inscritas em Restos a Pagar não Processados7", "TBL", 'C', 1);
            $this->pdf->setAutoNewLineMulticell(false);
        }

        $this->pdf->SetXY($iPosicaoX, $iPosicaoY);

        $this->pdf->Cell($larguraPagina * 0.1, $altura, "Até o Bimestre" . $formulas[1], "BL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.08, $altura, "% " . $formulas[2], "BL", 0, 'C', 1);

        $this->pdf->Cell($larguraPagina * 0.1, $altura, "Até o Bimestre" . $formulas[3], "BL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.08, $altura, "%" . $formulas[4], "BL", 1, 'C', 1);

        $this->pdf->setBold($bold);
    }

    protected function escreverDespesaSaude()
    {
        $titulo = "DESPESAS COM SAÚDE (Por grupo de natureza da despesa)";
        $formulas = array(" (e)", " (f)", " (f/e) x 100", " (g)", " (g/e) x 100");
        $this->cabecalhoDespesas($titulo, $formulas);
        $this->imprimirLinhaDespesa(self::LINHA_INICIO_DESPESAS_SAUDE, self::LINHA_FIM_DESPESAS_SAUDE);
    }

    protected function escreverDespesaSaudeNaoComputada()
    {
        $titulo = "DESPESAS COM SAÚDE NÃO COMPUTADAS PARA FINS DE APURAÇÃO DO PERCENTUAL MÍNIMO";
        $formulas = array("", " (h)", " (h/IVf) x 100", " (i)", " (i/IVg) x 100");
        $this->cabecalhoDespesas($titulo, $formulas);
        $this->imprimirLinhaDespesa(self::LINHA_INICIO_DESPESAS_SAUDE_NAO_COMPUTADAS, self::LINHA_FIM_DESPESAS_SAUDE_NAO_COMPUTADAS);
    }

    protected function escreverDespesaSaudeSubFuncao()
    {
        $titulo = "DESPESAS COM SAÚDE (Por subfunção)";
        $formulas = array("", " (l)", " (l/total l) x 100", " (m)", " (m/total m) x 100");
        $this->cabecalhoDespesas($titulo, $formulas);
        $this->imprimirLinhaDespesa(self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO, self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO);
    }

    /**
     * Escreve as linhas de despesa.
     * @param $linhaInicio
     * @param $linhaFim
     */
    private function imprimirLinhaDespesa($linhaInicio, $linhaFim)
    {
        $larguraPagina = $this->pdf->getAvailWidth();
        $linha = $linhaInicio;
        while ($linha <= $linhaFim) {
            $dadosLinha = $this->linhas[$linha];

            if (Check::between($linha, self::LINHA_INICIO_DESPESAS_SAUDE, self::LINHA_FIM_DESPESAS_SAUDE)) {
                $nEmpPorcentagem = $dadosLinha->dot_atual;
                $nLiqPorcentagem = $dadosLinha->dot_atual;
            } else {
                $nEmpPorcentagem = $this->linhas[$linhaFim]->emp_atebim;
                $nLiqPorcentagem = $this->linhas[$linhaFim]->liq_atebim;
            }

            $altura = 4;
            $larguraTitulo = $this->anexo->ultimoPeriodo() ? 0.34 : 0.44;
            $sBorda = "";

            if ($nEmpPorcentagem) {
                $nEmpPorcentagem = ($dadosLinha->emp_atebim / $nEmpPorcentagem) * 100;
            }

            if ($nLiqPorcentagem) {
                $nLiqPorcentagem = ($dadosLinha->liq_atebim / $nLiqPorcentagem) * 100;
            }

            if ($dadosLinha->ordem == $linhaFim) {
                $sBorda = "TB";
                $this->pdf->setBold(true);
            }

            $sDescricao = str_repeat(' ', $dadosLinha->nivel * 2) . $dadosLinha->descricao;
            $nDotIni = db_formatar($dadosLinha->dot_ini, 'f');
            $nDotAtu = db_formatar($dadosLinha->dot_atual, 'f');
            $nEmpAteBim = db_formatar($dadosLinha->emp_atebim, 'f');
            $nLiqAteBim = db_formatar($dadosLinha->liq_atebim, 'f');
            $nEmpPorcentagem = db_formatar($nEmpPorcentagem, 'f');
            $nLiqPorcentagem = db_formatar($nLiqPorcentagem, 'f');

            if ($dadosLinha->ordem == self::LINHA_RESTOS_PAGAR_INSCRITOS_INDEVIDAMENTE && !$this->anexo->ultimoPeriodo()) {
                $nDotIni = '-';
                $nDotAtu = '-';
                $nEmpAteBim = '-';
                $nLiqAteBim = '-';
                $nEmpPorcentagem = '-';
                $nLiqPorcentagem = '-';
            }

            $nAltura = $this->pdf->getMultiCellHeight($larguraPagina * $larguraTitulo, $altura, $dadosLinha->descricao);

            $this->pdf->MultiCell($larguraPagina * $larguraTitulo, $altura, $sDescricao, "" . $sBorda);
            $this->pdf->Cell($larguraPagina * 0.1, $nAltura, $nDotIni, "LR" . $sBorda, 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.1, $nAltura, $nDotAtu, "LR" . $sBorda, 0, 'R');

            $this->pdf->Cell($larguraPagina * 0.1, $nAltura, $nEmpAteBim, "L" . $sBorda, 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.08, $nAltura, $nEmpPorcentagem, "L" . $sBorda, 0, 'R');

            $this->pdf->Cell($larguraPagina * 0.1, $nAltura, $nLiqAteBim, "L" . $sBorda, 0, 'R');
            $this->pdf->Cell($larguraPagina * 0.08, $nAltura, $nLiqPorcentagem, "L" . $sBorda, !$this->anexo->ultimoPeriodo(), 'R');

            if ($this->anexo->ultimoPeriodo()) {
                $this->pdf->Cell($larguraPagina * 0.1, $nAltura, db_formatar($dadosLinha->rp_nproc, 'f'), "L" . $sBorda, 1, 'R');
            }

            $linha++;
        }
        $this->pdf->setBold(false);
    }

    protected function escreverPercentualAplicacao()
    {
        $this->imprimeLinhaOutros(self::LINHA_PERCENTUAL_DE_APLICACAO_EM_ACOES, self::LINHA_PERCENTUAL_DE_APLICACAO_EM_ACOES);
    }

    protected function escreverLinhaDiferenca()
    {
        $this->imprimeLinhaOutros(self::LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO, self::LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO);
    }

    /**
     * Escreve os dados para tabelas com somente duas colunas (descricao e valor).
     * @param $oLinha
     */
    protected function imprimeLinhaOutros($linhaInicio, $linhaFim)
    {
        $larguraPagina = $this->pdf->getAvailWidth();
        $bold = $this->pdf->getBold();
        $linha = $linhaInicio;
        while ($linha <= $linhaFim) {
            $dadosLinha = $this->linhas[$linha];
            $linha++;

            $this->pdf->setBold(true);
            $this->pdf->setAutoNewLineMulticell(false);

            $nAltura = $this->pdf->getMultiCellHeight($larguraPagina * 0.7, 4, str_repeat(' ', $dadosLinha->nivel * 2) . $dadosLinha->descricao);

            $this->pdf->MultiCell($larguraPagina * 0.7, 4, $dadosLinha->descricao, "TB", 'L', 1);
            $this->pdf->cell($larguraPagina * 0.3, $nAltura, db_formatar($dadosLinha->valor, 'f'), "LTB", 1, 'R');

            $this->pdf->setBold($bold);
        }
    }

    protected function cabecalhoExecucaoRestosPagar()
    {
        $bold = $this->pdf->getBold();
        $larguraPagina = $this->pdf->getAvailWidth();
        $altura = 8;
        $titulo = "EXECUÇÃO DE RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS COM DISPONIBILDADE DE CAIXA";

        $this->pdf->setBold(true);
        $this->pdf->setAutoNewLineMulticell(false);
        $this->pdf->MultiCell($larguraPagina * 0.4, $altura, $titulo, "TB", 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.12, $altura, "INSCRITOS", "TBL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.12, $altura, "CANCELADOS/PRESCRITOS", "TBL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.12, $altura, "PAGOS", "TBL", 0, 'C', 1);
        $this->pdf->Cell($larguraPagina * 0.12, $altura, "A PAGAR", "TBL", 0, 'C', 1);

        $this->pdf->setAutoNewLineMulticell(true);
        $this->pdf->MultiCell($larguraPagina * 0.12, ($altura / 2), "PARCELA CONSIDERADA\nNO LIMITE", "TBL", 'C', 1);
        $this->pdf->setAutoNewLineMulticell(false);

        $this->pdf->setBold($bold);
    }

    protected function escreverExecucaoRestosPagar()
    {
        $this->cabecalhoExecucaoRestosPagar();
        $this->imprimirExecucaoRestosPagar();
    }

    /**
     * Escreve a linha para Execucao dos Restos a Pagar.
     */
    protected function imprimirExecucaoRestosPagar()
    {
        $larguraPagina = $this->pdf->getAvailWidth();
        $bold = $this->pdf->getBold();

        $altura = 4;
        $larguraValor = 0.12;
        $larguraDescricao = 0.4;
        foreach ($this->linhasRestosPagar as $dadosLinha) {
            $borda = '';
            if ($dadosLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS) {
                $this->pdf->setBold(true);
                $borda = 'B';
            }
            $this->pdf->Cell($larguraPagina * $larguraDescricao, $altura, str_repeat(' ', $dadosLinha->nivel * 2) . $dadosLinha->descricao, $borda);
            $this->pdf->Cell($larguraPagina * $larguraValor, $altura, db_formatar($dadosLinha->inscritos, 'f'), "L{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * $larguraValor, $altura, db_formatar($dadosLinha->cancelados_prescritos, 'f'), "L{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * $larguraValor, $altura, db_formatar($dadosLinha->pagos, 'f'), "L{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * $larguraValor, $altura, db_formatar($dadosLinha->a_pagar, 'f'), "L{$borda}", 0, 'R');
            $this->pdf->Cell($larguraPagina * $larguraValor, $altura, db_formatar($dadosLinha->parcela_limite, 'f'), "L{$borda}", 1, 'R');
        }

        $this->pdf->setBold($bold);
    }


    /**
     * Escreve cabeçalho para as tabelas Controle.
     * @param $iLinha
     */
    private function cabecalhoControleRestosAPagar($titulo, $subtitulo, $letra)
    {
        $bold = $this->pdf->getBold();
        $largura = $this->pdf->getAvailWidth();
        $altura = 8;
        $nAltura = $this->pdf->getMultiCellHeight($largura * 0.64, $altura, $titulo);

        $this->pdf->setBold(true);
        $this->pdf->MultiCell($largura * 0.64, ($altura * 2) + ($altura - $nAltura), $titulo, "TB", 'C', 1);
        $iPosicaoX = $this->pdf->GetX();
        $this->pdf->Cell($largura * 0.36, $altura, $subtitulo, "TBL", 1, 'C', 1);
        $this->pdf->SetX($iPosicaoX);
        $this->pdf->Cell($largura * 0.12, $altura, "Saldo Inicial", "TBL", 0, 'C', 1);
        $this->pdf->MultiCell($largura * 0.12, ($altura / 2), "Despesas custeadas no exercício de referência (" . $letra . ")", "TBL", 'C', 1);
        $this->pdf->Cell($largura * 0.12, $altura, "Saldo Final (Não Aplicado)", 'BL', 1, 'C', 1);

        $this->pdf->setBold($bold);
    }

    protected function escreverControleRestosAPagarCanceladosOuPrescritos()
    {
        $titulo = "CONTROLE DOS RESTOS A PAGAR CANCELADOS OU PRESCRITOS PARA FINS DE APLICAÇÃO DA DISPONIBILIDADE DE CAIXA CONFORME ARTIGO 24, § 1º e 2º";
        $subtitulo = "RESTOS A PAGAR CANCELADOS OU PRESCRITOS";
        $letra = 'j';
        $this->cabecalhoControleRestosAPagar($titulo, $subtitulo, $letra);
        $this->imprimirControleRestosAPagar(self::QUADRO_RP_CANCELADO_OU_PRESCRITO);
    }

    protected function escreverControleRestosAPagarValorPercentual()
    {
        $titulo = "CONTROLE DO VALOR REFERENTE AO PERCENTUAL MÍNIMO NÃO CUMPRIDO EM EXERCÍCIOS ANTERIORES PARA FINS DE APLICAÇÃO DOS RECURSOS VINCULADOS CONFORME ARTIGOS 25 E 26";
        $subtitulo = "LIMITE NÃO CUMPRIDO";
        $letra = 'k';
        $this->cabecalhoControleRestosAPagar($titulo, $subtitulo, $letra);

        $this->imprimirControleRestosAPagar(self::QUADRO_RP_PERCENTUAL_MINIMO);
    }

    /**
     * Escreve a linha para tabelas Controle
     * @param $quadro
     */
    private function imprimirControleRestosAPagar($quadro)
    {
        $larguraPagina = $this->pdf->getAvailWidth();
        $bold = $this->pdf->getBold();

        $altura = 4;
        $larguraValor = $larguraPagina * 0.12;
        $larguraDescricao = $larguraPagina * 0.64;

        foreach ($this->controleRestosAPagar[$quadro] as $dadosLinha) {
            $borda = '';
            if ($dadosLinha->ordem == self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR || $dadosLinha->ordem == self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO) {
                $borda = 'B';
                $this->pdf->setBold(true);
            }

            $this->pdf->Cell($larguraDescricao, $altura, str_repeat(' ', $dadosLinha->nivel * 2) . $dadosLinha->descricao, "{$borda}");
            $this->pdf->Cell($larguraValor, $altura, db_formatar($dadosLinha->saldo_inicial, 'f'), "L{$borda}", 0, "R");
            $this->pdf->Cell($larguraValor, $altura, db_formatar($dadosLinha->despesas_custeadas_exercicio, 'f'), "L{$borda}", 0, 'R');
            $this->pdf->Cell($larguraValor, $altura, db_formatar($dadosLinha->saldo_final, 'f'), "L{$borda}", 1, 'R');
        }
        $this->pdf->setBold($bold);
    }
}
