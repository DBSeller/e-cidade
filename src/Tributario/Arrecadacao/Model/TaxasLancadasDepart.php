<?php

namespace ECidade\Tributario\Arrecadacao\Model;

class TaxasLancadasDepart
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
    private $departamento;

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
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param integer $departamento
     *
     * @return self
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }
}
