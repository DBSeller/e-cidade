<?php

namespace ECidade\Tributario\Cadastro\Model;

class SetorFiscal
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $descr;

    /**
     * @var float
     */
    private $valor;

    /**
     * Get the value of codigo
     *
     * @return  integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @param  integer  $codigo
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get the value of descr
     *
     * @return  string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set the value of descr
     *
     * @param  string  $descr
     *
     * @return  self
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get the value of valor
     *
     * @return  float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @param  float  $valor
     *
     * @return  self
     */
    public function setValor(float $valor)
    {
        $this->valor = $valor;

        return $this;
    }
}
