<?php

namespace ECidade\Tributario\Cadastro\Collection;

use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Cadastro\Model\Carlote;

final class CarloteCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $carlote = new Carlote();

        $carlote->setIdbql($object->j35_idbql);
        $carlote->setCaract($object->j35_caract);
        $carlote->setDtlanc(new \DateTime($object->j35_dtlanc));
        
        return $carlote;
    }
}
