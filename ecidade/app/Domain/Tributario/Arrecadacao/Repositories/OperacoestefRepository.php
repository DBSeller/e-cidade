<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Operacoestef;

class OperacoestefRepository
{
    private $operacoestef;

    public function __construct()
    {
        $this->operacoestef = new Operacoestef();
    }

    public function get()
    {
        $cl_operacoestef = new \cl_operacoestef();

        $rRecord = $cl_operacoestef->sql_record($cl_operacoestef->sql_query_file());

        if ($cl_operacoestef->erro_status == "0") {
            throw new \Exception($cl_operacoestef->erro_msg);
        }

        return \db_utils::getCollectionByRecord($rRecord);
    }

    public function salvar(Operacoestef $entity)
    {
        $cl_operacoestef = new \cl_operacoestef();

        $cl_operacoestef->k195_sequencial = $entity->getSequencial();
        $cl_operacoestef->k195_descricao = $entity->getDescricao();
        $cl_operacoestef->k195_codigoperacao = $entity->getCodigoperacao();

        if (!empty($cl_operacoestef->k195_sequencial)) {
            $cl_operacoestef->alterar($cl_operacoestef->k195_sequencial);
        } else {
            $cl_operacoestef->incluir(null);
        }

        if ($cl_operacoestef->erro_status == "0") {
            throw new \Exception($cl_operacoestef->erro_msg);
        }
    }
}
