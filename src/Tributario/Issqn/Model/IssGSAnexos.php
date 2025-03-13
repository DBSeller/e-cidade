<?php

namespace ECidade\Tributario\Issqn\Model;

class IssGSAnexos
{
    private $q162_sequencial;
    private $q162_issgruposervico;
    private $q162_issgscadanexos;
    private $q162_data_fim;

    /**
     * Get the value of q162_sequencial
     */
    public function getSequencial()
    {
        return $this->q162_sequencial;
    }

    /**
     * Set the value of q162_sequencial
     *
     * @return  self
     */
    public function setSequencial($q162_sequencial)
    {
        $this->q162_sequencial = $q162_sequencial;

        return $this;
    }

    /**
     * Get the value of q162_issgruposervico
     */
    public function getIssgruposervico()
    {
        return $this->q162_issgruposervico;
    }

    /**
     * Set the value of q162_issgruposervico
     *
     * @return  self
     */
    public function setIssgruposervico($q162_issgruposervico)
    {
        $this->q162_issgruposervico = $q162_issgruposervico;

        return $this;
    }

    /**
     * Get the value of q162_issgscadanexos
     */
    public function getIssgscadanexos()
    {
        return $this->q162_issgscadanexos;
    }

    /**
     * Set the value of q162_issgscadanexos
     *
     * @return  self
     */
    public function setIssgscadanexos($q162_issgscadanexos)
    {
        $this->q162_issgscadanexos = $q162_issgscadanexos;

        return $this;
    }

    /**
     * Get the value of q162_data_fim
     */
    public function getDataFim()
    {
        return $this->q162_data_fim;
    }

    /**
     * Set the value of q162_data_fim
     *
     * @return  self
     */
    public function setDataFim($q162_data_fim)
    {
        $this->q162_data_fim = $q162_data_fim;

        return $this;
    }
}
