<?php 

namespace ECidade\Tributario\Caixa\Repository;

use \DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Recibopagaboleto;
use ECidade\Tributario\Caixa\Collection\RecibopagaboletoCollection;

final class RecibopagaboletoRepository extends Repository
{
    public function insert(Recibopagaboleto $recibopagaboleto)
    {
        $this->dao->k138_sequencial = $recibopagaboleto->getSequencial();
        $this->dao->k138_numnov = $recibopagaboleto->getNumnov();
        $this->dao->k138_data = $recibopagaboleto->getData()->format('Y-m-d');
        $this->dao->k138_hora = $recibopagaboleto->getHora();
        $this->dao->k138_usuario = $recibopagaboleto->getUsuario();
        
        return $this->dao->incluir(null);
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $recibopagaboleto = new Recibopagaboleto();

        $recibopagaboleto->setSequencial($object->k138_sequencial);
        $recibopagaboleto->setNumnov($object->k138_numnov);
        $recibopagaboleto->setData(new DateTime($object->k138_data));
        $recibopagaboleto->setHora($object->k138_hora);
        $recibopagaboleto->setUsuario($object->k138_usuario);
        
        return $recibopagaboleto;
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

        return new RecibopagaboletoCollection($result);
    }
}
