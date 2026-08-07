<?php 

namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Issqn\Model\Cadvenc;

final class CadvencRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $cadvenc = new Cadvenc();

        $cadvenc->setCodigo($object->q82_codigo);
        $cadvenc->setParc($object->q82_parc);
        $cadvenc->setVenc($object->q82_venc);
        $cadvenc->setDesc($object->q82_desc);
        $cadvenc->setPerc($object->q82_perc);
        $cadvenc->setHist($object->q82_hist);
        $cadvenc->setCalculaparcvenc($object->q82_calculaparcvenc);

        return $cadvenc;
    }

    private function makeCollection($array)
    {
        $collection = array();

        if (empty($array)) {
            return $collection;
        }

        foreach ($array as $value) {
            $collection[] = $this->make((object)$value);
        }

        return $collection;
    }

    public function find($codigo, $parc)
    {
        $sql = $this->dao->sql_query_file($codigo, $parc);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
