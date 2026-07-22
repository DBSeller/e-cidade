<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use Ecidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Caracter;
use ECidade\Tributario\Cadastro\Collection\CaracterCollection;

final class CaracterRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $caracter = new Caracter();

        $caracter->setCodigo($object->j31_codigo);
        $caracter->setDescr($object->j31_descr);
        $caracter->setGrupo($object->j31_grupo);
        $caracter->setPontos($object->j31_pontos);
        
        return $caracter;
    }

    public function find($codigo)
    {
        $sql = $this->dao->sql_query_file($codigo);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        return new CaracterCollection($result);
    }
}
