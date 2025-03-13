<?php

namespace ECidade\Tributario\Issqn\Model;

class FormaLocAlvara
{
    private $q167_sequencial;
    private $q167_descricao;
    private $q167_data_validade;

    /**
     * @return integer
     */
    public function getSequencial()
    {
        return $this->q167_sequencial;
    }

    /**
     * @param integer
     * @return void
     */
    public function setSequencial($q167_sequencial)
    {
        $this->q167_sequencial = $q167_sequencial;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->q167_descricao;
    }

    /**
     * @param string
     * @return void
     */
    public function setDescricao($q167_descricao)
    {
        $this->q167_descricao = $q167_descricao;
    }

    /**
     * @param string
     */
    public function getDataValidade()
    {
        return $this->q167_data_validade;
    }

    /**
     * @param string
     * @return void
     */
    public function setDataValidade($q167_data_validade)
    {
        $this->q167_data_validade = $q167_data_validade;
    }
}
