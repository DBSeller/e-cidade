<?php

namespace ECidade\Tributario\ITBI\Repository;

use ECidade\Tributario\ITBI\Model\Taxasitbitaxa;

class TaxasitbitaxaRepository extends \BaseClassRepository
{
    public function persist(Taxasitbitaxa $entity)
    {
        $dao = new \cl_taxasitbitaxa();

        $dao->it37_sequencial = $entity->getSequencial();
        $dao->it37_taxasitbi = $entity->getTaxasitbi();
        $dao->it37_taxaslancadas = $entity->getTaxaslancadas();
        $dao->it37_calculasobre = $entity->getCalculasobre();
        $dao->it37_iniciofaixa = $entity->getIniciofaixa();
        $dao->it37_fimfaixa = $entity->getFimfaixa();
        
        if (!empty($dao->it37_sequencial)) {
            $dao->alterar($dao->it37_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function delete(Taxasitbitaxa $entity)
    {
        $dao = new \cl_taxasitbitaxa();

        $sWhere = $this->getCondicao($entity);

        $dao->excluir("", $sWhere);

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    private function getCondicao($entity)
    {
        $sWhere = "";

        if (!empty($entity->getSequencial())) {
            $sWhere = " it37_sequencial = ".$entity->getSequencial();
        }

        if (!empty($entity->getTaxasitbi())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." it37_taxasitbi = ".$entity->getTaxasitbi();
        }

        if (!empty($entity->getTaxaslancadas())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." it37_taxaslancadas = ".$entity->getTaxaslancadas();
        }

        if (!empty($entity->getCalculasobre())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." it37_calculasobre = ".$entity->getCalculasobre();
        }

        return $sWhere;
    }

    public function getTaxas(Taxasitbitaxa $entity)
    {
        $dao = new \cl_taxasitbitaxa();

        $sWhere = $this->getCondicao($entity);

        (trim($sWhere) != "" ? $sWhere .= " AND " : "");

        $sWhere = $sWhere." (ar44_datavigencia >= to_char(now(), 'YYYY-MM-DD')::date OR ar44_datavigencia IS NULL)";

        $result = db_query($dao->sql_query("", "", "it37_taxaslancadas", $sWhere));

        if (!$result) {
            throw new \Exception("Erro ao buscar os taxas. Erro: ".pg_last_error());
        }

        return \db_utils::getColectionByRecord($result);
    }
}
