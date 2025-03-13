<?php


namespace ECidade\Educacao\Escola\Relatorios;

use AmparoDisciplina;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\AreaMapper;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\GradeMapper;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Service\GradeAproveitamentoAreaPorAreaService;
use Exception;
use FPDF;
use Matricula;

class AreaGradeAproveitamentoRelatorio
{

    protected $colunaDisciplina = 30;
    protected $colunaFrequencia = 28;
    protected $colunaResultadoFinal = 20;
    protected $colunaResultado = 12; // se tiver até 2 assume este tamanho
    protected $colunaElementos = 0; // sempre calculado
    protected $colunaElemento = 0; //tamanho de cada elemento;

    protected $colunaNP = 9;  // se tiver configurado para apresentar nota parcial, assume este tamanho

    /**
     * Colunas dentro da coluna do elemento de avaliacao $colunaElemento
     */
    protected $colunaAvaliacao = 0;  // sempre calculado
    protected $colunaFalta = 8;

    /**
     * Colunas dentro da coluna de Frequencia;
     */
    protected $colunaPercentualFrequencia = 10;
    protected $colunaAD;
    protected $colunaTF;
    protected $colunaFA;

    // Colunas que compõe o Resultado Final
    protected $colunaAprov = 0;
    protected $colunaRF = 0;

    /**
     * @var FPDF
     */
    private $pdf;
    /**
     * @var Matricula
     */
    private $matricula;
    /**
     * @var integer
     */
    private $tamanhoLinha;
    /**
     * @var GradeAproveitamentoAreaPorAreaService
     */
    private $gradeService;
    /**
     * @var GradeMapper
     */
    private $mapper;
    /**
     * @var AreaProcedimento
     */
    private $procedimento;

    /**
     * AreaGradeAproveitamentoRelatorio constructor.
     * @param FPDF $pdf
     * @param Matricula $matricula
     * @param integer $tamanhoLinha
     * @throws Exception
     */
    public function __construct(FPDF $pdf, Matricula $matricula, $tamanhoLinha)
    {
        $this->pdf = $pdf;
        $this->pdf->SetFillColor(230);
        $this->matricula = $matricula;
        $this->tamanhoLinha = $tamanhoLinha;

        db_inicio_transacao();
        $diarioClasse = $matricula->getDiarioDeClasse();
        db_fim_transacao();
        $this->gradeService = new GradeAproveitamentoAreaPorAreaService($diarioClasse);
        $this->mapper = $this->gradeService->getGradeAproveitamento();

        $this->procedimento = $this->gradeService->getProcedimento();
        $this->calcularTamanhoColunas();
    }

    /**
     * @throws Exception
     */
    public function montarGrade()
    {
        $this->cabecalho();

        $areaMappers = $this->mapper->getAreas();

        $this->pdf->SetFont("Arial", "", 7);
        foreach ($areaMappers as $areaMapper) {
            $this->imprimirArea($areaMapper);
            $this->imprimirDisciplinasArea($areaMapper);
        }
    }

    /**
     * @throws Exception
     */
    private function cabecalho()
    {
        $procedimento = $this->procedimento;
        $avaliacoes = $procedimento->getAvaliacoes();

        $this->pdf->SetFont('arial', 'B', 7);

        // primeira linha
        $this->pdf->Cell($this->colunaDisciplina, 4, '', 1);
        foreach ($avaliacoes as $avaliacao) {
            $descricaoAbreviada = $avaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada();
            $this->pdf->Cell($this->colunaElemento, 4, $descricaoAbreviada, 1, 0, 'C');
        }
        $resultadoAbreviado = $procedimento->getResultado()->getTipoResultado()->getDescricaoAbreviada();
        if ($procedimento->getResultado()->getFormaObtencao()->getValue() !== "AP") {
            $this->pdf->Cell($this->colunaResultado, 4, $resultadoAbreviado, 1, 0, 'C');
        }
        $this->pdf->Cell($this->colunaFrequencia, 4, 'Frequência', 1, 0, 'C');
        $this->pdf->Cell($this->colunaResultadoFinal, 4, 'Resultado Final', 1, 1, 'C');

        // segunda linha
        $this->pdf->Cell($this->colunaDisciplina, 4, 'Disciplina', 1, 0, 'C');
        foreach ($avaliacoes as $avaliacao) {
            $this->pdf->Cell($this->colunaAvaliacao, 4, 'AVAL.', 1, 0, 'C');
            $this->pdf->Cell($this->colunaFalta, 4, 'FT', 1, 0, 'C');
        }
        if ($procedimento->getResultado()->getFormaObtencao()->getValue() !== "AP") {
            $this->pdf->Cell($this->colunaResultado, 4, 'AVAL.', 1, 0, 'C');
        }

        $this->pdf->Cell($this->colunaAD, 4, $this->gradeService->getControleFrequencia(), 1, 0, 'C');
        $this->pdf->Cell($this->colunaTF, 4, 'TF', 1, 0, 'C');
        $this->pdf->Cell($this->colunaFA, 4, 'FA', 1, 0, 'C');
        $this->pdf->Cell($this->colunaPercentualFrequencia, 4, 'Freq.', 1, 0, 'C');

        $this->pdf->Cell($this->colunaAprov, 4, 'Aprov', 1, 0, 'C');
        $this->pdf->Cell($this->colunaRF, 4, 'RF', 1, 1, 'C');
    }

    private function calcularTamanhoColunas()
    {
        $procedimento = $this->procedimento;
        $avaliacoes = $procedimento->getAvaliacoes();
        $numeroAvaliacoes = count($avaliacoes);
        if ($this->pdf->DefOrientation == 'P' && $numeroAvaliacoes === 3) {
            $this->colunaDisciplina = 40;
        }

        $this->colunaElementos = $this->tamanhoLinha;
        $this->colunaElementos -= $this->colunaDisciplina;
        $this->colunaElementos -= $this->colunaFrequencia;
        $this->colunaElementos -= $this->colunaResultadoFinal;
        if ($procedimento->getResultado()->getFormaObtencao()->getValue() !== "AP") {
            $this->colunaElementos -= $this->colunaResultado;
        }

        $this->colunaElemento = $this->colunaElementos / $numeroAvaliacoes;

        $this->colunaAvaliacao = $this->colunaElemento - $this->colunaFalta;

        $tamanho = ($this->colunaFrequencia - $this->colunaPercentualFrequencia) / 3;
        $this->colunaAD = $tamanho;
        $this->colunaTF = $tamanho;
        $this->colunaFA = $tamanho;

        $tamanho = $this->colunaResultadoFinal / 2;
        $this->colunaAprov = $tamanho;
        $this->colunaRF = $tamanho;
    }

    private function calculaAlturaLinha($descricao, $w)
    {
        $alturaLinha = 4;
        $linhas = $this->pdf->NbLines($this->colunaDisciplina, $descricao);
        $alturaLinha *= $linhas;
        return $alturaLinha;
    }

    private function imprimirArea(AreaMapper $areaMapper)
    {
        $area = $areaMapper->getAreaConhecimento();
        $descricaoArea = $area->getDescricao();
        $alturaLinha = $this->calculaAlturaLinha($descricaoArea, $this->colunaDisciplina);

        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($this->colunaDisciplina, 4, $descricaoArea, 1, 'L', 1);
        $this->pdf->SetY($y);
        $this->pdf->SetX($this->pdf->lMargin + $this->colunaDisciplina);
        $avaliacoes = $areaMapper->getAvaliacoes();

        foreach ($avaliacoes as $avaliacaoArea) {
            $avaliacao = $avaliacaoArea->getAvaliacao();
            if ($avaliacaoArea->isAmparado()) {
                $avaliacao = 'AMP';
            }
            if (!$avaliacaoArea->isAtingiuMinimo() || $avaliacaoArea->isAmparado()) {
                $this->pdf->SetFont('Arial', 'B', 7);
            }
            $this->pdf->Cell($this->colunaAvaliacao, $alturaLinha, $avaliacao, 1, 0, 'C', 1);
            $this->pdf->SetFont('Arial', '', 7);
            $this->pdf->Cell($this->colunaFalta, $alturaLinha, '', 1, 0, 'C', 1);
        }

        $avaliacao = $areaMapper->getResultado()->getAvaliacao();
        if ($areaMapper->getResultado()->isAmparado()) {
            $avaliacao = 'AMP';
        }
        if ($areaMapper->getResultado()->isAmparado() || !$areaMapper->getResultado()->isAtingiuMinimo()) {
            $this->pdf->SetFont('Arial', 'B', 7);
        }
        $isAprovacaoPeriodos = $areaMapper->getResultado()->getAreaResultado()->getFormaObtencao()->getValue();
        if ($isAprovacaoPeriodos != "AP") {
            $this->pdf->Cell($this->colunaResultado, $alturaLinha, $avaliacao, 1, 0, 'C', 1);
        }
        $this->pdf->SetFont('Arial', '', 7);

        $this->pdf->Cell($this->colunaAD, $alturaLinha, '', 1, 0, 'C', 1);
        $this->pdf->Cell($this->colunaTF, $alturaLinha, '', 1, 0, 'C', 1);
        $this->pdf->Cell($this->colunaFA, $alturaLinha, '', 1, 0, 'C', 1);
        $this->pdf->Cell($this->colunaPercentualFrequencia, $alturaLinha, '', 1, 0, 'C', 1);

        if ($this->mapper->getDiarioAluno()->isEncerrado()) {
            if ($isAprovacaoPeriodos != "AP") {
                $this->pdf->Cell($this->colunaAprov, $alturaLinha, $avaliacao, 1, 0, 'C', 1);
            } else {
                $this->pdf->Cell($this->colunaAprov, $alturaLinha, '-', 1, 0, 'C', 1);
            }
            $termoResultadoFinal = $areaMapper->getResultado()->getTermoResultadoFinal();
            $this->pdf->Cell($this->colunaRF, $alturaLinha, $termoResultadoFinal, 1, 1, 'C', 1);
        } else {
            $this->pdf->Cell($this->colunaAprov, $alturaLinha, '', 1, 0, 'C', 1);
            $this->pdf->Cell($this->colunaRF, $alturaLinha, '', 1, 1, 'C', 1);
        }
    }

    private function imprimirDisciplinasArea(AreaMapper $areaMapper)
    {
        $this->pdf->SetFont('Arial', '', 7);
        foreach ($areaMapper->getDiarioAvaliacaoDisciplinas() as $diarioAvaliacaoDisciplina) {
            $regencia = $diarioAvaliacaoDisciplina->getRegencia();
            $descricaoDisciplina = $regencia->getDisciplina()->getNomeDisciplina();
            $alturaLinha = $this->calculaAlturaLinha($descricaoDisciplina, $this->colunaDisciplina);
            $y = $this->pdf->GetY();
            $this->pdf->MultiCell($this->colunaDisciplina, 4, $descricaoDisciplina, 1);
            $this->pdf->SetY($y);
            $this->pdf->SetX($this->pdf->lMargin + $this->colunaDisciplina);

            foreach ($areaMapper->getAvaliacoes() as $avaliacao) {
                foreach ($avaliacao->getDisciplinas() as $disciplinaMapper) {
                    if ($regencia->getCodigo() === $disciplinaMapper->getRegencia()->getCodigo()) {
                        $this->pdf->Cell($this->colunaAvaliacao, $alturaLinha, '', 1, 0, 'C');
                        $this->pdf->Cell($this->colunaFalta, $alturaLinha, $disciplinaMapper->getFaltas(), 1, 0, 'C');
                    }
                }
            }
            if ($areaMapper->getResultado()->getAreaResultado()->getFormaObtencao()->getValue() != "AP") {
                $this->pdf->Cell($this->colunaResultado, $alturaLinha, '', 1, 0, 'C');
            }
            $this->pdf->Cell($this->colunaAD, $alturaLinha, $regencia->getTotalDeAulas(), 1, 0, 'C');
            $this->pdf->Cell($this->colunaTF, $alturaLinha, $diarioAvaliacaoDisciplina->getTotalFaltas(), 1, 0, 'C');
            $totalFaltasAbonadas = $diarioAvaliacaoDisciplina->getTotalFaltasAbonadas();
            $this->pdf->Cell($this->colunaFA, $alturaLinha, $totalFaltasAbonadas, 1, 0, 'C');
            $percentualFrequencia = $diarioAvaliacaoDisciplina->calcularPercentualFrequencia();
            $this->pdf->Cell($this->colunaPercentualFrequencia, $alturaLinha, "{$percentualFrequencia}%", 1, 0, 'C');
            $this->pdf->Cell($this->colunaAprov, $alturaLinha, '', 1, 0, 'C');
            $this->pdf->Cell($this->colunaRF, $alturaLinha, '', 1, 1, 'C');
        }
    }

    public function imprimirMinimoParaAprovacao()
    {
        $formaAvaliacao = $this->procedimento->getResultado()->getFormaAvaliacao();
        $this->pdf->SetFont("Arial", "B", 8);
        $texto = "Mínimo para Aprovação Anual: ";
        $texto .= $formaAvaliacao->getAproveitamentoMinino();
        $this->pdf->Cell($this->tamanhoLinha, 4, $texto, 1, 1, "L");
    }

    /**
     * @throws Exception
     */
    public function imprimirNiveis()
    {
        $formaAvaliacao = $this->procedimento->getResultado()->getFormaAvaliacao();
        if ($formaAvaliacao->getTipo() === 'NIVEL') {
            $conceitos = $formaAvaliacao->getConceitos();
            $listaDescricao = array();

            foreach ($conceitos as $conceito) {
                $listaDescricao[] = $conceito->iOrdem . '-' . $conceito->sConceito . ':' . $conceito->sDescricao;
            }

            if (!empty($listaDescricao)) {
                $this->pdf->SetFont('arial', 'b', 8);
                $this->pdf->Cell($this->tamanhoLinha, 4, "Níveis:", 1, 1, "L", 1);
                $this->pdf->SetFont('arial', '', 7);
                $this->pdf->MultiCell($this->tamanhoLinha, 4, implode(' | ', $listaDescricao), "RL", "L", 0, 0);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function imprimeResultadoFinal()
    {
        if ($this->mapper->getDiarioAluno()->isEncerrado()) {
            $resultadoFinal = $this->gradeService->getTermoEncerramento(
                $this->mapper->getDiarioAluno()->getResultadoFinal()->getResultadoFinal()
            );

            $this->pdf->SetFont("Arial", "B", 8);
            $this->pdf->Cell($this->tamanhoLinha, 4, "Resultado Final: {$resultadoFinal}", 1, 1, "L");
        }
    }

    public function getAmparosPorConvencao()
    {
        $convencoes = [];
        $diarioAvaliacaoDisciplinas = $this->matricula->getDiarioDeClasse()->getDisciplinas();
        foreach ($diarioAvaliacaoDisciplinas as $diarioAvaliacaoDisciplina) {
            $amparo = $diarioAvaliacaoDisciplina->getAmparo();

            if ($amparo->getTipoAmparo() == AmparoDisciplina::AMPARO_CONVENCAO) {
                $convencao = $amparo->getConvencao();
                $aConvencoes[$convencao->getCodigo()] = "{$convencao->getAbreviatura()} - {$convencao->getDescricao()}";
            }
        }

        return $convencoes;
    }

    public function getElementosApresentados()
    {
        $elementos = [];
        $avaliacoes = $this->procedimento->getAvaliacoes();
        foreach ($avaliacoes as $avaliacao) {
            $elementos[] = sprintf(
                '%s - %s',
                $avaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada(),
                $avaliacao->getPeriodoAvaliacao()->getDescricao()
            );
        }
        $elementos[] = sprintf(
            '%s - %s',
            $this->procedimento->getResultado()->getTipoResultado()->getDescricaoAbreviada(),
            $this->procedimento->getResultado()->getTipoResultado()->getDescricao()
        );

        return $elementos;
    }
}
