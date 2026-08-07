<?php

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Library\Repository;

abstract class ArrebaseRepository extends Repository
{
    protected function makeCollection($array)
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

    public function find($numpre, $identificador)
    {
        $sql = $this->dao->sql_query_file($numpre, $identificador);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function findByIdentificador($identificador)
    {
        $sql = $this->dao->sql_query_file(null, $identificador);
        $result = $this->dataBase->execute($sql);
        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    abstract public function make($object);
}
