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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use relatorioContabil;
use BusinessException;
use DBDate;
use cl_assinatura;

/**
 * Class AnexoX
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout
 */
class AnexoX extends Layout
{
    const HEADER_1 = 'RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA';
    const HEADER_2 = 'DEMONSTRATIVO DA PROJEÇÃO ATUARIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES';
    const HEADER_3 = "ORÇAMENTO DA SEGURIDADE SOCIAL";

    public function montar()
    {
        $dadosRelatorio = $this->relatorio->getDados();

        if (empty($dadosRelatorio)) {
            $msg = 'Não é possível emitir o relatório, pois não existem valores configurados na Edição Manual para as';
            $msg .= ' Instituições selecionadas.';
            throw new BusinessException($msg);
        }

        /*
         * Reinicia o array para pegar o ano de início
         * e o ano final do exercício
         */
        reset($dadosRelatorio);

        $this->iAltura = 4;

        $this->imprimirCabecalho(true, 1);

        for ($linha = 1; $linha <= 2; $linha++) {
            foreach ($dadosRelatorio[$linha] as $dados) {
                $this->imprimirCabecalho(false, $linha);
                $this->imprimeInfoProxPagina(false);

                $this->pdf->SetFont('arial', '', 6);
                $this->pdf->Cell(23, $this->iAltura, $dados->ano, "TRB", 0, "C", 0);
                $valor1 = db_formatar($dados->receitasprevidenciarias, "f");
                $this->pdf->Cell(40, $this->iAltura, $valor1, "TRB", 0, "R", 0);
                $valor2 = db_formatar($dados->despesasprevidenciarias, "f");
                $this->pdf->Cell(40, $this->iAltura, $valor2, "TRB", 0, "R", 0);
                $valor3 = db_formatar($dados->resultadoprevidenciario, "f");
                $this->pdf->Cell(40, $this->iAltura, $valor3, "TRB", 0, "R", 0);
                $valor4 = db_formatar($dados->saldofinanceiro, "f");
                $this->pdf->Cell(48, $this->iAltura, $valor4, "TLB", 1, "R", 0);
            }

            $this->pdf->ln();

            if (!empty($dadosRelatorio[$linha + 1])) {
                if ($this->quebrarPagina()) {
                    $this->pdf->AddPage();
                    $this->imprimeInfoProxPagina(true);
                }

                $this->montaCabecalho($linha + 1);
            }
        }

        $this->relatorio->getNotaExplicativa($this->pdf, $this->relatorio->getPeriodo()->getCodigo());

        if ($this->pdf->getAvailHeight() < 30) {
            $this->pdf->AddPage();
        }

        $assinatura = new cl_assinatura();
        $this->pdf->ln(18);
        assinaturas($this->pdf, $assinatura, 'BG', false, false);

        $this->pdf->Output();
    }

    /**
     * Impime cabecalho do relatorio
     *
     * @param bool $imprime
     */
    public function imprimirCabecalho($imprime, $linha)
    {

        if ($this->quebrarPagina() || $imprime) {
            $this->pdf->SetFont('arial', 'b', 6);
            if (!$imprime) {
                $this->pdf->AddPage();
                $this->imprimeInfoProxPagina(true);
            } else {
                $this->pdf->SetFillColor("777");
                $str = "RREO - ANEXO 10 (LRF, art. 53, § 1º, inciso II )";
                $this->pdf->Cell(100, 5, $str, "", 0, "L", 0);
                $this->pdf->Cell(85, 5, "Em Reais", "", 1, "R", 0);
            }
            /*
             * Cabeçalho a ser Repetido nas paginas
             */
            $this->montaCabecalho($linha);
        }
    }

    /**
     * Impime informacao da proxima pagina no relatorio
     *
     * @param bool $imprime
     */
    public function imprimeInfoProxPagina($imprime)
    {
        if ($this->pdf->GetY() > $this->pdf->h - 31 || $imprime) {
            $this->pdf->SetFont('arial', '', 6);
            if ($imprime) {
                $str = 'Continuação ' . ($this->pdf->PageNo()) . "/{nb}";
                $this->pdf->Cell(190, ($this->iAltura * 2), $str, 'T', 1, "R", 0);
            } else {
                $str = 'Continua na página ' . ($this->pdf->PageNo() + 1) . "/{nb}";
                $this->pdf->Cell(190, ($this->iAltura * 3), $str, 'T', 1, "R", 0);
                $this->imprimirCabecalho(false);
            }
            $this->pdf->SetFont('arial', 'b', 6);
        }
    }

    /**
     * Monta o cabeçalho do relatório de acordo com a linha informada.
     * @param int $linha
     */
    protected function montaCabecalho($linha)
    {
        $desricaoLinha = "PLANO PREVIDENCIÁRIO";

        if ($linha == 2) {
            $desricaoLinha = "PLANO FINANCEIRO";
        }
        $this->pdf->SetFont('arial', 'b', 6);
        $h = $this->iAltura;
        $this->pdf->Cell(191, $h, $desricaoLinha, "T", 1, "C", 0);

        $this->pdf->Cell(23, $h, "", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $h, "RECEITAS", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $h, "DESPESAS", "TR", 0, "C", 0);
        $this->pdf->Cell(40, $h, "RESULTADO", "TLR", 0, "C", 0);
        $this->pdf->Cell(48, $h, "SALDO FINANCEIRO", "TL", 1, "C", 0);

        $this->pdf->Cell(23, $h, "EXERCÍCIO", "R", 0, "C", 0);
        $this->pdf->Cell(40, $h, "PREVIDENCIÁRIAS", "LR", 0, "C", 0);
        $this->pdf->Cell(40, $h, "PREVIDENCIÁRIAS", "LR", 0, "C", 0);
        $this->pdf->Cell(40, $h, "PREVIDENCIÁRIO", "LR", 0, "C", 0);
        $this->pdf->Cell(48, $h, "DO EXERCÍCIO", "L", 1, "C", 0);

        $this->pdf->Cell(23, $h, "", "R", 0, "C", 0);
        $this->pdf->Cell(40, $h, "(a)", "L", 0, "C", 0);
        $this->pdf->Cell(40, $h, "(b)", "L", 0, "C", 0);
        $this->pdf->Cell(40, $h, "(c)=(a-b)", "L", 0, "C", 0);
        $this->pdf->Cell(48, $h, "(d)=('d' exercício anterior)+(c)", "L", 1, "C", 0);
        $this->pdf->SetFont('arial', '', 6);
    }

    /**
     * Valida se chegou no limite da impressão
     * @return bool
     */
    private function quebrarPagina()
    {
        return $this->pdf->GetY() > $this->pdf->h - 40;
    }
}
