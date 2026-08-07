<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class ParcelaPaga extends Entity
{
    private $data;

    private $valor;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValor()
    {
        return $this->valor;
    }
}
