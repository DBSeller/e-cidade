<?php

namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Issqn\Model\Issbase;

class IssbaseRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $issbase = new Issbase();

        $issbase->setInscr($object->q02_inscr);
        $issbase->setNumcgm($object->q02_numcgm);
        $issbase->setMemo($object->q02_memo);
        $issbase->setTiplic($object->q02_tiplic);
        $issbase->setRegjuc($object->q02_regjuc);
        $issbase->setInscmu($object->q02_inscmu);
        $issbase->setObs($object->q02_obs);
        $issbase->setDtcada($object->q02_dtcada);
        $issbase->setDtinic($object->q02_dtinic);
        $issbase->setDtbaix($object->q02_dtbaix);
        $issbase->setCapit($object->q02_capit);
        $issbase->setCep($object->q02_cep);
        $issbase->setDtjunta($object->q02_dtjunta);
        $issbase->setUltalt($object->q02_ultalt);
        $issbase->setDtalt($object->q02_dtalt);

        return $issbase;
    }

    public function find($inscricao)
    {
        $sql = $this->dao->sql_query_file($inscricao);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }

    public function findByProcesso($processo)
    {
        $sql =  $this->dao->sql_query_processo($processo);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $aInscricoes = \db_utils::makeCollectionFromRecord(
            $result,
            function ($oDados) {
                if (!empty($oDados)) {
                    return $this->make($oDados);
                }
            }
        );

        return $aInscricoes;
    }
}
