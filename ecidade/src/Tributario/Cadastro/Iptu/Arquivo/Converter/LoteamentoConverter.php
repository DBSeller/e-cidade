<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Library\Entity;

final class LoteamentoConverter extends Converter
{
    public function get(Entity $loteamento)
    {
        return str_pad(substr($loteamento->getDescricao(), 0, 40), 40, ' ');
    }
}
