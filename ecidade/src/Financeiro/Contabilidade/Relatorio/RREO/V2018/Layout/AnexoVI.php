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
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoVI as DadosAnexoVI;

/**
 * Class AnexoVI
 */
class AnexoVI
{
    /**
     * @var DadosAnexoVI
     */
    protected $anexo;

    /**
     * @var \stdClass[]
     */
    protected $linhas;

    /**
     * @var \PDFDocument
     */
    protected $pdf;

    /**
     * AnexoVI constructor.
     * @param $anexo
     * @throws \Exception
     */
    public function setAnexo($anexo)
    {
        $this->anexo  = $anexo;
        $this->linhas = $this->anexo->getDados();
    }

    /**
     *
     */
    public function imprimir()
    {
        $this->pdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
        $this->pdf->SetFontSize(6);
        $this->pdf->SetFillColor(235);
        $this->pdf->Open();

        $instituicaoPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
        $instituicoesSelecionadas = explode(",", $this->anexo->getInstituicoes());
        if (count($instituicoesSelecionadas) == 1) {

            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($instituicoesSelecionadas[0]);
            $this->pdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($instituicao));

            if ($instituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
                $this->pdf->addHeaderDescription($instituicao->getDescricao());
            }
        }else {
            $this->pdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($instituicaoPrefeitura));
        }

        $this->pdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
        $this->pdf->addHeaderDescription("DEMONSTRATIVO DOS RESULTADOS PRIMÁRIO E NOMINAL");
        $this->pdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");

        $this->pdf->addHeaderDescription($this->anexo->getTituloPeriodo());
        $this->pdf->AddPage();


        $this->pdf->cell(100, 4, 'RREO - ANEXO 6 (LRF, art 53, inciso III)', 0, 0, \PDFDocument::ALIGN_LEFT);
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, 'Em reais', 0, 1, \PDFDocument::ALIGN_RIGHT);
        $this->imprimirReceitasPrimarias();
        $this->imprimirDespesasPrimarias();
        $this->imprimirMetaFiscalResultadoPrimario();
        $this->imprimirJurosNominais();
        $this->imprimirMetaFiscalResultadoNominal();
        $this->imprimirCalculoResultadoNominal();
        $this->imprimirAjusteMetodologico();
        $this->imprimirInformacoesAdicionais();


        $this->pdf->ln(3);
        $this->anexo->getNotaExplicativa($this->pdf, $this->anexo->getPeriodo()->getCodigo(), $this->pdf->getAvailWidth());

        $this->pdf->ln(20);
        $oAssinatura = new \cl_assinatura();
        assinaturas($this->pdf, $oAssinatura, 'LRF');

        $this->pdf->showPDF();
    }

    /**
     * Imprime o quadro das receitas
     */
    protected function imprimirReceitasPrimarias()
    {

        $this->criarCabecalhoReceita();

        for ($linhas = 1; $linhas <= 39; $linhas++) {

            if ($this->pdf->getAvailHeight() < 15) {

                $this->pdf->cell($this->pdf->getAvailWidth(), 2, '', "T", 1);
                $this->pdf->cell($this->pdf->getAvailWidth(), 4, "Continua ".$this->pdf->getCurrentPage()."/{nb}", 0, 1, \PDFDocument::ALIGN_RIGHT);
                $this->pdf->addPage();
                $this->criarCabecalhoReceita();
            }

            $dadosLinha = $this->linhas[$linhas];
            $preenche = $linhas === 39 ? 1 : 0;
            $borda    = $linhas === 39 ? "1" : "LR";
            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);
            $this->pdf->Cell(145,4, $identacao . $dadosLinha->descricao, $borda, 0, \PDFDocument::ALIGN_LEFT, $preenche);
            $this->pdf->Cell(67, 4, db_formatar($dadosLinha->previni, 'f'), $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(65, 4, db_formatar($dadosLinha->saldo_bimestre_atual, 'f'), $borda, 1, \PDFDocument::ALIGN_RIGHT, $preenche);
        }
    }

    /**
     * Escreve o cabeçalho da receita
     */
    protected function criarCabecalhoReceita()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, 'ACIMA DA LINHA', "1", 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->Cell(145, 8, 'RECEITAS PRIMÁRIAS', "1", 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell(67, 8, 'PREVISÃO ATUALIZADA', "1", 0, \PDFDocument::ALIGN_CENTER, 1);
        $x = $this->pdf->getX();
        $this->pdf->Cell(65, 4, 'Até o Bimestre / '.$this->anexo->getAno(), 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->SetX($x);
        $this->pdf->Cell(65, 4, 'RECEITAS REALIZADAS (a)', "1", 1, \PDFDocument::ALIGN_CENTER, 1);
    }

    /**
     * Imprime o quadro das despesas
     */
    protected function imprimirDespesasPrimarias()
    {

        $this->pdf->ln(5);
        $this->pdf->setBold(true);
        $this->pdf->cell(100, 14, 'DESPESAS PRIMÁRIAS', 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);

        $this->pdf->setAutoNewLineMulticell(false);
        $this->pdf->MultiCell(22,7, "DOTAÇÃO\nATUALIZADA", 1, \PDFDocument::ALIGN_CENTER, 1);
        $x = $this->pdf->getX();
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, 'Até o Bimestre / '.$this->anexo->getAno(), 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setX($x);
        $this->pdf->MultiCell(25, 5, "DESPESAS\nEMPENHADAS", 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->MultiCell(25, 5, "DESPESAS\nLIQUIDADAS", 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->MultiCell(25, 5, "DESPESAS\nPAGAS (a)" , 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->MultiCell(25, 2.5, "RESTOS A\nPAGAR\nPROCESSADOS\nPAGOS (b)" , 1, \PDFDocument::ALIGN_CENTER, 1);
        $x = $this->pdf->getX();
        $this->pdf->Cell($this->pdf->getAvailWidth(), 3, "RESTOS A PAGAR NÃO PROCESSADOS", 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setX($x);
        $this->pdf->Cell(28, 7, "LIQUIDADOS" , 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->Cell(27, 7, "PAGOS (c)" , 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linhas = 40; $linhas <= 55; $linhas++) {

            $dadosLinha = $this->linhas[$linhas];

            /**
             * Quando a linha for 54 (RESERVA DE CONTINGÊNCIA (XXII))
             * deve ser exibido apenas a Dotação Atualizada.
             */
            $dotacaoAtualizada          = db_formatar($dadosLinha->dotatu, 'f');
            $despesasEmpenhadas         = $linhas === 54 ? "-" : db_formatar($dadosLinha->despemp, 'f');
            $despesasLiquidadas         = $linhas === 54 ? "-" : db_formatar($dadosLinha->despliq, 'f');
            $despesasPagas              = $linhas === 54 ? "-" : db_formatar($dadosLinha->desppag, 'f');
            $rpProcessadosPagos         = $linhas === 54 ? "-" : db_formatar($dadosLinha->rp_proc_pago, 'f');
            $rpNaoProcessadosLiquidados = $linhas === 54 ? "-" : db_formatar($dadosLinha->rp_nao_processado, 'f');
            $rpNaoProcessadosPagos      = $linhas === 54 ? "-" : db_formatar($dadosLinha->rp_pagos, 'f');

            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);
            $preenche  = $linhas === 55 ? 1 : 0;
            $borda     = $linhas === 55 ? "1" : "LR";

            $this->pdf->Cell(100, 4, $identacao . $dadosLinha->descricao,  $borda, 0, \PDFDocument::ALIGN_LEFT, $preenche);
            $this->pdf->Cell(22, 4, $dotacaoAtualizada, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(25, 4, $despesasEmpenhadas, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(25, 4, $despesasLiquidadas, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(25, 4, $despesasPagas, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(25, 4, $rpProcessadosPagos, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(28, 4, $rpNaoProcessadosLiquidados, $borda, 0, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->Cell(27, 4, $rpNaoProcessadosPagos, $borda, 1, \PDFDocument::ALIGN_RIGHT, $preenche);
        }

        /**
         * Quando a linha for 56 (RESULTADO PRIMÁRIO - Acima da Linha (XXIV) = [XIIa - (XXIIIa +XXIIIb + XXIIIc)])
         * deve ser exibido apenas a Descrição e o Valor do cálculo da fórmula.
         */
        $this->pdf->Ln(4);
        $this->pdf->setBold(true);
        $this->pdf->Cell(100, 4, $this->linhas[56]->descricao, 1, 0, \PDFDocument::ALIGN_LEFT, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, db_formatar($this->linhas[56]->valor_corrente, 'f'), 1, 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->Ln(4);

        $this->pdf->cell($this->pdf->getAvailWidth(), 4, "Continua ".$this->pdf->getCurrentPage()."/{nb}", 0, 1, \PDFDocument::ALIGN_RIGHT);
        $this->pdf->addPage();
    }

    protected function imprimirMetaFiscalResultadoPrimario()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, "META FISCAL PARA O RESULTADO PRIMÁRIO", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);

        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, "VALOR CORRENTE", 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->Cell(145, 4, $this->linhas[57]->descricao, 1, 0, \PDFDocument::ALIGN_LEFT);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, db_formatar($this->linhas[57]->valor_corrente, 'f'), 1, 1, \PDFDocument::ALIGN_RIGHT);
        $this->pdf->Ln(4);
    }

    protected function imprimirJurosNominais()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 7, "JUROS NOMINAIS", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $x = $this->pdf->GetX();

        $this->pdf->Cell($this->pdf->getAvailWidth(), 3, "Até o Bimestre / " .$this->anexo->getAno(), 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->SetX($x);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, "VALOR INCORRIDO", 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for($linhas = 58; $linhas <= 59; $linhas++) {

            $dadosLinha = $this->linhas[$linhas];
            $borda      = $linhas === 59 ? "LRB" : "LR";

            $this->pdf->Cell(145, 4, $dadosLinha->descricao, $borda, 0, \PDFDocument::ALIGN_LEFT);
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, db_formatar($dadosLinha->valor_incorrido, 'f'), $borda, 1, \PDFDocument::ALIGN_RIGHT);
        }

        $this->pdf->Ln(4);
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, $this->linhas[60]->descricao, 1, 0, \PDFDocument::ALIGN_LEFT, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, db_formatar($this->linhas[60]->valor_incorrido, 'f'), 1, 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->Ln(4);
    }

    protected function imprimirMetaFiscalResultadoNominal()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, "META FISCAL PARA O RESULTADO NOMINAL", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);

        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, "VALOR CORRENTE", 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->Cell(145, 4, $this->linhas[61]->descricao, 1, 0, \PDFDocument::ALIGN_LEFT);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, db_formatar($this->linhas[61]->valor_corrente, 'f'), 1, 1, \PDFDocument::ALIGN_RIGHT);
        $this->pdf->Ln(4);
    }

    protected function imprimirCalculoResultadoNominal()
    {

        $this->pdf->setBold(true);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, "ABAIXO DA LINHA", 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->Cell(145, 11, "CÁLCULO DO RESULTADO NOMINAL", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $x = $this->pdf->GetX();

        $this->pdf->Cell($this->pdf->getAvailWidth(), 3, "SALDO", 1, 1, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->SetX($x);
        $this->pdf->setAutoNewLineMulticell(false);
        $this->pdf->MultiCell(67, 4, "Em 31/Dez/" . ($this->anexo->getAno() - 1) . "\n(a)", 1, \PDFDocument::ALIGN_CENTER,1);
        $this->pdf->setAutoNewLineMulticell(true);
        $this->pdf->MultiCell(65, 4, "Até o {$this->anexo->getPeriodo()->getSigla()} / " . $this->anexo->getAno() . "\n(b)", 1, \PDFDocument::ALIGN_CENTER,1);

        for ($linha = 62; $linha <= 68; $linha++) {

            $dadosLinha = $this->linhas[$linha];
            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);
            $this->pdf->Cell(145, 4, $identacao . $dadosLinha->descricao, 'LR', 0, \PDFDocument::ALIGN_LEFT, 0);
            $this->pdf->Cell(67,  4, trim(db_formatar($dadosLinha->saldo_bimestre_anterior, 'f')), 'LR',0, \PDFDocument::ALIGN_RIGHT,0);
            $this->pdf->Cell(65,  4, trim(db_formatar($dadosLinha->saldo_bimestre_atual, 'f')), 'LR', 1,\PDFDocument::ALIGN_RIGHT,0);
        }
        $dadosLinha = $this->linhas[69];
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, $identacao . $dadosLinha->descricao, '1', 0, \PDFDocument::ALIGN_LEFT, 1);
        $this->pdf->Cell(132 , 4, trim(db_formatar($dadosLinha->saldo, 'f')), '1', 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->setBold(false);
        $this->pdf->ln(4);

    }

    protected function imprimirAjusteMetodologico()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "AJUSTE METODOLÓGICO", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, "Até o {$this->anexo->getPeriodo()->getSigla()} / " . $this->anexo->getAno(), 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 70; $linha <= 74; $linha++) {

            $dadosLinha = $this->linhas[$linha];
            $borda = $linha === 74 ? '1' : 'LR';
            $preenche = $linha === 74 ? '1' : '0';
            $bold = $linha === 74 ? true : false;

            $this->pdf->setBold($bold);
            $this->pdf->Cell(145, 4, $dadosLinha->descricao, $borda, 0, \PDFDocument::ALIGN_LEFT, $preenche);
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, trim(db_formatar($dadosLinha->saldo, 'f')), $borda, 1, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->setBold(false);
        }


        $this->pdf->ln(4);
        $dadosLinha = $this->linhas[75];
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, $dadosLinha->descricao, '1', 0, \PDFDocument::ALIGN_LEFT, 1);
        $this->pdf->Cell($this->pdf->getAvailWidth() , 4, trim(db_formatar($dadosLinha->saldo, 'f')), '1', 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->setBold(false);
        $this->pdf->ln(4);
    }

    protected function imprimirInformacoesAdicionais()
    {
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, "Continua ".$this->pdf->getCurrentPage()."/{nb}", 0, 1, \PDFDocument::ALIGN_RIGHT);
        $this->pdf->addPage();

        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "INFORMAÇÕES ADICIONAIS", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, "PREVISÃO ORÇAMENTÁRIA", 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 76; $linha <= 79; $linha++) {

            $dadosLinha = $this->linhas[$linha];
            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);

            $this->pdf->Cell(145, 4, $identacao . $dadosLinha->descricao, 1, 0, \PDFDocument::ALIGN_LEFT, 0);
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, trim(db_formatar($dadosLinha->previsao, 'f')), 1, 1, \PDFDocument::ALIGN_RIGHT, 0);

        }
    }
}
