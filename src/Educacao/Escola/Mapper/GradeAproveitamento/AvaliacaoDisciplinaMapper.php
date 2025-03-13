<?php


namespace ECidade\Educacao\Escola\Mapper\GradeAproveitamento;

use Regencia;

/**
 * Class AvaliacaoDisciplinaMapper
 * @package ECidade\Educacao\Escola\Mapper\GradeAproveitamento
 */
class AvaliacaoDisciplinaMapper
{

    /**
     * @var integer
     */
    private $ordem;

    /**
     * @var integer
     */
    private $faltas;

    /**
     * @var Regencia
     */
    private $regencia;

    /**
     * @param $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
    }

    /**
     * @return integer
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @return mixed
     */
    public function getFaltas()
    {
        return $this->faltas;
    }

    /**
     * @param mixed $faltas
     */
    public function setFaltas($faltas)
    {
        $this->faltas = $faltas;
    }

    /**
     * @param Regencia $regencia
     */
    public function setRegencia(Regencia $regencia)
    {
        $this->regencia = $regencia;
    }

    /**
     * @return Regencia
     */
    public function getRegencia()
    {
        return $this->regencia;
    }
}
