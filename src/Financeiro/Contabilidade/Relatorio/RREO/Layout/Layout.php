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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Layout;

abstract class Layout
{

    protected $oAnexo;

    /**
     * @var \PDFDocument
     */
    protected $oPdf;

    /**
     * @var array
     */
    protected $colunas = [];


    /**
     * @param $oAnexo
     */
    public function setAnexo($oAnexo)
    {
        $this->oAnexo = $oAnexo;
    }

    /**
     * @param array $aLinhas
     */
    protected function imprimirLinhas($aLinhas)
    {

        foreach ($aLinhas as $oLinha) {
            //adiciona bold na linha
            if ($oLinha->lBold) {
                $this->oPdf->SetFont('Arial', 'B');
            }

            if ($oLinha->lMultiCell) {
                $this->imprimeMultiCell($oLinha);
            } else {
                $this->imprimeCell($oLinha);
            }
            //remove bold na linha
            $this->oPdf->SetFont('');
        }
    }

    public function pageHeader()
    {
    }

    /**
     * Imprime uma linha com conteúdo em multi celula
     * @param $oLinha
     */
    private function imprimeMultiCell($oLinha)
    {
        /**
         * Variáveis para controle das celulas
         */
        $aAlturaLinha = array();
        foreach ($this->colunas as $coluna) {
            $aAlturaLinha[] = $this->oPdf->NbLines($coluna->tamanho, $oLinha->{$coluna->campo});
        }
        $iLinhas = array_reduce($aAlturaLinha, "DBNumber::maiorValor");
        $tamanhoLinha = $iLinhas > 1 ? 3 : 4;
        $iAlturaLinha = $tamanhoLinha * $iLinhas;

        if ($this->oPdf->GetY() + $iAlturaLinha > $this->oPdf->h - 15) {
            $this->oPdf->AddPage();
            $this->pageHeader();
            if ($oLinha->lBold) {
                $this->oPdf->SetFont('Arial', 'B');
            }
        }
        $iYAntes = $this->oPdf->getY();
        $iX = $this->oPdf->getX();

        $aDadosBordas = array();
        $formatos = $oLinha->formato;

        foreach ($this->colunas as $coluna) {
            $valorCelula = $oLinha->{$coluna->campo};
            if (!empty($coluna->formato)) {
                $formatos[] = $coluna->formato;
            }
            foreach ($formatos as $formato) {
                switch ($formato) {
                    case 'moeda':
                        if (is_numeric($valorCelula)) {
                            $valorCelula = number_format($valorCelula, 2, ',', '.');
                        }
                        break;
                    case 'abs':
                        if (is_numeric($valorCelula)) {
                            $valorCelula = abs($valorCelula);
                        }
                        break;
                }
            }
            $totalLinhasCelula = $this->oPdf->NbLines($coluna->tamanho, $oLinha->{$coluna->campo});
            $this->oPdf->SetXY($iX, $iYAntes);
            if ($totalLinhasCelula > 1) {
                $this->oPdf->MultiCell(
                    $coluna->tamanho,
                    $tamanhoLinha,
                    $valorCelula,
                    0,
                    $coluna->alinhamento,
                    $coluna->preenchimento
                );
            } else {
                $this->oPdf->Cell(
                    $coluna->tamanho,
                    $iAlturaLinha,
                    $valorCelula,
                    0,
                    0,
                    $coluna->alinhamento,
                    $coluna->preenchimento
                );
            }

            // guarda os dados da impressão para desenhar as bordas depois
            $oStd = new \stdClass();
            $oStd->tipoBorda = $coluna->borda;
            if (!empty($oLinha->borda)) {
                $oStd->tipoBorda .= $oLinha->borda;
            }
            $oStd->x = $iX;
            $oStd->w = $coluna->tamanho;
            $oStd->h = $iAlturaLinha;
            $oStd->yInicial = $iYAntes;
            $aDadosBordas[] = $oStd;
            $iX += $coluna->tamanho;
        }

        $this->imprimeBordas($aDadosBordas);
        $this->oPdf->setY($iYAntes + $iAlturaLinha);
    }

    /**
     * Realiza a impressao das bordas dos relatorios
     * @param array $aDadosBordas
     */
    private function imprimeBordas($aDadosBordas)
    {

        foreach ($aDadosBordas as $oDados) {
            switch ($oDados->tipoBorda) {
                case 1:
                case 'LRTB':
                    // borda em cima
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial);
                    // borda em baixo
                    $this->oPdf->line(
                        $oDados->x,
                        $oDados->yInicial + $oDados->h,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    // borda a direita
                    $this->oPdf->line(
                        $oDados->x + $oDados->w,
                        $oDados->yInicial,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    // borda a esqueda
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h);
                    break;

                case 'TBR':
                case 'RTB':
                case 'TRB':
                    // borda em cima
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial);
                    // borda em baixo
                    $this->oPdf->line(
                        $oDados->x,
                        $oDados->yInicial + $oDados->h,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    // borda a direita
                    $this->oPdf->line(
                        $oDados->x + $oDados->w,
                        $oDados->yInicial,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    break;

                case 'TBL':
                case 'LTB':
                    // borda em cima
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial);
                    // borda em baixo
                    $this->oPdf->line(
                        $oDados->x,
                        $oDados->yInicial + $oDados->h,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    // borda a esqueda
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h);
                    break;

                case 'TB':
                case 'BT':
                    // borda em cima
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial);
                    // borda em baixo
                    $this->oPdf->line(
                        $oDados->x,
                        $oDados->yInicial + $oDados->h,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    break;
                case 'L':
                    // borda a esqueda
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h);
                    break;
                case 'R':
                    // borda a direita
                    $this->oPdf->line(
                        $oDados->x + $oDados->w,
                        $oDados->yInicial,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );

                    break;
                case 'RL':
                case 'LR':
                    // borda a direita
                    $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h);
                    // borda a esqueda
                    $this->oPdf->line(
                        $oDados->x + $oDados->w,
                        $oDados->yInicial,
                        $oDados->x + $oDados->w,
                        $oDados->yInicial + $oDados->h
                    );
                    break;
            }
        }
    }

    /**
     * @param $oLinha
     */
    private function imprimeCell($oLinha)
    {

        foreach ($oLinha->aColunas as $oColuna) {
            $this->oPdf->Cell(
                $oColuna->w,
                $oColuna->h,
                $oColuna->value,
                $oColuna->border,
                $oColuna->ln,
                $oColuna->align,
                $oColuna->fill
            );
        }
    }

    /**
     * @return array
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    /**
     * @param array $colunas
     */
    public function setColunas(array $colunas)
    {
        $this->colunas = $colunas;
    }
}
