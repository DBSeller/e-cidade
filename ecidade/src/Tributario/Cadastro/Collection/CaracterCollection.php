<?php

namespace ECidade\Tributario\Cadastro\Collection;

use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Cadastro\Model\Caracter;

final class CaracterCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $caracter = new Caracter();

        $caracter->setCodigo($object->j31_codigo);
        $caracter->setDescr($object->j31_descr);
        $caracter->setGrupo($object->j31_grupo);
        $caracter->setPontos($object->j31_pontos);
        
        return $caracter;
    }

    public function toArrayCodigo()
    {
        $array = [];

        foreach ($this as $caracter) {
            $array[] = $caracter->getCodigo();
        }

        sort($array);

        return $array;
    }
}
