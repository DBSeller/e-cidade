<?php

namespace ECidade\Tributario\Arrecadacao\Model;

class TaxasLancadasDinamicos
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
    private $codcam;

    /**
     * @var boolean
     */
    private $obrigatorio;

    /**
     * @var integer
     */
    private $tipocampo;

    /**
     * @var string
     */
    private $valordefault;


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
     * Get the value of codcam
     *
     * @return  integer
     */
    public function getCodcam()
    {
        return $this->codcam;
    }

    /**
     * Set the value of codcam
     *
     * @param  integer  $codcam
     *
     * @return  self
     */
    public function setCodcam($codcam)
    {
        $this->codcam = $codcam;

        return $this;
    }

    /**
     * Get the value of obrigatorio
     *
     * @return  boolean
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * Set the value of obrigatorio
     *
     * @param  boolean  $obrigatorio
     *
     * @return  self
     */
    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;

        return $this;
    }

    /**
     * Get the value of tipocampo
     *
     * @return  integer
     */
    public function getTipocampo()
    {
        return $this->tipocampo;
    }

    /**
     * Set the value of tipocampo
     *
     * @param  integer  $tipocampo
     *
     * @return  self
     */
    public function setTipocampo($tipocampo)
    {
        $this->tipocampo = $tipocampo;

        return $this;
    }

    /**
     * Get the value of valordefault
     *
     * @return  string
     */
    public function getValordefault()
    {
        return $this->valordefault;
    }

    /**
     * Set the value of valordefault
     *
     * @param  string  $valordefault
     *
     * @return  self
     */
    public function setValordefault($valordefault)
    {
        $this->valordefault = $valordefault;

        return $this;
    }
}
