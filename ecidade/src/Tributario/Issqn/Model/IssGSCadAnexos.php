<?php

namespace ECidade\Tributario\Issqn\Model;

class IssGSCadAnexos
{
    private $q157_sequencial;
    private $q157_codigo;
    private $q157_descricao;

    /**
     * Get the value of q157_sequencial
     */
    public function getSequencial()
    {
        return $this->q157_sequencial;
    }

    /**
     * Set the value of q157_sequencial
     *
     * @return  self
     */
    public function setSequencial($q157_sequencial)
    {
        $this->q157_sequencial = $q157_sequencial;

        return $this;
    }

    /**
     * Get the value of q157_codigo
     */
    public function getCodigo()
    {
        return $this->q157_codigo;
    }

    /**
     * Set the value of q157_codigo
     *
     * @return  self
     */
    public function setCodigo($q157_codigo)
    {
        $this->q157_codigo = $q157_codigo;

        return $this;
    }

    /**
     * Get the value of q157_descricao
     */
    public function getDescricao()
    {
        return $this->q157_descricao;
    }

    /**
     * Set the value of q157_descricao
     *
     * @return  self
     */
    public function setDescricao($q157_descricao)
    {
        $this->q157_descricao = $q157_descricao;

        return $this;
    }
}
