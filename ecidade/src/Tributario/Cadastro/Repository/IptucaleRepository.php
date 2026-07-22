<?php

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptucale;

final class IptucaleRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptucale = new Iptucale();

        $iptucale->setAnousu($object->j22_anousu);
        $iptucale->setMatric($object->j22_matric);
        $iptucale->setIdcons($object->j22_idcons);
        $iptucale->setAreaed($object->j22_areaed);
        $iptucale->setVm2($object->j22_vm2);
        $iptucale->setPontos($object->j22_pontos);
        $iptucale->setValor($object->j22_valor);

        return $iptucale;
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

    public function find($anousu, $matric, $idcons)
    {
        $sql = $this->dao->sql_query_file($anousu, $matric, $idcons);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, null, null, '*', null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }
}
