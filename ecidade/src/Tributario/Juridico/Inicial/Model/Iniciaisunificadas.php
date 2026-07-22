<?php

namespace ECidade\Tributario\Juridico\Inicial\Model;

class Iniciaisunificadas
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $inicialprimaria;

    /**
     * @var integer
     */
    private $inicialsecundaria;

    /**
     * @var integer
     */
    private $certidao;

    /**
     * @var string
     */
    private $dataunificacao;

    /**
     * @var integer
     */
    private $usuario;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Iniciaisunificadas
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getInicialprimaria()
    {
        return $this->inicialprimaria;
    }

    /**
     * @param int $inicialprimaria
     * @return Iniciaisunificadas
     */
    public function setInicialprimaria($inicialprimaria)
    {
        $this->inicialprimaria = $inicialprimaria;
        return $this;
    }

    /**
     * @return int
     */
    public function getInicialsecundaria()
    {
        return $this->inicialsecundaria;
    }

    /**
     * @param int $inicialsecundaria
     * @return Iniciaisunificadas
     */
    public function setInicialsecundaria($inicialsecundaria)
    {
        $this->inicialsecundaria = $inicialsecundaria;
        return $this;
    }

    /**
     * @return int
     */
    public function getCertidao()
    {
        return $this->certidao;
    }

    /**
     * @param int $certidao
     * @return Iniciaisunificadas
     */
    public function setCertidao($certidao)
    {
        $this->certidao = $certidao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataunificacao()
    {
        return $this->dataunificacao;
    }

    /**
     * @param string $dataunificacao
     * @return Iniciaisunificadas
     */
    public function setDataunificacao($dataunificacao)
    {
        $this->dataunificacao = $dataunificacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int $usuario
     * @return Iniciaisunificadas
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }
}
