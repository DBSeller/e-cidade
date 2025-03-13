<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use Ecidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Carlote;
use ECidade\Tributario\Cadastro\Collection\CarloteCollection;

final class CarloteRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $carlote = new Carlote();

        $carlote->setIdbql($object->j35_idbql);
        $carlote->setCaract($object->j35_caract);
        $carlote->setDtlanc(new \DateTime($object->j35_dtlanc));
        
        return $carlote;
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

    public function find($idbql, $caract)
    {
        $sql = $this->dao->sql_query_file($idbql, $caract);

        $result = $this->dataBase->execute($sql);

        return new CarloteCollection($result);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
