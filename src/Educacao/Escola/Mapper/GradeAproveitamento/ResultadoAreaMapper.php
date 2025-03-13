<?php


namespace ECidade\Educacao\Escola\Mapper\GradeAproveitamento;

use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;

/**
 * Class ResultadoAreaMapper
 * @package ECidade\Educacao\Escola\Mapper\GradeAproveitamento
 */
class ResultadoAreaMapper
{
    /**
     * @var AreaProcedimentoResultado
     */
    private $areaResultado;
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
     * @var string
     */
    private $termoResultadoFinal;
    /**
     * @var string
     */
    private $resultadoAvaliacao;
    /**
     * @var string
     */
    private $resultadoFrequencia;

    /**
     * @return AreaProcedimentoResultado
     */
    public function getAreaResultado()
    {
        return $this->areaResultado;
    }

    /**
     * @param AreaProcedimentoResultado $resultado
     */
    public function setAreaResultado($resultado)
    {
        $this->areaResultado = $resultado;
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
     */
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    /**
     * @return bool
     */
    public function isAmparado()
    {
        return $this->amparado;
    }

    /**
     * @param bool $amparado
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;
    }

    /**
     * @return bool
     */
    public function isAtingiuMinimo()
    {
        return $this->atingiuMinimo;
    }

    /**
     * @param bool $atingiuMinimo
     */
    public function setAtingiuMinimo($atingiuMinimo)
    {
        $this->atingiuMinimo = $atingiuMinimo;
    }

    /**
     * @param $termoEncerramento
     */
    public function setTermoResultado($termoEncerramento)
    {
        $this->termoResultadoFinal = $termoEncerramento;
    }

    /**
     * @param $resultadoAvaliacao
     */
    public function setResultadoAvaliacao($resultadoAvaliacao)
    {
        $this->resultadoAvaliacao = $resultadoAvaliacao;
    }

    /**
     * @param $resultadoFrequencia
     */
    public function setResultadoFrequencia($resultadoFrequencia)
    {
        $this->resultadoFrequencia = $resultadoFrequencia;
    }

    /**
     * @return string
     */
    public function getTermoResultadoFinal()
    {
        return $this->termoResultadoFinal;
    }

    /**
     * @return string
     */
    public function getResultadoAvaliacao()
    {
        return $this->resultadoAvaliacao;
    }

    /**
     * @return string
     */
    public function getResultadoFrequencia()
    {
        return $this->resultadoFrequencia;
    }
}
