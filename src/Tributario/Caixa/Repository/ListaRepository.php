<?php

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Library\Repository as RepositoryAbstract;
use ECidade\Tributario\Caixa\Model\Lista;
use \DateTime;

final class ListaRepository extends RepositoryAbstract 
{
    public function find($codigo)
    {
        $query = $this->dao->sql_query_file($codigo);
                
        $result = $this->dataBase->execute($query);
        
        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }

    public function make($object)
    {
        $lista = new Lista();
        
        $lista->setCodigo($object->k60_codigo);
        $lista->setDescr($object->k60_descr);
        $lista->setTipo($object->k60_tipo);
        $lista->setDatadeb(new DateTime($object->k60_datadeb));
        $lista->setUsuario($object->k60_usuario);
        $lista->setInstit($object->k60_instit);

        return $lista;
    }
}