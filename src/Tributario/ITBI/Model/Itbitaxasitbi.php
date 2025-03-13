<?php

namespace ECidade\Tributario\ITBI\Model;

class Itbitaxasitbi
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $itbi;

    /**
     * @var integer
     */
    private $taxasitbi;

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
     * Get the value of itbi
     *
     * @return  integer
     */
    public function getItbi()
    {
        return $this->itbi;
    }

    /**
     * Set the value of itbi
     *
     * @param  integer  $itbi
     *
     * @return  self
     */
    public function setItbi($itbi)
    {
        $this->itbi = $itbi;

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
}
