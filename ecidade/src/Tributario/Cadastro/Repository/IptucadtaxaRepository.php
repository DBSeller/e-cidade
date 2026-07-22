<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptucadtaxa;

final class IptucadtaxaRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptucadtaxa = new Iptucadtaxa();

        $iptucadtaxa->setIptucadtaxa($object->j07_iptucadtaxa);
        $iptucadtaxa->setDescr($object->j07_descr);      

        return $iptucadtaxa;
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

    public function find($iptucadtaxa)
    {
        $sql = $this->dao->sql_query_file($iptucadtaxa);

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
