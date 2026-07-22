<?php

namespace ECidade\Tributario\Caixa\Model;

use \DateTime;
use ECidade\Tributario\Library\Model;

final class Recibopagaboleto extends Model
{
    private $sequencial;

    private $numnov;

    private $data;

    private $hora;

    private $usuario;

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;
    }

    public function setData(DateTime $data)
    {
        $this->data = $data;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function getNumnov()
    {
        return $this->numnov;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
}
