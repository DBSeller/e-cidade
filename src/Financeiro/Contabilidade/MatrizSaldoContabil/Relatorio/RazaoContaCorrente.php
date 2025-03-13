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

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Relatorio;

use PDFDocument;
use DBDate;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente as RazaoContaCorrenteModel;

/**
 * Class RazaoContaCorrente
 * @package ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Relatorio
 */
class RazaoContaCorrente
{
    const ALTURA_LINHA = 4;

    /**
     * @var PDFDocument
     */
    private $pdf;

    /**
     * @var array
     */
    private $lancamentos;

    /**
     * RazaoContaCorrente constructor.
     * @param $lancamentos
     * @param DBDate $dataInicio
     * @param DBDate $dataFim
     */
    public function __construct($lancamentos, DBDate $dataInicio, DBDate $dataFim)
    {
        $pdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
        $pdf->Open();
        $pdf->setAutoPageBreak(false);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);
        $pdf->addHeaderDescription("RAZÃO POR CONTA CORRENTE");
        $pdf->addHeaderDescription("\n");
        $pdf->addHeaderDescription("\n");

        $header = $dataInicio->getDate(DBDate::DATA_PTBR);
        $header .= " a " . $dataFim->getDate(DBDate::DATA_PTBR);
        $pdf->addHeaderDescription("Período: $header");

        $this->pdf = $pdf;
        $this->lancamentos = $lancamentos;
    }

    /**
     * Impressão do relatório
     * @return string
     */
    public function imprimir()
    {
        $this->pdf->SetFont('arial', '', 7);
        $this->pdf->setfillcolor(235);

        foreach ($this->lancamentos as $reduzido => $lancamentos) {
            $this->pdf->AddPage();

            $primeiroLancamento = current($lancamentos);

            $this->pdf->Ln(2);
            $this->pdf->Cell(20, self::ALTURA_LINHA, "REDUZIDO:", 0, 0, "L", 0);
            $this->pdf->Cell(20, self::ALTURA_LINHA, $reduzido, 0, 1, "L", 0);
            $this->pdf->Cell(20, self::ALTURA_LINHA, "ESTRUTURAL:", 0, 0, "L", 0);
            $this->pdf->Cell(20, self::ALTURA_LINHA, $primeiroLancamento->getEstrutural(), 0, 1, "L", 0);
            $this->pdf->Cell(20, self::ALTURA_LINHA, "DESCRIÇÃO:", 0, 0, "L", 0);
            $this->pdf->Cell(20, self::ALTURA_LINHA, $primeiroLancamento->getDescricaoEstrutural(), 0, 0, "L", 0);
            $this->pdf->Ln();
            $this->pdf->Ln();

            $this->imprimirCabecalho();

            $fill = 1;
            $hasAnterior = null;
            foreach ($lancamentos as $lancamento) {
                if ($this->pdf->getAvailHeight() < 20) {
                    $this->pdf->AddPage();
                    $this->imprimirCabecalho();
                }

                $this->imprimirLancamentos($lancamento, $fill,$hasAnterior);
                $fill = $fill == 1 ? 0 : 1;
                $hasAnterior = substr($lancamento->getHashAtributos(), 10);
            }
            $this->pdf->Line(10, $this->pdf->getY(), 287, $this->pdf->getY());
        }

        $nomeArquivo = "razao_conta_corrente_" . time();
        return $this->pdf->savePDF($nomeArquivo);
    }

    /**
     * Impressão do cabeçalho dos lançamentos no relatório
     */
    protected function imprimirCabecalho()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(17, self::ALTURA_LINHA, "DATA", "TBR", 0, "L", 0);
        $this->pdf->Cell(160, self::ALTURA_LINHA, "CONTA CORRENTE", "TBR", 0, "L", 0);
        $this->pdf->Cell(25, self::ALTURA_LINHA, "SALDO INICIAL", "TBR", 0, "L", 0);
        $this->pdf->Cell(25, self::ALTURA_LINHA, "DÉBITO", "TBR", 0, "L", 0);
        $this->pdf->Cell(25, self::ALTURA_LINHA, "CRÉDITO", "TBR", 0, "L", 0);
        $this->pdf->Cell(25, self::ALTURA_LINHA, "SALDO FINAL", "TB", 0, "L", 0);
        $this->pdf->Ln();
    }

    /**
     * Imprimir a linha dos lançamentos
     * @param RazaoContaCorrenteModel $lancamento
     * @param $fill
     */
    protected function imprimirLancamentos(RazaoContaCorrenteModel $lancamento, $fill, $hashAnterior= null)
    {
        $this->pdf->setBold(false);

        $data = $lancamento->getDataMovimentacao()->getDate(DBDate::DATA_PTBR);

        if ($hashAnterior != null && $hashAnterior != substr($lancamento->getHashAtributos(), 10)) {
            $this->pdf->Line(10, $this->pdf->getY(), 287, $this->pdf->getY());
        }
        $border = "0";
        $this->pdf->Cell(17, self::ALTURA_LINHA, $data, $border, 0, "L", $fill);
        $this->pdf->Cell(160, self::ALTURA_LINHA, "", $border, 0, "L", $fill);

        //$documento = $lancamento->getCodigoDocumento() . " - " . $lancamento->getDescricaoDocumento();
       //$this->pdf->Cell(80, self::ALTURA_LINHA, $documento, "0", 0, "L", $fill);

        $naturezaSaldoAnterior = $lancamento->getNaturezaSaldoAnterior();
        $saldoAnterior = db_formatar($lancamento->getSaldoAnterior(), 'f') . " " . $naturezaSaldoAnterior;
        $this->pdf->Cell(25, self::ALTURA_LINHA, $saldoAnterior, $border, 0, "R", $fill);

        $debito = db_formatar($lancamento->getMovimentacaoDebito(), 'f');
        $this->pdf->Cell(25, self::ALTURA_LINHA, $debito, $border, 0, "R", $fill);

        $credito = db_formatar($lancamento->getMovimentacaoCredito(), 'f');
        $this->pdf->Cell(25, self::ALTURA_LINHA, $credito, $border, 0, "R", $fill);

        $naturezaSaldoFinal = $lancamento->getNaturezaSaldoFinal();
        $saldoFinal = db_formatar($lancamento->getSaldoFinal(), 'f') . " " . $naturezaSaldoFinal;
        $this->pdf->Cell(25, self::ALTURA_LINHA, $saldoFinal, $border, 0, "R", $fill);

        $atributos = $lancamento->getAtributos();
        $this->pdf->setBold(true);

        if (!empty($atributos)) {
            $this->pdf->Ln();
            $this->pdf->Cell(17, self::ALTURA_LINHA, "", $border, 0, "L", $fill);
        }

        $usaDescricao = false;
        $tamanhoCompletaLinha = 260;
        $stringAtributo = array_reduce(array_keys($atributos), function ($contaCorrente, $key) use ($atributos) {

           return $contaCorrente .= "{$key}: ".$atributos[$key]." ";
        });

        $this->pdf->Cell(260, self::ALTURA_LINHA, $stringAtributo, $border, 0, "L", $fill);
        $this->pdf->Ln();
    }
}
