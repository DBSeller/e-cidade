<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Isenproc;

final class IsenprocRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $isenproc = new Isenproc();

        $isenproc->setCodigo($object->j61_codigo);
        $isenproc->setCodproc($object->j61_codproc);

        return $isenproc;
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

    public function find($codigo)
    {
        $sql = $this->dao->sql_query_file(null, "*", null, "j61_codigo = {$codigo}");

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
