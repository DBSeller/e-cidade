<?php

namespace ECidade\Tributario\Caixa\Collection;

use \DateTime;
use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Caixa\Model\Recibopagaboleto;

final class RecibopagaboletoCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $recibopagaboleto = new Recibopagaboleto();

        $recibopagaboleto->setSequencial($object->k138_sequencial);
        $recibopagaboleto->setNumnov($object->k138_numnov);
        $recibopagaboleto->setData(new DateTime($object->k138_data));
        $recibopagaboleto->setHora($object->k138_hora);
        $recibopagaboleto->setUsuario($object->k138_usuario);

        return $recibopagaboleto;
    }
}
