<?php


namespace ECidade\Educacao\Escola\Mapper\GradeAproveitamento;

use ECidade\Educacao\Escola\Model\DiarioAluno;
use Matricula;

class GradeMapper
{
    /**
     * @var AreaMapper[]
     */
    private $areas = [];

    /**
     * @var string
     */
    private $controleFrequencia;
    /**
     * @var Matricula
     */
    private $matricula;
    /**
     * @var DiarioAluno
     */
    private $diarioAluno;

    /**
     * @param AreaMapper $areaMapper
     */
    public function addArea(AreaMapper $areaMapper)
    {
        $this->areas[] = $areaMapper;
    }

    /**
     * @param string $controleFrequencia
     */
    public function setControleFrequencia($controleFrequencia = 'AD')
    {
        $this->controleFrequencia = $controleFrequencia;
    }

    /**
     * @param Matricula $matricula
     */
    public function setMatricula(Matricula $matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return AreaMapper[]
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @return string
     */
    public function getControleFrequencia()
    {
        return $this->controleFrequencia;
    }

    /**
     * @return Matricula
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param DiarioAluno $diarioAluno
     */
    public function setDiarioAluno(DiarioAluno $diarioAluno)
    {
        $this->diarioAluno = $diarioAluno;
    }

    /**
     * @return DiarioAluno
     */
    public function getDiarioAluno()
    {
        return $this->diarioAluno;
    }
}
