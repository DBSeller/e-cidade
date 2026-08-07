<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasDepart;

class TaxasLancadasDepartRepository extends \BaseClassRepository
{
    public function persist(TaxasLancadasDepart $entity)
    {
        $dao = new \cl_taxaslancadasdepart();

        $dao->ar45_sequencial = $entity->getSequencial();
        $dao->ar45_taxaslancadas = $entity->getTaxaslancadas();
        $dao->ar45_departamento = $entity->getDepartamento();

        if (!empty($dao->ar45_sequencial)) {
            $dao->alterar($dao->ar45_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function make($oObject)
    {
        $taxasLancadasDepart = new TaxasLancadasDepart();

        if (!empty($oObject->ar45_sequencial)) {
            $taxasLancadasDepart->setSequencial($oObject->ar45_sequencial);
        }
        if (!empty($oObject->ar45_taxaslancadas)) {
            $taxasLancadasDepart->setTaxaslancadas($oObject->ar45_taxaslancadas);
        }
        if ($oObject->ar45_departamento != "") {
            $taxasLancadasDepart->setDepartamento($oObject->ar45_departamento);
        }

        if (!empty($oObject->ar44_sequencial)) {
            $taxasLancadasDepart->setTaxaslancadas($oObject->ar44_sequencial);
        }

        return $taxasLancadasDepart;
    }

    private function workData(TaxasLancadasDepart $oDepartamento)
    {
        $oDados = (object) array();

        $oDados->ar45_sequencial = $oDepartamento->getSequencial();
        $oDados->ar45_taxaslancadas = $oDepartamento->getTaxaslancadas();
        $oDados->ar45_departamento = $oDepartamento->getDepartamento();

        return $oDados;
    }

    public function makeCollection($rReturn)
    {
        $oReturn = \db_utils::getColectionByRecord($rReturn);
        $aDados = array();

        foreach ($oReturn as $item) {
            $oDados = (object) array();
            $oDepartamento = $this->make($item);

            $oDados = $this->workData($oDepartamento);

            $aDados[] = $oDados;
        }

        return $aDados;
    }

    public function delete(TaxasLancadasDepart $entity)
    {
        $dao = new \cl_taxaslancadasdepart();

        $sWhere = $this->getCondicao($entity);

        $dao->excluir("", $sWhere);

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function getDepartamentos($entity)
    {
        $dao = new \cl_taxaslancadasdepart();

        $sWhere = $this->getCondicao($entity);

        $result = db_query($dao->sql_query("", "*", "", $sWhere));

        if (!$result) {
            throw new \Exception("Erro ao buscar os departamentos. Erro: ".pg_last_error());
        }

        return $this->makeCollection($result);
    }

    private function getCondicao($entity)
    {
        $sWhere = "";

        if (!empty($entity->getSequencial())) {
            $sWhere = " ar45_sequencial = ".$entity->getSequencial();
        }

        if (!empty($entity->getTaxaslancadas())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." ar45_taxaslancadas = ".$entity->getTaxaslancadas();
        }

        if (!empty($entity->getDepartamento())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." ar45_departamento = ".$entity->getDepartamento();
        }

        if (!empty($entity->sWhere)) {
            $sWhere = $entity->sWhere;
        }

        return $sWhere;
    }
}
