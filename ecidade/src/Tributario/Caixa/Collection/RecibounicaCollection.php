<?php

namespace ECidade\Tributario\Caixa\Collection;

use \DateTime;
use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Caixa\Model\Recibounica;

final class RecibounicaCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $recibounica = new Recibounica();

        $recibounica->setNumpre($object->k00_numpre);
        $recibounica->setDtvenc(new DateTime($object->k00_dtvenc));
        $recibounica->setDtoper(new DateTime($object->k00_dtoper));
        $recibounica->setPercdes($object->k00_percdes);
        $recibounica->setTipoger($object->k00_tipoger);
        $recibounica->setRecibounicageracao($object->k00_recibounicageracao);
        $recibounica->setSequencial($object->k00_sequencial);

        return $recibounica;
    }
}
