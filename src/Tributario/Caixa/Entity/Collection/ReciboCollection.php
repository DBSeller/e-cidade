<?php

namespace ECidade\Tributario\Caixa\Entity\Collection;

use ECidade\Tributario\Library\ArrayCollection;
use ECidade\Tributario\Caixa\Entity\Recibo;

final class ReciboCollection extends ArrayCollection
{
    public function add(Recibo $recibo)
    {
        parent::add($recibo);
    }
}
