<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptuconstr;
use ECidade\Tributario\Cadastro\Collection\IptuconstrCollection;

final class IptuconstrRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptuconstr = new Iptuconstr();

        $iptuconstr->setMatric($object->j39_matric);
        $iptuconstr->setIdcons($object->j39_idcons);
        $iptuconstr->setAno($object->j39_ano);
        $iptuconstr->setArea($object->j39_area);
        $iptuconstr->setAreap($object->j39_areap);
        $iptuconstr->setDtlan($object->j39_dtlan);
        $iptuconstr->setCodigo($object->j39_codigo);
        $iptuconstr->setNumero($object->j39_numero);
        $iptuconstr->setCompl($object->j39_compl);
        $iptuconstr->setDtdemo($object->j39_dtdemo);
        $iptuconstr->setIdaument($object->j39_idaument);
        $iptuconstr->setIdprinc($object->j39_idprinc);
        $iptuconstr->setHabite($object->j39_habite);
        $iptuconstr->setPavim($object->j39_pavim);
        $iptuconstr->setCodprotdemo($object->j39_codprotdemo);
        $iptuconstr->setObs($object->j39_obs);

        return $iptuconstr;
    }

    public function find($matric, $idcons)
    {
        $sql = $this->dao->sql_query_file($matric, $idcons);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, null, '*', null, $where);

        $result = $this->dataBase->execute($sql);

        return new IptuconstrCollection($result);
    }

    public function findAllByMatric($matric)
    {
        return $this->findAll('j39_matric = '.$matric);
    }
}
