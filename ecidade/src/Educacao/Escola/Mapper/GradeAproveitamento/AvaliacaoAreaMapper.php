<?php


namespace ECidade\Educacao\Escola\Mapper\GradeAproveitamento;

use PeriodoAvaliacao;

/**
 * Class AvaliacaoAreaMapper
 * @package ECidade\Educacao\Escola\Mapper\GradeAproveitamento
 */
class AvaliacaoAreaMapper
{
    /**
     * @var PeriodoAvaliacao
     */
    private $periodoAvaliacao;

    /**
     * @var mixed
     */
    private $avaliacao;

    /**
     * @var bool
     */
    private $amparado = false;
    /**
     * @var bool
     */
    private $atingiuMinimo = false;

    /**
     * @var integer
     */
    private $ordem;

    /**
     * @return PeriodoAvaliacao
     */
    public function getPeriodoAvaliacao()
    {
        return $this->periodoAvaliacao;
    }

    /**
     * @var AvaliacaoDisciplinaMapper[]
     */
    private $disciplinas = [];

    /**
     * @param PeriodoAvaliacao $periodoAvaliacao
     */
    public function setPeriodoAvaliacao(PeriodoAvaliacao $periodoAvaliacao)
    {
        $this->periodoAvaliacao = $periodoAvaliacao;
    }

    /**
     * @return mixed
     */
    public function getAvaliacao()
    {
        return $this->avaliacao;
    }

    /**
     * @param mixed $avaliacao
     * @return AvaliacaoAreaMapper
     */
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAmparado()
    {
        return $this->amparado;
    }

    /**
     * @param boolean $amparado
     * @return AvaliacaoAreaMapper
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;
        return $this;
    }

    /**
     * @param boolean $atingiuMinimo
     */
    public function setAtingiuMinimo($atingiuMinimo = false)
    {
        $this->atingiuMinimo = $atingiuMinimo;
    }

    /**
     * @return AvaliacaoDisciplinaMapper[]
     */
    public function getDisciplinas()
    {
        return $this->disciplinas;
    }

    /**
     * @param AvaliacaoDisciplinaMapper[] $disciplinas
     * @return AvaliacaoAreaMapper
     */
    public function setDisciplinas(array $disciplinas)
    {
        $this->disciplinas = $disciplinas;
        return $this;
    }

    /**
     * @param $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
    }

    /**
     * @return bool
     */
    public function isAtingiuMinimo()
    {
        return $this->atingiuMinimo;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }
}
