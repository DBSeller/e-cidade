<?php

namespace ECidade\Educacao\Escola\Mapper;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Model\AreaConhecimento;

class DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper
{
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;
    /**
     * @var DiarioAvaliacaoDisciplina[]
     */
    private $diarioAvaliacoesDisciplinas = [];
    /**
     * @var AvaliacaoPorAreaConhecimento[]
     */
    private $avaliacoes = [];

    /**
     * @var ResultadoPorAreaConhecimento
     */
    private $resultado;

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper
     */
    public function setAreaConhecimento($areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
        return $this;
    }

    /**
     * @return DiarioAvaliacaoDisciplina[]
     */
    public function getDiarioAvaliacoesDisciplinas()
    {
        return $this->diarioAvaliacoesDisciplinas;
    }

    /**
     * @param DiarioAvaliacaoDisciplina[] $diarioAvaliacoesDisciplinas
     * @return DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper
     */
    public function setDiarioAvaliacoesDisciplinas($diarioAvaliacoesDisciplinas)
    {
        $this->diarioAvaliacoesDisciplinas = $diarioAvaliacoesDisciplinas;
        return $this;
    }

    /**
     * @param $diarioAvaliacoes
     */
    public function addDiarioAvaliacoes($diarioAvaliacoes)
    {
        $this->diarioAvaliacoesDisciplinas[] = $diarioAvaliacoes;
    }

    /**
     * @return AvaliacaoPorAreaConhecimento[]
     */
    public function getAvaliacoes()
    {
        return $this->avaliacoes;
    }

    /**
     * @param AvaliacaoPorAreaConhecimento[] $avaliacaoArea
     */
    public function setAvaliacoes(array $avaliacaoArea)
    {
        $this->avaliacoes = $avaliacaoArea;
    }

    /**
     * @return ResultadoPorAreaConhecimento
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param ResultadoPorAreaConhecimento $resultado
     * @return DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
        return $this;
    }
}
