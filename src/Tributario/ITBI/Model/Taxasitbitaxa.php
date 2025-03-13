<?php

namespace ECidade\Tributario\ITBI\Model;

class Taxasitbitaxa
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $taxasitbi;

    /**
     * @var integer
     */
    private $taxaslancadas;

    /**
     * @var integer
     */
    private $calculasobre;

    /**
     * @var float
     */
    private $iniciofaixa;

    /**
     * @var float
     */
    private $fimfaixa;

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
     * Get the value of taxasitbi
     *
     * @return  integer
     */
    public function getTaxasitbi()
    {
        return $this->taxasitbi;
    }

    /**
     * Set the value of taxasitbi
     *
     * @param  integer  $taxasitbi
     *
     * @return  self
     */
    public function setTaxasitbi($taxasitbi)
    {
        $this->taxasitbi = $taxasitbi;

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
     * Get the value of iniciofaixa
     *
     * @return  float
     */
    public function getIniciofaixa()
    {
        return $this->iniciofaixa;
    }

    /**
     * Set the value of iniciofaixa
     *
     * @param  float  $iniciofaixa
     *
     * @return  self
     */
    public function setIniciofaixa($iniciofaixa)
    {
        $this->iniciofaixa = $iniciofaixa;

        return $this;
    }

    /**
     * Get the value of fimfaixa
     *
     * @return  float
     */
    public function getFimfaixa()
    {
        return $this->fimfaixa;
    }

    /**
     * Set the value of fimfaixa
     *
     * @param  float  $fimfaixa
     *
     * @return  self
     */
    public function setFimfaixa($fimfaixa)
    {
        $this->fimfaixa = $fimfaixa;

        return $this;
    }
}
