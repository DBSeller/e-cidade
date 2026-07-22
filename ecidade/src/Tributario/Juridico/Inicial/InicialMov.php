<?php

namespace ECidade\Tributario\Juridico\Inicial;

use DateTime;

class InicialMov
{
    const SITUACAO_CANCELADA = 9;

    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $inicial;

    /**
     * @var integer
     */
    private $situacao;

    /**
     * @var string
     */
    private $observacao;

    /**
     * @var DateTime
     */
    private $data;

    /**
     * @var integer
     */
    private $login;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @return int
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @return String
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @return DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    //SET

    /**
     * @param int
     * @return InicialMov
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @param int
     * @return InicialMov
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
        return $this;
    }

    /**
     * @param int
     * @return InicialMov
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @param String
     * @return InicialMov
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    /**
     * @param DateTime
     * @return InicialMov
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int
     * @return InicialMov
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param  $state
     * @return Diversos
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v56_codmov', $state)) {
            $self->setCodigo($state['v56_codmov']);
        }

        if (array_key_exists('v56_inicial', $state)) {
            $self->setInicial($state['v56_inicial']);
        }

        if (array_key_exists('v56_codsit', $state)) {
            $self->setSituacao($state['v56_codsit']);
        }

        if (array_key_exists('v56_obs', $state)) {
            $self->setObservacao($state['v56_obs']);
        }

        if (array_key_exists('v56_data', $state)) {
            $data = new DateTime($state['v56_data']);
            $self->setData($data);
        }

        if (array_key_exists('v56_id_login', $state)) {
            $self->setLogin($state['v56_id_login']);
        }

        return $self;
    }
}
