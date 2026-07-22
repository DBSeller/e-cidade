<?php

namespace ECidade\Tributario\Cadastro\Collection;

use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Cadastro\Model\Iptubase;

final class IptubaseCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $iptubase = new Iptubase();

        $iptubase->setMatric($object->j01_matric);
        $iptubase->setNumcgm($object->j01_numcgm);
        $iptubase->setIdbql($object->j01_idbql);
        $iptubase->setBaixa($object->j01_baixa);
        $iptubase->setCodave($object->j01_codave);
        $iptubase->setFracao($object->j01_fracao);
        
        return $iptubase;
    }
}
