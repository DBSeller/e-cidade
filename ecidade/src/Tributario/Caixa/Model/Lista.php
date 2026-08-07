<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Lista extends Model
{
    private $codigo;

    private $descr;

    private $tipo;

    private $datadeb;

    private $usuario;

    private $instit;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescr($descricao)
    {
        $this->descr = $descricao;
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
    public function getDatadeb()
    {
        return $this->datadeb;
    }

    /**
     * @param mixed $data
     */
    public function setDatadeb($data)
    {
        $this->datadeb = $data;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getInstit()
    {
        return $this->instit;
    }

    /**
     * @param mixed $instituicao
     */
    public function setInstit($instituicao)
    {
        $this->instit = $instituicao;
    }
}