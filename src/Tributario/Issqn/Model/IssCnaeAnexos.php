<?php

namespace ECidade\Tributario\Issqn\Model;

class IssCnaeAnexos
{
    private $q178_sequencial;
    private $q178_cnae;
    private $q178_issgscadanexos;
    private $q178_data_fim;

    /**
     * Get the value of q178_sequencial
     */
    public function getSequencial()
    {
        return $this->q178_sequencial;
    }

    /**
     * Set the value of q178_sequencial
     *
     * @return  self
     */
    public function setSequencial($q178_sequencial)
    {
        $this->q178_sequencial = $q178_sequencial;

        return $this;
    }

    /**
     * Get the value of q178_cnae
     */
    public function getCnae()
    {
        return $this->q178_cnae;
    }

    /**
     * Set the value of q178_cnae
     *
     * @return  self
     */
    public function setCnae($q178_cnae)
    {
        $this->q178_cnae = $q178_cnae;

        return $this;
    }

    /**
     * Get the value of q178_issgscadanexos
     */
    public function getIssgscadanexos()
    {
        return $this->q178_issgscadanexos;
    }

    /**
     * Set the value of q178_issgscadanexos
     *
     * @return  self
     */
    public function setIssgscadanexos($q178_issgscadanexos)
    {
        $this->q178_issgscadanexos = $q178_issgscadanexos;

        return $this;
    }

    /**
     * Get the value of q178_data_fim
     */
    public function getDataFim()
    {
        return $this->q178_data_fim;
    }

    /**
     * Set the value of q178_data_fim
     *
     * @return  self
     */
    public function setDataFim($q178_data_fim)
    {
        $this->q178_data_fim = $q178_data_fim;

        return $this;
    }
}
