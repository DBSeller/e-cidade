<?php

namespace ECidade\Tributario\ITBI\Repository;

use ECidade\Tributario\ITBI\Model\Itbitaxasitbi;

class ItbitaxasitbiRepository extends \BaseClassRepository
{
    public function persist(Itbitaxasitbi $entity)
    {
        $dao = new \cl_itbitaxasitbi();

        $dao->it38_sequencial = $entity->getSequencial();
        $dao->it38_itbi = $entity->getItbi();
        $dao->it38_taxasitbi = $entity->getTaxasitbi();
        
        if (!empty($dao->it38_sequencial)) {
            $dao->alterar($dao->it38_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function getDados(Itbitaxasitbi $entity)
    {
        $dao = new \cl_itbitaxasitbi();

        $sWhere = $this->getCondicao($entity);

        $result = db_query($dao->sql_query("", "*", "", $sWhere));


        if (!$result) {
            throw new \Exception("Erro ao buscar o dados da guia. Erro: ".pg_last_error());
        }

        return \db_utils::fieldsMemory($result, 0);
    }

    private function getCondicao($entity)
    {
        $sWhere = "";

        if (!empty($entity->getSequencial())) {
            $sWhere = " it38_sequencial = ".$entity->getSequencial();
        }

        if (!empty($entity->getItbi())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." it38_itbi = ".$entity->getItbi();
        }

        if (!empty($entity->getTaxasitbi())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." it38_taxasitbi = ".$entity->getTaxasitbi();
        }

        return $sWhere;
    }

    public function getTipoPelaGuia($guia)
    {
        $dao = new \cl_itbitaxasitbi();

        $sWhere = "it38_itbi = " . intval($guia);

        $result = db_query($dao->sql_query("", "*", "", $sWhere));


        if (!$result) {
            throw new \Exception("Erro ao buscar o dados da guia. Erro: ".pg_last_error());
        }

        return \db_utils::fieldsMemory($result, 0);
    }
}
