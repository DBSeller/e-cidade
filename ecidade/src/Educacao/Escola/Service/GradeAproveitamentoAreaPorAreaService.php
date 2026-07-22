<?php


namespace ECidade\Educacao\Escola\Service;

use ArredondamentoNota;
use DBEducacaoTermo;
use DiarioAvaliacaoDisciplina;
use DiarioClasse;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\AreaMapper;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\AvaliacaoAreaMapper;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\AvaliacaoDisciplinaMapper;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\GradeMapper;
use ECidade\Educacao\Escola\Mapper\GradeAproveitamento\ResultadoAreaMapper;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AvaliacaoAreaConhecimento;
use ECidade\Educacao\Escola\Model\DiarioArea;
use Exception;
use IElementoAvaliacao;
use ResultadoAvaliacao;
use Turma;

/**
 * Class GradeAproveitamentoPorAreaService
 * @package ECidade\Educacao\Escola\Service
 */
class GradeAproveitamentoAreaPorAreaService
{
    /**
     * @var DiarioClasse
     */
    private $diarioClasse;

    private $mapper;

    /**
     * termos do resulto final
     * @var array
     */
    private $termosResultado = [];
    /**
     * @var int
     */
    private $ano;
    /**
     * @var string
     */
    private $controleFrequencia;

    /**
     * GradeAproveitamentoAreaPorAreaService constructor.
     * @param DiarioClasse $diarioClasse
     */
    public function __construct(DiarioClasse $diarioClasse)
    {
        $this->diarioClasse = $diarioClasse;
        $this->ano = $this->diarioClasse->getTurma()->getCalendario()->getAnoExecucao();


        $matricula = $this->diarioClasse->getMatricula();
        $codigoEnsino = $matricula->getEtapaDeOrigem()->getEnsino()->getCodigo();
        $termoEncerramento = DBEducacaoTermo::getTermoEncerramento($codigoEnsino, 'A', $this->ano);
        $this->termosResultado['A'] = $termoEncerramento[0];
        $termoEncerramento = DBEducacaoTermo::getTermoEncerramento($codigoEnsino, 'R', $this->ano);
        $this->termosResultado['R'] = $termoEncerramento[0];

        $this->controleFrequencia = 'AD';
        if ($matricula->getTurma()->getFormaCalculoCargaHoraria() == Turma::CH_DIA_LETIVO) {
            $this->controleFrequencia = 'DL';
        }

        $this->mapper = new GradeMapper();
        $this->mapper->setControleFrequencia($this->controleFrequencia);
        $this->mapper->setMatricula($matricula);
    }

    /**
     * @return AreaProcedimento|null
     * @throws Exception
     */
    public function getProcedimento()
    {
        return $this->diarioClasse->getAreaProcedimento();
    }

    /**
     * @param DiarioArea $diarioArea
     * @return AreaMapper
     * @throws Exception
     */
    public function processarArea(DiarioArea $diarioArea)
    {
        $avaliacoesArea = $diarioArea->getAvaliacoes();
        $diarioAvaliacaoDisciplinas = $diarioArea->getDiarioAvaliacaoDisciplinas();

        $areaMapper = new AreaMapper();
        $areaMapper->setArea($diarioArea->getAreaConhecimento());
        $areaMapper->setDisciplinas($diarioAvaliacaoDisciplinas);

        foreach ($avaliacoesArea as $avaliacaoArea) {
            $areaProcedimentoAvaliacao = $avaliacaoArea->getAreaProcedimentoAvaliacao();

            $avaliacaoMapper = new AvaliacaoAreaMapper();
            $avaliacaoMapper->setPeriodoAvaliacao($areaProcedimentoAvaliacao->getPeriodoAvaliacao());
            $avaliacaoMapper->setAvaliacao($this->getAvaliacao($avaliacaoArea));
            $avaliacaoMapper->setAmparado($avaliacaoArea->isAmparado());
            $avaliacaoMapper->setAtingiuMinimo($this->atingiuMinimo($avaliacaoArea));
            $avaliacaoMapper->setOrdem($areaProcedimentoAvaliacao->getOrdem());

            $avaliacaoMapper->setDisciplinas(
                $this->processarDisciplinasArea($areaProcedimentoAvaliacao, $diarioAvaliacaoDisciplinas)
            );

            $areaMapper->addAvaliacoes($avaliacaoMapper);
        }

        $avaliacoes = $areaMapper->getAvaliacoes();
        usort($avaliacoes, function ($x, $y) {
            if ($x->getOrdem() == $y->getOrdem()) {
                return 0;
            }
                return ( ( $x->getOrdem() < $y->getOrdem() ) ? -1 : 1 );
        });

        $areaMapper->setAvaliacoes($avaliacoes);

        $resultadoMapper = new ResultadoAreaMapper();
        $resultadoMapper->setAreaResultado($diarioArea->getResultado()->getAreaProcedimentoResultado());
        $resultadoMapper->setAvaliacao($this->getAvaliacao($diarioArea->getResultado()));
        $resultadoMapper->setAtingiuMinimo($this->atingiuMinimo($diarioArea->getResultado()));
        $resultadoMapper->isAmparado($diarioArea->getResultado()->isAmparado());

        $resultadoAvaliacao = $diarioArea->getResultado()->getResultadoAvaliacao();
        $resultadoMapper->setResultadoAvaliacao($resultadoAvaliacao);
        $resultadoMapper->setResultadoFrequencia($diarioArea->getResultado()->getResultadoFrequencia());

        $resultadoMapper->setTermoResultado($this->getTermoAbreviadoEncerramento($resultadoAvaliacao));
        $areaMapper->setResultado($resultadoMapper);

        return $areaMapper;
    }

    /**
     * @throws Exception
     */
    private function processarGradeAproveitamento()
    {
        $diarioAlunoService = $this->diarioClasse->getDiarioAlunoService();

        $procedimento = $this->getProcedimento();
        $avaliacoes = $procedimento->getAvaliacoes();
        foreach ($avaliacoes as $avaliacao) {
            $avaliacao->getCodigo();
        }

        $diarioAluno = $diarioAlunoService->getDiarioAluno();
        $this->mapper->setDiarioAluno($diarioAluno);
        $diarioAreasConhecimento = $diarioAluno->getDiarioAreasConhecimento();

        foreach ($diarioAreasConhecimento as $diarioArea) {
            $this->mapper->addArea($this->processarArea($diarioArea));
        }

        return $this->mapper;
    }

    /**
     * @throws Exception
     */
    public function getGradeAproveitamento()
    {
        $this->diarioClasse->organizaDisciplinasPorAreaConhecimento();

        return $this->processarGradeAproveitamento();
    }

    /**
     * @param AvaliacaoAreaConhecimento $avaliacaoArea
     * @return string
     */
    private function getAvaliacao(AvaliacaoAreaConhecimento $avaliacaoArea)
    {
        switch ($avaliacaoArea->getElementoAvaliacao()->getFormaAvaliacao()->getTipo()) {
            case 'NIVEL':
                return $avaliacaoArea->getConceito();
            case 'PARECER':
                return 'PD';
            case 'NOTA':
                return ArredondamentoNota::formatar($avaliacaoArea->getNota(), $this->ano);
        }
    }

    private function atingiuMinimo(AvaliacaoAreaConhecimento $avaliacaoArea)
    {

        switch ($avaliacaoArea->getElementoAvaliacao()->getFormaAvaliacao()->getTipo()) {
            case 'NIVEL':
                /** @todo verificar */
                return true;
            case 'PARECER':
                return true;
            case 'NOTA':
                $menorValor = $avaliacaoArea->getElementoAvaliacao()->getFormaAvaliacao()->getMenorValor();
                return $avaliacaoArea->getNota() > $menorValor;
        }
    }

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @param DiarioAvaliacaoDisciplina[] $diarioAvaliacaoDisciplinas
     * @return AvaliacaoDisciplinaMapper[]
     */
    private function processarDisciplinasArea(
        AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao,
        array $diarioAvaliacaoDisciplinas
    ) {
        $disciplinas = [];

        foreach ($diarioAvaliacaoDisciplinas as $diarioAvaliacaoDisciplina) {
            $disciplinaMapper = new AvaliacaoDisciplinaMapper();
            $disciplinaMapper->setOrdem($areaProcedimentoAvaliacao->getOrdem());
            $disciplinaMapper->setRegencia($diarioAvaliacaoDisciplina->getRegencia());

            $ordemElemento = $areaProcedimentoAvaliacao->getOrdemElemento();
            $avaliacaoAproveitamentoDisciplina = $diarioAvaliacaoDisciplina->getAvaliacoesPorOrdem($ordemElemento);
            $elemento = $avaliacaoAproveitamentoDisciplina->getElementoAvaliacao();
            $disciplinaMapper->setFaltas($this->calculaFaltas($elemento, $diarioAvaliacaoDisciplina));
            $disciplinas[] = $disciplinaMapper;
        }

        return $disciplinas;
    }

    /**
     * @param IElementoAvaliacao $elemento
     * @param DiarioAvaliacaoDisciplina $diarioAvaliacaoDisciplina
     * @return int
     */
    private function calculaFaltas(IElementoAvaliacao $elemento, DiarioAvaliacaoDisciplina $diarioAvaliacaoDisciplina)
    {
        $faltas = 0;
        if ($elemento instanceof ResultadoAvaliacao) {
            $elementosCompoemResultado = $elemento->getElementosComposicaoResultado();

            foreach ($elementosCompoemResultado as $elementoComposicao) {
                $avaliacaoAproveitamentoDisciplina = $diarioAvaliacaoDisciplina->getAvaliacoesPorOrdem(
                    $elementoComposicao->getOrdem()
                );
                $faltas += $avaliacaoAproveitamentoDisciplina->getTotalFaltas();
            }
        } else {
            $faltas = $diarioAvaliacaoDisciplina->getTotalFaltasPorPeriodo($elemento->getPeriodoAvaliacao());
        }
        return $faltas;
    }

    /**
     * @param $resultadoAvaliacao
     * @return string
     * @throws Exception
     */
    public function getTermoAbreviadoEncerramento($resultadoAvaliacao)
    {
        if (!array_key_exists($resultadoAvaliacao, $this->termosResultado)) {
            throw new Exception("Não foi encontrado termo de encerramento para o resultado: {$resultadoAvaliacao}");
        }

        $termo = $this->termosResultado[$resultadoAvaliacao];
        return $termo->sAbreviatura;
    }

    /**
     * @param $resultadoAvaliacao
     * @return string
     * @throws Exception
     */
    public function getTermoEncerramento($resultadoAvaliacao)
    {
        if (!array_key_exists($resultadoAvaliacao, $this->termosResultado)) {
            throw new Exception("Não foi encontrado termo de encerramento para o resultado: {$resultadoAvaliacao}");
        }

        $termo = $this->termosResultado[$resultadoAvaliacao];
        return $termo->sAbreviatura;
    }

    /**
     * @return string
     */
    public function getControleFrequencia()
    {
        return $this->controleFrequencia;
    }
}
