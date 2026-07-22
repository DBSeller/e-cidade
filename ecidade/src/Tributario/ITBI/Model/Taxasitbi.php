<?php

namespace ECidade\Tributario\ITBI\Model;

class Taxasitbi
{
    /**
     * @var integer
     */
    private $sequencial;
    
    /**
     * @var string
     */
    private $descricao;

    /**
     * @var boolean
     */
    private $imovelurbano;

    /**
     * @var boolean
     */
    private $imovelrural;

    /**
     * @var boolean
     */
    private $imovelurbanopleno;

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
     * Get the value of descricao
     *
     * @return  string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     *
     * @param  string  $descricao
     *
     * @return  self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of imovelurbano
     *
     * @return  boolean
     */
    public function getImovelurbano()
    {
        return $this->imovelurbano;
    }

    /**
     * Set the value of imovelurbano
     *
     * @param  boolean  $imovelurbano
     *
     * @return  self
     */
    public function setImovelurbano($imovelurbano)
    {
        $this->imovelurbano = $imovelurbano;

        return $this;
    }

    /**
     * Get the value of imovelrural
     *
     * @return  boolean
     */
    public function getImovelrural()
    {
        return $this->imovelrural;
    }

    /**
     * Set the value of imovelrural
     *
     * @param  boolean  $imovelrural
     *
     * @return  self
     */
    public function setImovelrural($imovelrural)
    {
        $this->imovelrural = $imovelrural;

        return $this;
    }

    /**
     * Get the value of imovelurbanopleno
     *
     * @return  boolean
     */
    public function getImovelurbanopleno()
    {
        return $this->imovelurbanopleno;
    }

    /**
     * Set the value of imovelurbanopleno
     *
     * @param  boolean  $imovelurbanopleno
     *
     * @return  self
     */
    public function setImovelurbanopleno($imovelurbanopleno)
    {
        $this->imovelurbanopleno = $imovelurbanopleno;

        return $this;
    }
}
