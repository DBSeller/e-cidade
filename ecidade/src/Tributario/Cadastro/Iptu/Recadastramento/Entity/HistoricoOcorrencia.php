<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Entity;

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/03/18
 * Time: 11:25
 */

class HistoricoOcorrencia
{
    /**
     * @var $sequencial
     */
    private $sequencial;

    /**
     * @var $idUsuario
     */
    private $idUsuario;

    /**
     * @var $instit
     */
    private $instit;

    /**
     * @var $modulo
     */
    private $modulo;

    /**
     * @var $idItensmenu
     */
    private $idItensmenu;

    /**
     * @var $data
     */
    private $data;

    /**
     * @var $hora
     */
    private $hora;

    /**
     * @var $tipo
     */
    private $tipo;

    /**
     * @var $descricao
     */
    private $descricao;

    /**
     * @var $ocorrencia
     */
    private $ocorrencia;

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param mixed $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return mixed
     */
    public function getInstit()
    {
        return $this->instit;
    }

    /**
     * @param mixed $instit
     */
    public function setInstit($instit)
    {
        $this->instit = $instit;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
    }

    /**
     * @return mixed
     */
    public function getIdItensmenu()
    {
        return $this->idItensmenu;
    }

    /**
     * @param mixed $idItensmenu
     */
    public function setIdItensmenu($idItensmenu)
    {
        $this->idItensmenu = $idItensmenu;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param mixed $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getOcorrencia()
    {
        return $this->ocorrencia;
    }

    /**
     * @param mixed $ocorrencia
     */
    public function setOcorrencia($ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;
    }

}