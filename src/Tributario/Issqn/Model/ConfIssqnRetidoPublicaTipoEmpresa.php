<?php

namespace ECidade\Tributario\Issqn\Model;

class ConfIssqnRetidoPublicaTipoEmpresa
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $confissqnretidopublica;

    /**
     * @var integer
     */
    private $tipoempresa;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return ConfIssqnRetidoPublicaTipoEmpresa
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getConfissqnretidopublica()
    {
        return $this->confissqnretidopublica;
    }

    /**
     * @param int $confissqnretidopublica
     * @return ConfIssqnRetidoPublicaTipoEmpresa
     */
    public function setConfissqnretidopublica($confissqnretidopublica)
    {
        $this->confissqnretidopublica = $confissqnretidopublica;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoempresa()
    {
        return $this->tipoempresa;
    }

    /**
     * @param int $tipoempresa
     * @return ConfIssqnRetidoPublicaTipoEmpresa
     */
    public function setTipoempresa($tipoempresa)
    {
        $this->tipoempresa = $tipoempresa;
        return $this;
    }
}
