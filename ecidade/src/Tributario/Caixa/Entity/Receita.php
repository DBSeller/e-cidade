<?php

namespace ECidade\Tributario\Caixa\Entity;

use ECidade\Tributario\Library\Entity;

final class Receita extends Entity
{
    private $codigo;

    private $valor;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getValor()
    {
        return $this->valor;
    }
}
