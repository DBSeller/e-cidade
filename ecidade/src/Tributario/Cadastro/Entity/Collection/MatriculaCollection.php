<?php

namespace ECidade\Tributario\Cadastro\Entity\Collection;

use ECidade\Tributario\Library\EntityCollection;
use ECidade\Tributario\Cadastro\Entity\Matricula;

final class MatriculaCollection extends EntityCollection
{
    protected function get($index)
    {
        $iptubase = $this->modelCollection->offsetGet($index);

        $matricula = new Matricula();

        $matricula->setMatricula($iptubase->getMatric());
        $matricula->setCgm($iptubase->getNumcgm());

        return $matricula;
    }
}
