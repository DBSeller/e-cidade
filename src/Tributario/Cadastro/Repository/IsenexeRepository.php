<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Isenexe;

final class IsenexeRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $isenexe = new Isenexe();

        $isenexe->setCodigo($object->j47_codigo);
        $isenexe->setAnousu($object->j47_anousu);

        return $isenexe;
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

    public function find($codigo, $anousu)
    {
        $sql = $this->dao->sql_query_file($codigo, $anousu);

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
