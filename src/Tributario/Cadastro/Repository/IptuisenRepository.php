<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptuisen;

final class IptuisenRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptuisen = new Iptuisen();

        $iptuisen->setCodigo($object->j46_codigo);
        $iptuisen->setMatric($object->j46_matric);
        $iptuisen->setTipo($object->j46_tipo);  
        $iptuisen->setDtini($object->j46_dtini); 
        $iptuisen->setDtfim($object->j46_dtfim); 
        $iptuisen->setPerc($object->j46_perc);  
        $iptuisen->setDtinc($object->j46_dtinc); 
        $iptuisen->setIdusu($object->j46_idusu); 
        $iptuisen->setHist($object->j46_hist);  
        $iptuisen->setArealo($object->j46_arealo);

        return $iptuisen;
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
        $sql = $this->dao->sql_query_file($codigo);

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
