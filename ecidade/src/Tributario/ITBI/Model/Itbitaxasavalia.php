<?php

namespace ECidade\Tributario\ITBI\Model;

class Itbitaxasavalia
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $guia;

    /**
     * @var integer
     */
    private $taxaslancadas;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var integer
     */
    private $calculasobre;

    /**
     * @var float
     */
    private $aliquota;

    /**
     * Get the value of sequencial
     *
     * @return  integer
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  integer  $sequencial
     *
     * @return  self
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;

        return $this;
    }

    /**
     * Get the value of guia
     *
     * @return  integer
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * Set the value of guia
     *
     * @param  integer  $guia
     *
     * @return  self
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;

        return $this;
    }

    /**
     * Get the value of taxaslancadas
     *
     * @return  integer
     */
    public function getTaxaslancadas()
    {
        return $this->taxaslancadas;
    }

    /**
     * Set the value of taxaslancadas
     *
     * @param  integer  $taxaslancadas
     *
     * @return  self
     */
    public function setTaxaslancadas($taxaslancadas)
    {
        $this->taxaslancadas = $taxaslancadas;

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
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of calculasobre
     *
     * @return  integer
     */
    public function getCalculasobre()
    {
        return $this->calculasobre;
    }

    /**
     * Set the value of calculasobre
     *
     * @param  integer  $calculasobre
     *
     * @return  self
     */
    public function setCalculasobre($calculasobre)
    {
        $this->calculasobre = $calculasobre;

        return $this;
    }

    /**
     * Get the value of aliquota
     *
     * @return  float
     */
    public function getAliquota()
    {
        return $this->aliquota;
    }

    /**
     * Set the value of aliquota
     *
     * @param  float  $aliquota
     *
     * @return  self
     */
    public function setAliquota($aliquota)
    {
        $this->aliquota = $aliquota;

        return $this;
    }
}
