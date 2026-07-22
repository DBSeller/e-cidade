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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use relatorioContabil;
use BusinessException;
use DBDate;
use cl_assinatura;

/**
 * Class AnexoX
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoX extends Layout
{

    const HEADER_1 = 'RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA';
    const HEADER_2 = 'DEMONSTRATIVO DA PROJEÇÃO ATUARIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES';
    const HEADER_3 = "ORÇAMENTO DA SEGURIDADE SOCIAL";

    public function montar() {

        $aDadosRelatorio = $this->relatorio->getDados();

        if (empty($aDadosRelatorio)) {
            throw new BusinessException('Não é possível emitir o relatório, pois não existem valores configurados na Edição Manual para as Instituições selecionadas.');
        }

        /*
         * Reinicia o array para pegar o ano de inicio
         * e o ano final do exercicio
         */
        reset($aDadosRelatorio);

        $this->iAltura = 4;

        $this->imprimirCabecalho(true);

        foreach ($aDadosRelatorio as $oLinhaRelatorio) {

            $this->pdf->SetFont('arial', '', 6);
            $this->pdf->Cell(23, $this->iAltura,             $oLinhaRelatorio->ano,                          "TRB", 0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->receitasprevidenciarias,"f"), "TRB", 0, "R", 0);
            $this->pdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->despesasprevidenciarias,"f"), "TRB", 0, "R", 0);
            $this->pdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->resultadoprevidenciario,"f"), "TRB", 0, "R", 0);
            $this->pdf->Cell(48, $this->iAltura, db_formatar($oLinhaRelatorio->saldofinanceiro,"f"),         "TLB", 1, "R", 0);

            $this->imprimirCabecalho(false);
            $this->imprimeInfoProxPagina(false);
        }

        $this->pdf->ln();
        $this->relatorio->getNotaExplicativa($this->pdf, $this->relatorio->getPeriodo()->getCodigo());

        if ($this->pdf->getAvailHeight() < 35) {
             $this->pdf->AddPage();
        }

        $oAssinatura = new cl_assinatura();
        $this->pdf->ln(18);
        assinaturas($this->pdf, $oAssinatura, 'BG', false, false);

        $this->pdf->Output();
    }

  /**
   * Impime cabecalho do relatorio
   *
   * @param bool $lImprime
   */
    public function imprimirCabecalho($lImprime) {

        if ($this->pdf->GetY() > $this->pdf->h - 25 || $lImprime) {

            $this->pdf->SetFont('arial', 'b', 6);
            if (!$lImprime) {

                $this->pdf->AddPage();
                $this->imprimeInfoProxPagina(true);
            } else {

                $this->pdf->SetFillColor("777");
                $this->pdf->Cell(100, 5, "RREO - ANEXO 10 (LRF, art. 53, § 1º, inciso II )", "", 0, "L", 0);
                $this->pdf->Cell(85, 5, "Em Reais",                                       "", 1, "R", 0);
            }
            /*
             * Cabeçalho a ser Repetido nas paginas
             */
            $this->pdf->Cell(23, $this->iAltura, "",                                 "TR",  0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "RECEITAS",                         "TR",  0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "DESPESAS",                         "TR",  0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "RESULTADO",                        "TLR", 0, "C", 0);
            $this->pdf->Cell(48, $this->iAltura, "SALDO FINANCEIRO",                 "TL",  1, "C", 0);

            $this->pdf->Cell(23, $this->iAltura, "EXERCÍCIO",                        "R",   0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIO",                   "LR",  0, "C", 0);
            $this->pdf->Cell(48, $this->iAltura, "DO EXERCÍCIO",                     "L",   1, "C", 0);

            $this->pdf->Cell(23, $this->iAltura, "",                                 "R",   0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "(a)",                              "L",   0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "(b)",                              "L",   0, "C", 0);
            $this->pdf->Cell(40, $this->iAltura, "(c)=(a-b)",                        "L",   0, "C", 0);
            $this->pdf->Cell(48, $this->iAltura, "(d)=('d' exercício anterior)+(c)", "L",   1, "C", 0);
        }
    }

    /**
     * Impime informacao da proxima pagina no relatorio
     *
     * @param bool $lImprime
     */
    public function imprimeInfoProxPagina($lImprime) {

        if ($this->pdf->GetY() > $this->pdf->h - 31 || $lImprime) {

            $this->pdf->SetFont('arial', '', 6);
            if ($lImprime) {
                $this->pdf->Cell(190, ($this->iAltura*2), 'Continuação ' . ($this->pdf->PageNo()) . "/{nb}", 'T', 1, "R", 0);
            } else {
                $this->pdf->Cell(190, ($this->iAltura*3), 'Continua na página ' . ($this->pdf->PageNo() + 1) . "/{nb}", 'T', 1, "R", 0);
                $this->imprimirCabecalho(false);
            }
        }
    }
}
