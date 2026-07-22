<?php

namespace ECidade\Tributario\Issqn\Model;

class ConfIssqnRetidoEmpresaPublica
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $receit;

    /**
     * @var integer
     */
    private $hist;

    /**
     * @var integer
     */
    private $tipo;

    /**
     * @var integer
     */
    private $anousu;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return ConfIssqnRetidoEmpresaPublica
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getReceit()
    {
        return $this->receit;
    }

    /**
     * @param int $receit
     * @return ConfIssqnRetidoEmpresaPublica
     */
    public function setReceit($receit)
    {
        $this->receit = $receit;
        return $this;
    }

    /**
     * @param int $hist
     * @return ConfIssqnRetidoEmpresaPublica
     */
    public function setHist($hist)
    {
        $this->hist = $hist;
        return $this;
    }
    /**
     * @return int
     */
    public function getHist()
    {
        return $this->hist;
    }
    
    /**
     * @param int $tipo
     * @return ConfIssqnRetidoEmpresaPublica
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @return int
     */
    public function getAnousu()
    {
        return $this->anousu;
    }

    /**
     * @param int $anousu
     * @return ConfIssqnRetidoEmpresaPublica
     */
    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
        return $this;
    }
}
