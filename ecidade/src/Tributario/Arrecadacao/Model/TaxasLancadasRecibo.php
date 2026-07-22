<?php

namespace ECidade\Tributario\Arrecadacao\Model;

class TaxasLancadasRecibo
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $taxaslancadas;

    /**
     * @var integer
     */
    private $numnov;

    /**
     * @var integer
     */
    private $tipoemissao;

    /**
     * @var integer
     */
    private $departamento = null;

    /**
     * @return integer
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param integer $sequencial
     *
     * @return self
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTaxaslancadas()
    {
        return $this->taxaslancadas;
    }

    /**
     * @param integer $taxaslancadas
     *
     * @return self
     */
    public function setTaxaslancadas($taxaslancadas)
    {
        $this->taxaslancadas = $taxaslancadas;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNumnov()
    {
        return $this->numnov;
    }

    /**
     * @param integer $numnov
     *
     * @return self
     */
    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTipoemissao()
    {
        return $this->tipoemissao;
    }

    /**
     * @param integer $tipoemissao
     *
     * @return self
     */
    public function setTipoemissao($tipoemissao)
    {
        $this->tipoemissao = $tipoemissao;

        return $this;
    }

    /**
     * @return  integer
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param  integer  $departamento
     *
     * @return  self
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }
}
