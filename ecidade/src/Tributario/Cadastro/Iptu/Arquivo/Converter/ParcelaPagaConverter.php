<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaPaga;
use ECidade\Tributario\Library\Entity;

final class ParcelaPagaConverter extends Converter
{
    public function get(Entity $parcelaPaga)
    {
        $s = $this->format->date($parcelaPaga->getData());
        $s .= $this->format->decimal($parcelaPaga->getValor());

        return $s;
    }
}
