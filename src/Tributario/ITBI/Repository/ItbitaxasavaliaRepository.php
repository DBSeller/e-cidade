<?php

namespace ECidade\Tributario\ITBI\Repository;

use ECidade\Tributario\ITBI\Model\Itbitaxasavalia;

class ItbitaxasavaliaRepository extends \BaseClassRepository
{
    public function persist(Itbitaxasavalia $entity)
    {
        $dao = new \cl_itbitaxasavalia();

        $dao->it39_sequencial = $entity->getSequencial();
        $dao->it39_guia = $entity->getGuia();
        $dao->it39_taxaslancadas = $entity->getTaxaslancadas();
        $dao->it39_valor = $entity->getValor();
        $dao->it39_calculasobre = $entity->getCalculasobre();
        $dao->it39_aliquota = $entity->getAliquota();

        if (!empty($dao->it39_sequencial)) {
            $dao->alterar($dao->it39_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function delete(Itbitaxasavalia $entity)
    {
        $dao = new \cl_itbitaxasavalia();

        $sWhere = $this->getCondicao($entity);

        $dao->excluir("", $sWhere);

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function getDadosTaxas(Itbitaxasavalia $entity)
    {
        $dao = new \cl_itbitaxasavalia();

        $sWhere = $this->getCondicao($entity);

        $result = db_query($dao->sql_query("", "", "it39_taxaslancadas", $sWhere));

        if (!$result) {
            throw new \Exception("Erro ao buscar os taxas. Erro: ".pg_last_error());
        }

        return \db_utils::getColectionByRecord($result);
    }

    private function getCondicao($entity)
    {
        $sWhere = "";

        if (!empty($entity->getSequencial())) {
            $sWhere = " it39_sequencial = ".$entity->getSequencial();
        }

        if (!empty($entity->getGuia())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." it39_guia = ".$entity->getGuia();
        }

        if (!empty($entity->getTaxaslancadas())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." it39_taxaslancadas = ".$entity->getTaxaslancadas();
        }

        if (!empty($entity->getCalculasobre())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." it39_calculasobre = ".$entity->getCalculasobre();
        }

        return $sWhere;
    }
}
