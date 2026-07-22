<?php

namespace ECidade\Tributario\Caixa\Collection;

use \DateTime;
use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Caixa\Model\Arrecad;

final class ArrecadCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $arrecad = new Arrecad();

        $arrecad->setNumpre($object->k00_numpre);
        $arrecad->setNumpar($object->k00_numpar);
        $arrecad->setNumcgm($object->k00_numcgm);
        $arrecad->setDtoper(new DateTime($object->k00_dtoper));
        $arrecad->setReceit($object->k00_receit);
        $arrecad->setHist($object->k00_hist);
        $arrecad->setValor($object->k00_valor);
        $arrecad->setDtvenc(new DateTime($object->k00_dtvenc));
        $arrecad->setNumtot($object->k00_numtot);
        $arrecad->setNumdig($object->k00_numdig);
        $arrecad->setTipo($object->k00_tipo);
        $arrecad->setTipojm($object->k00_tipojm);

        return $arrecad;
    }
}
