<?php

namespace ECidade\Tributario\Caixa\Entity\Collection;

use ECidade\Tributario\Library\EntityCollection;
use ECidade\Tributario\Caixa\Entity\CotaUnica;

final class CotaUnicaCollection extends EntityCollection
{
    protected function get($index)
    {
        $recibounica = $this->modelCollection->offsetGet($index);

        $cotaUnica = new CotaUnica();

        $cotaUnica->setNumpre($recibounica->getMatric());
        $cotaUnica->setVencimento($recibounica->getDtvenc());
        $cotaUnica->setOperacao($recibounica->getDtoper());
        $cotaUnica->setPorcentagem($recibounica->getPercdes());

        return $cotaUnica;
    }
}
