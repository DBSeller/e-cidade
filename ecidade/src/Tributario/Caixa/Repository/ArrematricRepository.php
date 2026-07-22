<?php

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Caixa\Model\Arrematric;

final class ArrematricRepository extends ArrebaseRepository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $arrematric = new Arrematric();

        $arrematric->setNumpre($object->k00_numpre);
        $arrematric->setMatric($object->k00_matric);
        $arrematric->setPerc($object->k00_perc);

        return $arrematric;
    }
}
