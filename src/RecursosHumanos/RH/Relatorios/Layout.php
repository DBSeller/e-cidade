<?php

namespace ECidade\RecursosHumanos\RH\Relatorios;

use Instituicao;
use PDFDocument;

/**
 * Class Layout
 * @package ECidade\RecursosHumanos\RH\Relatorios
 */
abstract class Layout
{
    /**
     * @var int
     */
    const ALTURA_LINHA = 4;

    /**
     * @var int
     */
    const TAMANHO_FONTE = 6;

    /**
     * @var PDFDocument
     */
    protected $pdf;

    /**
     * @var int
     */
    protected $largura;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * Layout constructor.
     * @param string $orientacao
     */
    final public function __construct($orientacao = PDFDocument::PRINT_LANDSCAPE)
    {
        switch ($orientacao) {
            case PDFDocument::PRINT_LANDSCAPE:
                $this->largura = 277;
                break;
            case PDFDocument::PRINT_PORTRAIT:
                $this->largura = 190;
                break;
        }

        $this->inicializarPdf($orientacao);
    }

    /**
     * @param string $orientacao
     */
    final private function inicializarPdf($orientacao)
    {
        $pdf = new PDFDocument($orientacao);
        $pdf->Open();
        $pdf->setAutoPageBreak(false);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);

        $this->pdf = $pdf;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return string
     */
    final public function imprimir()
    {
        $this->montarEnteFederativo();
        $this->montarDescricao();
        $this->pdf->AddPage();
        $this->pdf->SetFont('arial', '', static::TAMANHO_FONTE);
        $this->montar();

        $arquivo = 'tmp/' . time() . '.pdf';

        $this->pdf->Output($arquivo, false, true);

        return $arquivo;
    }

    /**
     * @return $this
     */
    final private function montarEnteFederativo()
    {
        $this->pdf->addHeaderDescription($this->instituicao->getDescricao());

        return $this;
    }

    /**
     *
     */
    abstract protected function montarDescricao();

    /**
     *
     */
    abstract protected function montar();

    /**
     * @param float $largura
     * @param float $altura
     * @param string $texto
     * @param int $x
     * @param bool $borda
     * @param string $alinhamento
     * @param bool $preencher
     * @param bool $indentar
     * @return int
     */
    final protected function montarLinha(
        $largura,
        $altura,
        $texto,
        $x = 10,
        $borda = true,
        $alinhamento = PDFDocument::ALIGN_JUSTIFY,
        $preencher = false,
        $indentar = false
    ) {
        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($largura, $altura, $texto, $borda, $alinhamento, $preencher, $indentar);
        $x += $largura;
        $this->pdf->SetXY($x, $y);

        return $x;
    }

    /**
     * @return int
     */
    protected function quebrarLinha()
    {
        $this->pdf->Ln(static::ALTURA_LINHA);
        return 10;
    }
}
