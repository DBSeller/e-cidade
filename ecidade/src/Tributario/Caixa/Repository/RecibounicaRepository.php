<?php 

namespace ECidade\Tributario\Caixa\Repository;

use \DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Recibounica;
use ECidade\Tributario\Caixa\Collection\RecibounicaCollection;

final class RecibounicaRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $recibounica = new Recibounica();

        $recibounica->setNumpre($object->k00_numpre);
        $recibounica->setDtvenc(new DateTime($object->k00_dtvenc));
        $recibounica->setDtoper(new DateTime($object->k00_dtoper));
        $recibounica->setPercdes($object->k00_percdes);
        $recibounica->setTipoger($object->k00_tipoger);
        $recibounica->setRecibounicageracao($object->k00_recibounicageracao);
        $recibounica->setSequencial($object->k00_sequencial);

        return $recibounica;
    }

    public function find($sequencial)
    {
        $sql = $this->dao->sql_query_file($sequencial);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);
        
        $result = $this->dataBase->execute($sql);

        return new RecibounicaCollection($result);
    }
}
