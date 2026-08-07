<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use Ecidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Carfator;

final class CarfatorRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $carfator = new Carfator();

        $carfator->setAnousu($object->j74_anousu);
        $carfator->setCaract($object->j74_caract);
        $carfator->setFator($object->j74_fator);
        $carfator->setCorrig($object->j74_corrig);
        
        return $carfator;
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

    public function find($anousu, $caract)
    {
        $sql = $this->dao->sql_query_file($anousu, $caract);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
