<?php


namespace ECidade\Core\Relatorios\Layout\PDF;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;
use ECidade\Core\Relatorios\Layout\Layout;
use FPDF;

/**
 * Class RelatorioPdf
 * @package ECidade\Core\Relatorios\Layout\PDF
 */
abstract class RelatorioPdf implements Layout
{
    /**
     * @var FPDF
     */
    protected $pdf;
    /**
     * Array com os dados a ser impressos
     * @var array
     */
    protected $dados = [];
    /**
     * @var CampoDinamico[]
     */
    protected $campos = [];

    public function __construct(FPDF $pdf, $dados)
    {
        $this->pdf = $pdf;
        $this->dados = $dados;
    }

    abstract public function imprimir($fileName = null);

    /**
     * @param CampoDinamico[] $campos
     * @return Layout
     */
    public function setCampos(array $campos)
    {
        $this->campos = $campos;
        return $this;
    }

    protected function imprimirLinhas($linhas)
    {
        /**
         * @var Linha[] $linhas
         */
        foreach ($linhas as $linha) {
            if ($linha->chamarMetodo) {
                $this->{$linha->nomeMetodo}($this);
            } else {
                //adiciona bold na linha
                if ($linha->bold) {
                    $this->pdf->SetFont('Arial', 'B');
                }

                if ($linha->multiCell) {
                    $this->imprimeMultiCell($linha);
                } else {
                    $this->imprimeColunas($linha);
                }
                //remove bold na linha
                $this->pdf->SetFont('');
            }
        }
    }

    /**
     * @param Linha $linha
     */
    private function imprimeMultiCell($linha)
    {
        // Variáveis para controle das celulas
        $aAlturaLinha = array();
        foreach ($linha->getColunas() as $coluna) {
            $aAlturaLinha[] = $this->pdf->NbLines($coluna->w, $coluna->value);
        }
        $iLinhas = array_reduce($aAlturaLinha, "DBNumber::maiorValor");
        $iAlturaLinha = $linha->alturaLinha * $iLinhas;

        $yAntes = $this->pdf->getY();
        $iX = $this->pdf->getX();

        $aDadosBordas = array();

        foreach ($linha->getColunas() as $coluna) {
            $this->pdf->SetXY($iX, $yAntes);
            $this->pdf->MultiCell($coluna->w, $coluna->h, $coluna->value, 0, $coluna->align, $coluna->fill);

            // guarda os dados da impressão para desenhar as bordas depois
            $oStd            = new \stdClass();
            $oStd->tipoBorda = $coluna->border;
            $oStd->x         = $iX;
            $oStd->w         = $coluna->w;
            $oStd->h         = $iAlturaLinha;
            $oStd->yInicial  = $yAntes;

            $aDadosBordas[] = $oStd;
            $iX  += $coluna->w;
        }

        $this->imprimeBordas($aDadosBordas);
        $this->pdf->setY($yAntes + $iAlturaLinha);
    }

    /**
     * @param array $aDadosBordas
     */
    private function imprimeBordas($aDadosBordas)
    {
        foreach ($aDadosBordas as $oDados) {
            $eixoXFinal = $oDados->x + $oDados->w;
            $eixoYInferior = $oDados->yInicial + $oDados->h;
            switch ($oDados->tipoBorda) {
                case 1:
                    // borda em cima
                    $this->pdf->line($oDados->x, $oDados->yInicial, $eixoXFinal, $oDados->yInicial);
                    // borda em baixo
                    $this->pdf->line($oDados->x, $eixoYInferior, $eixoXFinal, $eixoYInferior);
                    // borda a direita
                    $this->pdf->line($eixoXFinal, $oDados->yInicial, $eixoXFinal, $eixoYInferior);
                    // borda a esqueda
                    $this->pdf->line($oDados->x, $oDados->yInicial, $oDados->x, $eixoYInferior);
                    break;

                case 'TBR':
                    // borda em cima
                    $this->pdf->line($oDados->x, $oDados->yInicial, $eixoXFinal, $oDados->yInicial);
                    // borda em baixo
                    $this->pdf->line($oDados->x, $eixoYInferior, $eixoXFinal, $eixoYInferior);
                    // borda a direita
                    $this->pdf->line($eixoXFinal, $oDados->yInicial, $eixoXFinal, $eixoYInferior);
                    break;

                case 'TBL':
                    // borda em cima
                    $this->pdf->line($oDados->x, $oDados->yInicial, $eixoXFinal, $oDados->yInicial);
                    // borda em baixo
                    $this->pdf->line($oDados->x, $eixoYInferior, $eixoXFinal, $eixoYInferior);
                    // borda a esqueda
                    $this->pdf->line($oDados->x, $oDados->yInicial, $oDados->x, $eixoYInferior);
                    break;

                case 'TB':
                case 'BT':
                    // borda em cima
                    $this->pdf->line($oDados->x, $oDados->yInicial, $eixoXFinal, $oDados->yInicial);
                    // borda em baixo
                    $this->pdf->line($oDados->x, $eixoYInferior, $eixoXFinal, $eixoYInferior);
                    break;
                case 'L':
                    // borda a esqueda
                    $this->pdf->line($oDados->x, $oDados->yInicial, $oDados->x, $eixoYInferior);
                    break;
                case 'R':
                    // borda a direita
                    $this->pdf->line($eixoXFinal, $oDados->yInicial, $eixoXFinal, $eixoYInferior);

                    break;
                case 'RL':
                case 'LR':
                    // borda a direita
                    $this->pdf->line($oDados->x, $oDados->yInicial, $oDados->x, $eixoYInferior);
                    // borda a esqueda
                    $this->pdf->line($eixoXFinal, $oDados->yInicial, $eixoXFinal, $eixoYInferior);
                    break;
            }
        }
    }

    /**
     * @param Linha $linha
     */
    private function imprimeColunas($linha)
    {
        $quebraLinha = false;
        foreach ($linha->getColunas() as $coluna) {
            if ($coluna instanceof ColunaCampo) {
                $this->imprimeCelulaColunaCampo($coluna);
                $quebraLinha = true;
            } else {
                $this->imprimeCelula($coluna);
            }
        }

        if ($quebraLinha) {
            $this->pdf->Ln();
        }
    }

    /**
     * @param $self
     */
    public function addPage($self)
    {
        $self->pdf->AddPage();
    }

    /**
     * @param Coluna $coluna
     */
    private function imprimeCelula(Coluna $coluna)
    {
        $this->pdf->Cell(
            $coluna->w,
            $coluna->h,
            $coluna->value,
            $coluna->border,
            $coluna->ln,
            $coluna->align,
            $coluna->fill
        );
    }

    private function imprimeCelulaColunaCampo(ColunaCampo $coluna)
    {
        $tamanhoFonteOriginal = 7;
        $this->pdf->SetFont('Arial', 'b', 7);

        $label = "{$coluna->getCampo()->getLabel()}:  ";

        $labelSize = $this->pdf->getStringWidth($label);
        $contentSize = $coluna->getCampo()->getWidth() - $labelSize;
        $this->pdf->Cell($labelSize, 4, $label);
        $this->pdf->SetFont('Arial', '', 7);

        $conteudo = trim($coluna->getCampo()->getValue());
        $tamanhoString = $this->pdf->GetStringWidth($conteudo);

        if ($tamanhoString > $contentSize) {
            // Deixa a fonte EXATAMENTE no tamanho para caber na célula
            $tamanhoFonte = $tamanhoFonteOriginal * $contentSize / $tamanhoString;
            $this->pdf->setFontSize($tamanhoFonte);
        }

        $this->pdf->cell($contentSize, 4, $conteudo, 0, 0, 'L');
        $this->pdf->setFontSize($tamanhoFonteOriginal);
    }
}
