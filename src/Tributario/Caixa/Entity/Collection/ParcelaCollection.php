<?php

namespace ECidade\Tributario\Caixa\Entity\Collection;

use ECidade\Tributario\Library\ArrayCollection;

final class ParcelaCollection extends ArrayCollection
{
    public function add($parcela)
    {
        parent::add($parcela);
    }

    public function getByIndex($index)
    {
        return parent::get($index);
    }
}
