<?php 

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Caixa\Model\Arreinscr;

final class ArreinscrRepository extends ArrebaseRepository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $arrematric = new Arreinscr();

        $arrematric->setNumpre($object->k00_numpre);
        $arrematric->setInscr($object->k00_inscr);
        $arrematric->setPerc($object->k00_perc);

        return $arrematric;
    }
}
