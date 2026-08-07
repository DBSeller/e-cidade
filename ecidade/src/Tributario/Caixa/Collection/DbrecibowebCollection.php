<?php

namespace ECidade\Tributario\Caixa\Collection;

use \DateTime;
use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Caixa\Model\Dbreciboweb;

final class DbrecibowebCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $dbreciboweb = new Dbreciboweb();

        $dbreciboweb->setNumpre($object->k99_numpre);  
        $dbreciboweb->setNumpar($object->k99_numpar);  
        $dbreciboweb->setNumpren($object->k99_numpre_n);
        $dbreciboweb->setCodbco($object->k99_codbco);  
        $dbreciboweb->setCodage($object->k99_codage);  
        $dbreciboweb->setNumbco($object->k99_numbco);  
        $dbreciboweb->setDesconto($object->k99_desconto);
        $dbreciboweb->setTipo($object->k99_tipo);    
        $dbreciboweb->setOrigem($object->k99_origem);  
        
        return $dbreciboweb;
    }
}
