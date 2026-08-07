<?php

namespace ECidade\Tributario\ITBI\Repository;

use ECidade\Tributario\ITBI\Model\Taxasitbi;

class TaxasitbiRepository extends \BaseClassRepository
{
    public function persist(Taxasitbi $entity)
    {
        $dao = new \cl_taxasitbi();

        $dao->it36_sequencial = $entity->getSequencial();
        $dao->it36_descricao = $entity->getDescricao();
        $dao->it36_imovelurbano = $entity->getImovelurbano();
        $dao->it36_imovelrural = $entity->getImovelrural();
        $dao->it36_imovelurbanopleno = $entity->getImovelurbanopleno();

        if (!empty($dao->it36_sequencial)) {
            $dao->alterar($dao->it36_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }

        return $dao->it36_sequencial;
    }

    public function getTipoById($sequencial)
    {
        $dao = new \cl_taxasitbi();

        $result = $dao->sql_record($dao->sql_query("", "*", "", " it36_sequencial = {$sequencial}"));

        if (!$result) {
            throw new \Exception("Erro aos buscar o tipo. \n\n {$dao->erro_msg}");
        }

        return \db_utils::fieldsMemory($result, 0);
    }

    public function getAllTipos($sWhere)
    {
        $dao = new \cl_taxasitbi();

        $result = db_query($dao->sql_query("", "*", "", $sWhere));

        if (!$result) {
            throw new \Exception("Erro aos buscar os tipos. \n\n {$dao->erro_msg}");
        }

        return \db_utils::getColectionByRecord($result);
    }
}
