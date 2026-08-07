<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Isentaxa;

final class IsentaxaRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $isentaxa = new Isentaxa();

        $isentaxa->setCodigo($object->j56_codigo);
        $isentaxa->setReceit($object->j56_receit);
        $isentaxa->setPerc($object->j56_perc);

        return $isentaxa;
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

    public function find($codigo, $receit)
    {
        $sql = $this->dao->sql_query_file($codigo, $receit);

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
