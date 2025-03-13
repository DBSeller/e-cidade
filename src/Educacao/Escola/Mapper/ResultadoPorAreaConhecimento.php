<?php


namespace ECidade\Educacao\Escola\Mapper;

use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;

/**
 * Class ResultadoPorAreaConhecimento
 * @package ECidade\Educacao\Escola\Mapper
 */
class ResultadoPorAreaConhecimento
{
    /**
     * @var AreaProcedimentoResultado
     */
    protected $areaProcedimentoResultado;
    /**
     * @var mixed
     */
    private $avaliacao;
    /**
     * @var boolean
     */
    private $amparado = false;
    /**
     * @var string
     */
    private $resultadoAvaliacao = 'R';
    /**
     * @var string
     */
    private $resultadoFrequencia = 'R';

    /**
     * @return AreaProcedimentoResultado
     */
    public function getAreaProcedimentoResultado()
    {
        return $this->areaProcedimentoResultado;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return ResultadoPorAreaConhecimento
     */
    public function setAreaProcedimentoResultado($areaProcedimentoResultado)
    {
        $this->areaProcedimentoResultado = $areaProcedimentoResultado;
        return $this;
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
     * @return ResultadoPorAreaConhecimento
     */
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
        return $this;
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
     * @return ResultadoPorAreaConhecimento
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;

        if ($this->amparado) {
            $this->resultadoAvaliacao = 'A';
            $this->resultadoFrequencia = 'A';
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getResultadoAvaliacao()
    {
        return $this->resultadoAvaliacao;
    }

    /**
     * @param string $resultadoAvaliacao
     * @return ResultadoPorAreaConhecimento
     */
    public function setResultadoAvaliacao($resultadoAvaliacao)
    {
        $this->resultadoAvaliacao = $resultadoAvaliacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultadoFrequencia()
    {
        return $this->resultadoFrequencia;
    }

    /**
     * @param string $resultadoFrequencia
     * @return ResultadoPorAreaConhecimento
     */
    public function setResultadoFrequencia($resultadoFrequencia)
    {
        $this->resultadoFrequencia = $resultadoFrequencia;
        return $this;
    }
}
