<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Configuracoesteftipodebito;

class ConfiguracoesteftipodebitoRepository
{
    private $configuracoesteftipodebito;

    public function __construct()
    {
        $this->configuracoesteftipodebito = new Configuracoesteftipodebito();
    }

    public function getByTipo($tipo)
    {
        $cl_configuracoesteftipodebito = new \cl_configuracoesteftipodebito();

        $rResult = db_query($cl_configuracoesteftipodebito->sql_query_file(null, "*", null, "k196_tipo = {$tipo}"));

        if (!$rResult) {
            throw new \Exception("Erro ao buscar as configuraçoes para TEF.");
        }

        return \db_utils::fieldsMemory($rResult, 0);
    }

    public function salvar(Configuracoesteftipodebito $entity)
    {
        $cl_configuracoesteftipodebito = new \cl_configuracoesteftipodebito();

        $cl_configuracoesteftipodebito->k196_sequencial = $entity->getSequencial();
        $cl_configuracoesteftipodebito->k196_tipo = $entity->getTipo();
        $cl_configuracoesteftipodebito->k196_aceitatef = $entity->getAceitatef();
        $cl_configuracoesteftipodebito->k196_maximoparcelas = $entity->getMaximoparcelas();
        $cl_configuracoesteftipodebito->k196_valorminimoparcelafisica = $entity->getValorminimoparcelafisica();
        $cl_configuracoesteftipodebito->k196_valorminimoparcelajuridica = $entity->getValorminimoparcelajuridica();

        if (!empty($cl_configuracoesteftipodebito->k196_sequencial)) {
            $cl_configuracoesteftipodebito->alterar($cl_configuracoesteftipodebito->k196_sequencial);
        } else {
            $cl_configuracoesteftipodebito->incluir(null);
        }

        if ($cl_configuracoesteftipodebito->erro_status == "0") {
            throw new \Exception($cl_configuracoesteftipodebito->erro_msg);
        }

        return $cl_configuracoesteftipodebito->k196_sequencial;
    }
}
