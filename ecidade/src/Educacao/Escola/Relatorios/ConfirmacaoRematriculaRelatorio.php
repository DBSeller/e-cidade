<?php

namespace ECidade\Educacao\Escola\Relatorios;

use PDFDocument;

/**
 * Class ConfirmacaoRematriculaRelatorio
 * @package ECidade\Educacao\Escola\Relatorio
 */
class ConfirmacaoRematriculaRelatorio
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
     * @var string
     */
    const HEADER_1 = '<HEADER_1>';

    /**
     * @var string
     */
    const HEADER_2 = '<HEADER_2>';

    /**
     * @var string
     */
    const HEADER_3 = '';

    /**
     * @var string
     */
    const LARGURA_COLUNA_ANO_VIGENTE = 47.5;

    /**
     * @var string
     */
    const LARGURA_COLUNA_ANO_PROXIMO = 31.68;

    /**
     * @var PDFDocument
     */
    protected $pdf;

    /**
     * @var array
     */
    private $dados;

    /**
     * @var int
     */
    private $alturaLinha;

    /**
     * ConfirmacaoRematriculaRelatorio constructor.
     */
    final public function __construct()
    {
        $this->dados = array();
        $this->inicializarPdf();
    }

    /**
     *
     */
    final private function inicializarPdf()
    {
        $pdf = new PDFDocument();
        $pdf->Open();
        $pdf->SetAutoPageBreak(false);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);

        $this->pdf = $pdf;
        $this->alturaLinha = static::ALTURA_LINHA;
    }

    /**
     * @return string
     */
    final public function imprimir()
    {
        $this->pdf->AddPage();
        $this->pdf->SetFont('arial', '', static::TAMANHO_FONTE);

        $this->montar();

        return $this->pdf->savePDF('confirmacao_rematricula_' . date('Y-m-d_H-i-s'));
    }


    /**
     *
     */
    private function montar()
    {
        $this->montarCabecalho();

        foreach ($this->dados as $dado) {
            if (static::ALTURA_LINHA * 4 > $this->pdf->getAvailHeight()) {
                $this->pdf->AddPage();
                $this->montarCabecalho();
            }

            $this->montarLinha(static::LARGURA_COLUNA_ANO_VIGENTE, $dado->etapaVigente, 'C');
            $this->montarLinha(static::LARGURA_COLUNA_ANO_VIGENTE, $dado->rematriculasConfirmadas, 'C');
            $this->montarLinha(static::LARGURA_COLUNA_ANO_PROXIMO, $dado->etapaProxima, 'C');
            $this->montarLinha(static::LARGURA_COLUNA_ANO_PROXIMO, $dado->totalVagas, 'C');
            $this->montarLinha(static::LARGURA_COLUNA_ANO_PROXIMO, '', 'L', true);
        }
    }

    /**
     *
     */
    private function montarCabecalho()
    {
        $x = $this->montarCabecalhoColuna(95, static::ALTURA_LINHA, 'Ano Letivo Vigente');

        $this->montarCabecalhoColuna(95, static::ALTURA_LINHA, 'Próximo Ano Letivo', $x);
        $this->pdf->Ln();

        $x = $this->montarCabecalhoColuna(static::LARGURA_COLUNA_ANO_VIGENTE, static::ALTURA_LINHA, 'Etapa');
        $x = $this->montarCabecalhoColuna(
            static::LARGURA_COLUNA_ANO_VIGENTE,
            static::ALTURA_LINHA,
            'Rematrículas Confirmadas',
            $x
        );
        $x = $this->montarCabecalhoColuna(static::LARGURA_COLUNA_ANO_PROXIMO, static::ALTURA_LINHA, 'Etapa', $x);
        $x = $this->montarCabecalhoColuna(
            static::LARGURA_COLUNA_ANO_PROXIMO,
            static::ALTURA_LINHA,
            'Total de Vagas',
            $x
        );

        $this->montarCabecalhoColuna(
            static::LARGURA_COLUNA_ANO_PROXIMO,
            static::ALTURA_LINHA,
            'Estimativas de Vagas',
            $x
        );
        $this->pdf->Ln();
    }

    /**
     * @param $largura
     * @param $altura
     * @param $valor
     * @param int $x
     * @return int
     */
    final protected function montarCabecalhoColuna($largura, $altura, $valor, $x = 10)
    {
        $this->pdf->setBold(1);
        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($largura, $altura, $valor, 1, 'C', 1);
        $x += $largura;
        $this->pdf->SetXY($x, $y);
        $this->pdf->SetFont('arial', '');
        $this->pdf->setBold(0);

        return $x;
    }

    /**
     * @param $largura
     * @param string $valor
     * @param string $alinhamento
     * @param bool $quebra
     * @return $this
     */
    final protected function montarLinha(
        $largura,
        $valor = '',
        $alinhamento = 'L',
        $quebra = false
    ) {
        $this->pdf->setBold(0);

        $linhasOcupadas = $this->pdf->NbLines($largura, $valor);

        if ($linhasOcupadas > 1) {
            $y = $this->pdf->GetY();
            $x = $this->pdf->GetX() + $largura;
            $this->pdf->MultiCell($largura, static::ALTURA_LINHA, $valor, 1, $alinhamento);
            $this->pdf->SetXY($x, $y);

            $this->alturaLinha = static::ALTURA_LINHA * $linhasOcupadas;
        } else {
            $this->pdf->Cell($largura, $this->alturaLinha, $valor, 1, $quebra, $alinhamento);
        }

        if ($quebra) {
            $this->alturaLinha = static::ALTURA_LINHA;
        }

        $this->pdf->setBold(0);

        return $this;
    }

    /**
     * @param array $dados
     */
    public function setDados(array $dados)
    {
        $this->dados = $dados;
    }

    /**
     * @param $descricao
     * @return $this
     */
    public function adicionarDescricao($descricao)
    {
        $this->pdf->addHeaderDescription($descricao);
        return $this;
    }
}
