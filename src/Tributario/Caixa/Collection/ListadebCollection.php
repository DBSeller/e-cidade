<?php

namespace ECidade\Tributario\Caixa\Collection;

use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Caixa\Model\Listadeb;

final class ListadebCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $arrecad = new Listadeb();

        $arrecad->setCodigo($object->k61_codigo);
        $arrecad->setNumpre($object->k61_numpre);
        $arrecad->setNumpar($object->k61_numpar);

        return $arrecad;
    }
}