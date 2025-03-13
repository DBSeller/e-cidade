<?php


namespace ECidade\Educacao\Escola\Mapper\GradeAproveitamento;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Model\AreaConhecimento;

/**
 * Class AreaMapper
 * @package ECidade\Educacao\Escola\Mapper\GradeAproveitamento
 */
class AreaMapper
{
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;

    /**
     * @var AvaliacaoAreaMapper[]
     */
    private $avaliacoes = [];

    /**
     * @var ResultadoAreaMapper
     */
    private $resultado;

    /**
     * @var DiarioAvaliacaoDisciplina[]
     */
    private $diarioAvaliacaoDisciplinas = [];

    public function setArea(AreaConhecimento $areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
    }

    /**
     * @param DiarioAvaliacaoDisciplina[] $diarioAvaliacaoDisciplinas
     */
    public function setDisciplinas(array $diarioAvaliacaoDisciplinas)
    {
        $this->diarioAvaliacaoDisciplinas = $diarioAvaliacaoDisciplinas;
    }

    /**
     * @param AvaliacaoAreaMapper $avaliacaoMapper
     */
    public function addAvaliacoes(AvaliacaoAreaMapper $avaliacaoMapper)
    {
        $this->avaliacoes[] = $avaliacaoMapper;
    }

    /**
     * @return ResultadoAreaMapper
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param ResultadoAreaMapper $resultado
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @return AvaliacaoAreaMapper[]
     */
    public function getAvaliacoes()
    {
        return $this->avaliacoes;
    }

    /**
     * @return DiarioAvaliacaoDisciplina[]
     */
    public function getDiarioAvaliacaoDisciplinas()
    {
        return $this->diarioAvaliacaoDisciplinas;
    }

    /**
     * @param AvaliacaoAreaMapper[] $avaliacoes
     */
    public function setAvaliacoes($avaliacoes)
    {
        $this->avaliacoes = $avaliacoes;
    }
}
