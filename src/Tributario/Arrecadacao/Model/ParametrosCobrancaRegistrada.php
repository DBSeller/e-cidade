<?php

namespace ECidade\Tributario\Arrecadacao\Model;

class ParametrosCobrancaRegistrada
{
    /**
     * @var integer
    */
    private $sequencial;

    /**
     * @var string
     */
    private $usuario;

    /**
     * @var string
     */
    private $clientid;

    /**
     * @var string
     */
    private $clientsecret;

    /**
     * @var string
    */
    private $codban;

    /**
     * @var string
    */
    private $chavej;

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
     * Get the value of usuario
     *
     * @return  string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @param  string  $usuario
     *
     * @return  self
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get the value of clientid
     *
     * @return  string
     */
    public function getClientid()
    {
        return $this->clientid;
    }

    /**
     * Set the value of clientid
     *
     * @param  string  $clientid
     *
     * @return  self
     */
    public function setClientid($clientid)
    {
        $this->clientid = $clientid;

        return $this;
    }

    /**
     * Get the value of clientsecret
     *
     * @return  string
     */
    public function getClientsecret()
    {
        return $this->clientsecret;
    }

    /**
     * Set the value of clientsecret
     *
     * @param  string  $clientsecret
     *
     * @return  self
     */
    public function setClientsecret($clientsecret)
    {
        $this->clientsecret = $clientsecret;

        return $this;
    }

    /**
     * Get the value of codban
     *
     * @return  string
     */
    public function getCodban()
    {
        return $this->codban;
    }

    /**
     * Set the value of codban
     *
     * @param  string  $codban
     *
     * @return  self
     */
    public function setCodban($codban)
    {
        $this->codban = $codban;

        return $this;
    }

    /**
     * Get the value of chavej
     *
     * @return  string
     */
    public function getChavej()
    {
        return $this->chavej;
    }

    /**
     * Set the value of chavej
     *
     * @param  string  $chavej
     *
     * @return  self
     */
    public function setChavej($chavej)
    {
        $this->chavej = $chavej;

        return $this;
    }
}
