<?php 

namespace ECidade\Tributario\Inflatores\Model;

use ECidade\Tributario\Library\Model;

final class Infla extends Model 
{
    private $codigo;

    private $data;

    private $valor;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }
}
