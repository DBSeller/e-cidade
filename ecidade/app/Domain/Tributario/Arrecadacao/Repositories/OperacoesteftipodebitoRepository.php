<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesteftipodebito;

class OperacoesteftipodebitoRepository
{
    private $operacoesteftipodebito;

    public function __construct()
    {
        $this->operacoesteftipodebito = new Operacoesteftipodebito();
    }

    public function deleteByConfig($configuracoesteftipodebito)
    {
        $cl_operacoesteftipodebito = new \cl_operacoesteftipodebito();

        $cl_operacoesteftipodebito->excluir(null, "k197_configuracoesteftipodebito = {$configuracoesteftipodebito}");
    }

    public function getByConfig($configuracoesteftipodebito)
    {
        $cl_operacoesteftipodebito = new \cl_operacoesteftipodebito();

        $rResult = db_query($cl_operacoesteftipodebito->sql_query(
            null,
            "*",
            null,
            "k197_configuracoesteftipodebito = {$configuracoesteftipodebito}"
        ));

        if (!$rResult) {
            throw new \Exception("Erro ao buscar as operações para TEF deste tipo de débito.");
        }

        return \db_utils::getCollectionByRecord($rResult);
    }

    public function salvar(Operacoesteftipodebito $entity)
    {
        $cl_operacoesteftipodebito = new \cl_operacoesteftipodebito();

        $cl_operacoesteftipodebito->k197_sequencial = $entity->getSequencial();
        $cl_operacoesteftipodebito->k197_configuracoesteftipodebito = $entity->getConfiguracoesteftipodebito();
        $cl_operacoesteftipodebito->k197_operacoestef = $entity->getOperacoestef();

        if (!empty($cl_operacoesteftipodebito->k197_sequencial)) {
            $cl_operacoesteftipodebito->alterar($cl_operacoesteftipodebito->k197_sequencial);
        } else {
            $cl_operacoesteftipodebito->incluir(null);
        }

        if ($cl_operacoesteftipodebito->erro_status == "0") {
            throw new \Exception($cl_operacoesteftipodebito->erro_msg);
        }
    }
}
