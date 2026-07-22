<?php

namespace ECidade\Tributario\Inflatores\Repository;

use DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Inflatores\Model\Infla;

final class InflaRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $infla = new Infla();

        $infla->setCodigo($object->i02_codigo);
        $infla->setData($object->i02_data);
        $infla->setValor($object->i02_valor);

        return $infla;
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

    public function find($codigo, DateTime $data)
    {
        $sql = $this->dao->sql_query_file($codigo, $data->format('Y-m-d'));

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where)
    {
        $sql = $this->dao->sql_query(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }
}
