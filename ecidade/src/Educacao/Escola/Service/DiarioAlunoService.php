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

namespace ECidade\Educacao\Escola\Service;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Mapper\DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper;
use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use ECidade\Educacao\Escola\Model\DiarioAluno;
use ECidade\Educacao\Escola\Model\DiarioAlunoResultadoFinal;
use ECidade\Educacao\Escola\Model\DiarioArea;
use ECidade\Educacao\Escola\Model\DiarioAreaAvaliacao;
use ECidade\Educacao\Escola\Model\DiarioAreaResultado;
use ECidade\Educacao\Escola\Repository\DiarioAlunoRepository;
use ECidade\Educacao\Escola\Repository\DiarioAlunoResultadoFinalRepository;
use ECidade\Educacao\Escola\Repository\DiarioAreaAvaliacaoRepository;
use ECidade\Educacao\Escola\Repository\DiarioAreaRepository;
use ECidade\Educacao\Escola\Repository\DiarioAreaResultadoRepository;
use Exception;
use Matricula;

class DiarioAlunoService
{
    /**
     * @var DiarioAluno
     */
    private $diarioAluno;
    /**
     * @var Matricula
     */
    private $matricula;

    /**
     * DiarioAlunoService constructor.
     * @param Matricula $matricula
     * @throws Exception
     */
    public function __construct(Matricula $matricula)
    {
        $this->matricula = $matricula;
        $this->buscaOuCriaDiario($matricula);
    }

    /**
     * @param Matricula $matricula
     * @throws Exception
     */
    private function buscaOuCriaDiario(Matricula $matricula)
    {
        $repository = new DiarioAlunoRepository();
        $diarioAluno = $repository->scopeAluno($matricula->getAluno())
            ->scopeEtapa($matricula->getEtapaDeOrigem())
            ->scopeTurma($matricula->getTurma())
            ->first();

        if (is_null($diarioAluno)) {
            $diarioAluno = new DiarioAluno();
            $diarioAluno->setAluno($matricula->getAluno());
            $diarioAluno->setTurma($matricula->getTurma());
            $diarioAluno->setEtapa($matricula->getEtapaDeOrigem());

            $diarioAluno = $repository->salvar($diarioAluno);
        }

        $this->diarioAluno = $diarioAluno;
        $this->getDiarioResultadoFinal();
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @param DiarioAvaliacaoDisciplina[] $diarioAvaliacoesDisciplinas
     * @throws Exception
     */
    public function salvar(AreaProcedimento $areaProcedimento, array $diarioAvaliacoesDisciplinas)
    {
        $calculo = new CalcularResultadoAreaService($areaProcedimento, $diarioAvaliacoesDisciplinas);
        $diarioAvaliacaoDisciplinasPorAreaConhecimento = $calculo->calcular();

        foreach ($diarioAvaliacaoDisciplinasPorAreaConhecimento as $avaliacaoAreaConhecimento) {
            $this->salvarAvaliacoes($avaliacaoAreaConhecimento);
            $this->salvarResultado($avaliacaoAreaConhecimento);
        }

        $this->salvarResultadoFinalArea($diarioAvaliacaoDisciplinasPorAreaConhecimento);
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return DiarioArea
     * @throws Exception
     */
    private function getDiarioArea(AreaConhecimento $areaConhecimento)
    {
        $diarioAreas = $this->diarioAluno->getDiarioAreasConhecimento();
        foreach ($diarioAreas as $diarioArea) {
            if ($diarioArea->getAreaConhecimento()->getCodigo() === $areaConhecimento->getCodigo()) {
                return $diarioArea;
            }
        }

        $diarioArea = $this->criarDiarioArea($areaConhecimento);
        $this->diarioAluno->addAreaConhecimento($diarioArea);
        return $diarioArea;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return DiarioArea
     * @throws Exception
     */
    private function criarDiarioArea(AreaConhecimento $areaConhecimento)
    {
        $repository = new DiarioAreaRepository();
        return $repository->findOrCreate($this->diarioAluno, $areaConhecimento);
    }

    /**
     * @param DiarioArea $diarioArea
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    private function getDiarioAvaliacao(DiarioArea $diarioArea, AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $avaliacoes = $diarioArea->getAvaliacoes();
        foreach ($avaliacoes as $diarioAreaAvaliacao) {
            $elemento = $diarioAreaAvaliacao->getAreaProcedimentoAvaliacao();
            if ($elemento->getCodigo() === $areaProcedimentoAvaliacao->getCodigo()) {
                return $diarioAreaAvaliacao;
            }
        }

        $diarioAreaAvaliacao = $this->criarDiarioAvaliacao($diarioArea, $areaProcedimentoAvaliacao);
        $diarioArea->addAvaliacao($diarioAreaAvaliacao);
        return $diarioAreaAvaliacao;
    }

    /**
     * @param DiarioArea $diarioArea
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    private function criarDiarioAvaliacao(DiarioArea $diarioArea, AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $repository = new DiarioAreaAvaliacaoRepository();
        return $repository->findOrCreate($diarioArea, $areaProcedimentoAvaliacao);
    }

    /**
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $avaliacaoAreaConhecimento
     * @throws Exception
     */
    private function salvarAvaliacoes(DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $avaliacaoAreaConhecimento)
    {
        $diarioArea = $this->getDiarioArea($avaliacaoAreaConhecimento->getAreaConhecimento());
        $avaliacoes = $avaliacaoAreaConhecimento->getAvaliacoes();

        foreach ($avaliacoes as $avaliacaoPorAreaConhecimento) {
            $procedimentoAvaliacao = $avaliacaoPorAreaConhecimento->getAreaProcedimentoAvaliacao();
            $diarioAvaliacao = $this->getDiarioAvaliacao($diarioArea, $procedimentoAvaliacao);
            $diarioAvaliacao->setAmparado($avaliacaoPorAreaConhecimento->isAmparado());

            switch ($procedimentoAvaliacao->getFormaAvaliacao()->getTipo()) {
                case 'NOTA':
                    $diarioAvaliacao->setNota($avaliacaoPorAreaConhecimento->getAvaliacao());
                    break;
                case 'NIVEL':
                    $diarioAvaliacao->setConceito($avaliacaoPorAreaConhecimento->getAvaliacao());
                    break;
                case 'PARECER':
                    $diarioAvaliacao->setParecer($avaliacaoPorAreaConhecimento->getAvaliacao());
                    break;
            }

            $repository = new DiarioAreaAvaliacaoRepository();
            $repository->salvar($diarioAvaliacao);
        }
    }

    /**
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $avaliacaoAreaConhecimento
     * @throws Exception
     */
    private function salvarResultado(DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $avaliacaoAreaConhecimento)
    {
        $diarioArea = $this->getDiarioArea($avaliacaoAreaConhecimento->getAreaConhecimento());

        $resultado = $avaliacaoAreaConhecimento->getResultado();
        $diarioResultado = $this->getDiarioAreaResultado($diarioArea, $resultado->getAreaProcedimentoResultado());
        $diarioResultado->setAmparado($resultado->isAmparado());
        $diarioResultado->setResultadoAvaliacao($resultado->getResultadoAvaliacao());
        $diarioResultado->setResultadoFrequencia($resultado->getResultadoFrequencia());

        $tipo = $resultado->getAreaProcedimentoResultado()->getFormaAvaliacao()->getTipo();
        switch ($tipo) {
            case 'NOTA':
                $diarioResultado->setNota($resultado->getAvaliacao());
                break;
            case 'NIVEL':
                $diarioResultado->setConceito($resultado->getAvaliacao());
                break;
            case 'PARECER':
                $diarioResultado->setParecer($resultado->getAvaliacao());
                break;
        }

        $repository = new DiarioAreaResultadoRepository();
        $repository->salvar($diarioResultado);
    }

    /**
     * @param DiarioArea $diarioArea
     * @param AreaProcedimentoResultado $procedimentoResultado
     * @return DiarioAreaResultado
     * @throws Exception
     */
    private function getDiarioAreaResultado(DiarioArea $diarioArea, AreaProcedimentoResultado $procedimentoResultado)
    {
        $diarioResultado = $diarioArea->getResultado();
        if (is_null($diarioResultado)) {
            $repository = new DiarioAreaResultadoRepository();
            $diarioResultado = $repository->findOrCreate($diarioArea, $procedimentoResultado);
            $diarioArea->setResultado($diarioResultado);
        }

        return $diarioResultado;
    }

    /**
     * @return DiarioAlunoResultadoFinal
     */
    private function getDiarioResultadoFinal()
    {
        $resultadoFinal = $this->diarioAluno->getResultadoFinal();
        if (is_null($resultadoFinal)) {
            $repository = new DiarioAlunoResultadoFinalRepository();
            $resultadoFinal = $repository->findOrCreate($this->diarioAluno);
        }

        $this->diarioAluno->setResultadoFinal($resultadoFinal);
        return $resultadoFinal;
    }

    /**
     * @return DiarioAluno
     */
    public function getDiarioAluno()
    {
        return $this->diarioAluno;
    }

    /**
     * @throws Exception
     */
    public function encerrar()
    {
        $this->diarioAluno->setEncerrado(true);
        return $this->salvarDiarioAluno($this->diarioAluno);
    }

    public function cancelarEncerramento()
    {
        $this->diarioAluno->setEncerrado(false);
        return $this->salvarDiarioAluno($this->diarioAluno);
    }

    public function salvarDiarioAluno(DiarioAluno $diarioAluno)
    {
        $repository = new DiarioAlunoRepository();
        $this->diarioAluno = $repository->salvar($diarioAluno);
        return $this->diarioAluno;
    }

    /**
     * @param string $resultado
     * @throws Exception
     */
    public function salvarResultadoFinal($resultado = '')
    {
        $resultadoFinal = $this->getDiarioResultadoFinal();
        $resultadoFinal->setResultadoFinal($resultado);

        $repository = new DiarioAlunoResultadoFinalRepository();
        $repository->salvar($resultadoFinal);
    }

    /**
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper[] $diarioAvaliacaoDisciplinasPorAreaConhecimento
     * @return string
     * @throws Exception
     */
    private function salvarResultadoFinalArea(array $diarioAvaliacaoDisciplinasPorAreaConhecimento)
    {
        $resultados = [];
        foreach ($diarioAvaliacaoDisciplinasPorAreaConhecimento as $avaliacaoAreaConhecimento) {
            $resultados[] = $avaliacaoAreaConhecimento->getResultado()->getResultadoAvaliacao();
            $resultados[] = $avaliacaoAreaConhecimento->getResultado()->getResultadoFrequencia();
        }

        $resultadoFinal = 'A';
        if (in_array('R', $resultados)) {
            $resultadoFinal = 'R';
        }
        if (in_array('', $resultados)) {
            $resultadoFinal = '';
        }

        $this->salvarResultadoFinal($resultadoFinal);
    }

    /**
     * @throws Exception
     */
    public function deletar()
    {
        $repository = new DiarioAlunoRepository();
        return $repository->excluir($this->diarioAluno);
    }
}
