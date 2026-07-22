<?php

namespace ECidade\Tributario\Caixa\Entity\Collection;

use ECidade\Tributario\Library\ArrayCollection;

final class DebitoCollection extends ArrayCollection
{
    public function add($debito)
    {
        parent::add($debito);
    }
}
