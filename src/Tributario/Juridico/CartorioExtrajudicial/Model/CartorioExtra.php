<?php

namespace ECidade\Tributario\Juridico\CartorioExtrajudicial\Model;

class CartorioExtra
{
    /**
     *@var integer
     */
    private $sequencial;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var integer
     */
    private $numcgm;

    /**
     * @var string
     */
    private $observacao;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return CartorioExtra
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return CartorioExtra
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumcgm()
    {
        return $this->numcgm;
    }

    /**
     * @param int $numcgm
     * @return CartorioExtra
     */
    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     * @return CartorioExtra
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }
}
