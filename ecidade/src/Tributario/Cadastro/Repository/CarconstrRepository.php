<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Carconstr;

final class CarconstrRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $carconstr = new Carconstr();

        $carconstr->setMatric($object->j48_matric);
        $carconstr->setIdcons($object->j48_idcons);
        $carconstr->setCaract($object->j48_caract);

        return $carconstr;
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

    public function find($matric, $idcons, $caract)
    {
        $sql = $this->dao->sql_query_file($matric, $idcons, $caract);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
