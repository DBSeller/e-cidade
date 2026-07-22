<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio;

include_once modification('libs/db_stdlib.php');
include_once modification('libs/db_liborcamento.php');
include_once modification('libs/db_libcontabilidade.php');

use cl_assinatura;
use Instituicao;
use InstituicaoRepository;
use PDFDocument;
use RelatoriosLegaisBase;

/**
 * Class Layout
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO
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
     * @var RelatoriosLegaisBase
     */
    protected $relatorio;

    /**
     * @var PDFDocument
     */
    protected $pdf;

    /**
     * @var string
     */
    protected $assinatura = 'LRF';

    /**
     * @var int
     */
    private $alturaLinha;

    /**
     * Layout constructor.
     */
    public final function __construct($orientacao = PDFDocument::PRINT_LANDSCAPE)
    {
        $this->inicializarPdf($orientacao);
    }

    /**
     *
     */
    private final function inicializarPdf($orientacao)
    {
        $pdf = new PDFDocument($orientacao);
        $pdf->Open();
        $pdf->setAutoPageBreak(false);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);

        $this->pdf = $pdf;
        $this->alturaLinha = static::ALTURA_LINHA;
    }

    /**
     *
     */
    public final function imprimir()
    {
        $this->montarEnteFederativo()->montarDescricao();

        $this->pdf->AddPage();
        $this->pdf->SetFont('arial', '', static::TAMANHO_FONTE);

        $this->montar();
        $this->montarRodape();

        $this->pdf->Output();
    }

    /**
     * @return $this
     */
    private final function montarDescricao()
    {
        $this->pdf->addHeaderDescription(static::HEADER_1);
        $this->pdf->addHeaderDescription(static::HEADER_2);

        if (static::HEADER_3) {
            $this->pdf->addHeaderDescription(static::HEADER_3);
        }

        $this->pdf->addHeaderDescription($this->relatorio->getTituloPeriodo());

        return $this;
    }

    /**
     * @return $this
     */
    private final function montarEnteFederativo()
    {
        $instituicoes = $this->relatorio->getInstituicoes();
        $instituicoes = is_array($instituicoes) ?: explode(',', $instituicoes);

        if (count($instituicoes) == 1) {
            $instituicao = array_shift($instituicoes);
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($instituicao);

            if ($instituicao->getTipo() != Instituicao::TIPO_PREFEITURA) {
                $enteFederacao = $instituicao->getDescricao();
            } else {
                $enteFederacao = DemonstrativoFiscal::getEnteFederativo($instituicao);
            }
        } else {
            $prefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
            $enteFederacao = DemonstrativoFiscal::getEnteFederativo($prefeitura);
        }

        $this->pdf->addHeaderDescription($enteFederacao);

        return $this;
    }

    /**
     *
     */
    protected abstract function montar();

    /**
     *
     */
    private final function montarRodape()
    {
        $this->pdf->Ln(5);

        $this->relatorio->getNotaExplicativa($this->pdf, $this->relatorio->getPeriodo()->getCodigo());

        $this->pdf->Ln(30);

        $clAssinatura = new cl_assinatura();
        assinaturas($this->pdf, $clAssinatura, $this->assinatura);
    }

    /**
     * @param RelatoriosLegaisBase $relatorio
     */
    public final function definirRelatorio(RelatoriosLegaisBase $relatorio)
    {
        $this->relatorio = $relatorio;
    }

    /**
     * @param $largura
     * @param $altura
     * @param $valor
     * @param int $x
     * @return int
     */
    protected final function montarCabecalhoColuna($largura, $altura, $valor, $x = 10)
    {
        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($largura, $altura, $valor, 1, 'C', 1);
        $x += $largura;
        $this->pdf->SetXY($x, $y);

        return $x;
    }

    /**
     * @param $largura
     * @param string $valor
     * @param bool $totalizador
     * @param bool $preenche
     * @param bool $quebra
     * @param bool $nivel
     * @return $this
     */
    protected final function montarLinha(
        $largura,
        $valor = '',
        $totalizador = false,
        $preenche = false,
        $quebra = false,
        $nivel = false
    ) {
        $borda = 'LR';
        $alinhamento = 'L';

        if ($nivel && $nivel > 1) {
            $valor = str_repeat('   ', $nivel) . $valor;
        }

        if ($totalizador) {
            $this->pdf->setBold(1);
            $borda .= 'T';
        } else {
            $this->pdf->setBold(0);
        }

        if ($preenche) {
            $borda .= 'B';
        }

        if (is_numeric($valor)) {
            $alinhamento = 'R';
            $valor = db_formatar($valor, 'f');
        }

        $linhasOcupadas = $this->pdf->NbLines($largura, $valor);

        if ($linhasOcupadas > 1) {
            $y = $this->pdf->GetY();
            $this->pdf->MultiCell($largura, static::ALTURA_LINHA, $valor, $borda, $alinhamento, $preenche);
            $x = 10 + $largura;
            $this->pdf->SetXY($x, $y);

            $this->alturaLinha = static::ALTURA_LINHA * $linhasOcupadas;
        } else {
            $this->pdf->Cell($largura, $this->alturaLinha, $valor, $borda, $quebra, $alinhamento, $preenche);
        }

        if ($quebra) {
            $this->alturaLinha = static::ALTURA_LINHA;
        }

        $this->pdf->setBold(0);

        return $this;
    }
}