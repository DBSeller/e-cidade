<?php 

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Recibocodbar;

final class RecibocodbarRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $recibocodbar = new Recibocodbar();

        $recibocodbar->setNumpre($object->k00_numpre);
        $recibocodbar->setCodbar($object->k00_codbar);
        $recibocodbar->setLinhadigitavel($object->k00_linhadigitavel);
        $recibocodbar->setNossonumero($object->k00_nossonumero);

        return $recibocodbar;
    }

    public function find($numpre)
    {
        $sql = $this->dao->sql_query_file($numpre);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }
}
