<?php

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptucalv;

final class IptucalvRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptucalv = new Iptucalv();

        $iptucalv->setAnousu($object->j21_anousu);
        $iptucalv->setMatric($object->j21_matric);
        $iptucalv->setReceit($object->j21_receit);
        $iptucalv->setValor($object->j21_valor);
        $iptucalv->setQuant($object->j21_quant);
        $iptucalv->setCodhis($object->j21_codhis);

        return $iptucalv;
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

    public function find($anousu, $matric)
    {
        $sql = $this->dao->sql_query_file(null, '*', null, "j21_anousu = {$anousu} and j21_matric = {$matric}");

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
