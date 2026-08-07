<?php

namespace ECidade\Tributario\Caixa\Entity;

use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
use ECidade\Tributario\Library\Entity;

final class Lista extends Entity
{
    private $codigo;

    private $debitos;

    private $descricao;

    private $tipo;

    private $data;

    private $usuario;

    private $instituicao;

    public function __construct()
    {
        $this->debitos = new DebitoCollection();
    }

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
     * @return DebitoCollection
     */
    public function getDebitos()
    {
        return $this->debitos;
    }

    /**
     * @param DebitoCollection $debitos
     */
    public function setDebitos(DebitoCollection $debitos)
    {
        $this->debitos = $debitos;
    }

    /**
     * @param Debito $debitos
     */
    public function addDebito(Debito $debito)
    {
        $this->debitos->add($debito);
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
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param mixed $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }
}