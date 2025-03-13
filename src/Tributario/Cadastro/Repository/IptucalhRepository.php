<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptucalh;

final class IptucalhRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptucalh = new Iptucalh();

        $iptucalh->setCodhis($object->j17_codhis);
        $iptucalh->setDescr($object->j17_descr);

        return $iptucalh;
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

    public function find($codhis)
    {
        $sql = $this->dao->sql_query_file($codhis);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, '*', null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
