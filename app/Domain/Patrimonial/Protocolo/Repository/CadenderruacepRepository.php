<?php

namespace App\Domain\Patrimonial\Protocolo\Repository;

use App\Domain\Patrimonial\Protocolo\Model\Cadenderruacep;

class CadenderruacepRepository
{
    private $cadenderruacep;

    public function __construct()
    {
        $this->cadenderruacep = new Cadenderruacep();
    }

    public function getByCep($cep, $campos = ["*"])
    {
        $clcadenderruacep = new \cl_cadenderruacep;

        $sSql = $clcadenderruacep->sql_query_cepSemCadEnderLocal(
            null,
            implode(",", $campos),
            null,
            "db86_cep = '{$cep}'"
        );

        $oResult = db_query($sSql);

        if (!$oResult) {
            throw new \Exception("Erro ao buscar a rua com base no CEP: {$cep}");
        }

        return \db_utils::fieldsMemory($oResult, 0);
    }

    public function getByCepMunicipio($cep, $campos = ["*"])
    {
        $clcadenderruacep = new \cl_cadenderruacep;
        $cldb_config = new \cl_db_config;

        $sSqlPrefeitura = $cldb_config->sql_query_file(
            null,
            "munic",
            null,
            "prefeitura IS TRUE"
        );

        $rResultPref = db_query($sSqlPrefeitura);

        if (!$rResultPref) {
            throw new \Exception("Erro ao buscar o município da prefeitura.");
        }

        $oResultPref = \db_utils::fieldsMemory($rResultPref, 0);

        $sSql = $clcadenderruacep->sql_query_cepSemCadEnderLocal(
            null,
            implode(",", $campos),
            null,
            "db86_cep = '{$cep}' AND db72_descricao = '{$oResultPref->munic}'"
        );

        $oResult = db_query($sSql);

        if (!$oResult) {
            throw new \Exception("Erro ao buscar a rua com base no CEP: {$cep}");
        }

        return \db_utils::fieldsMemory($oResult, 0);
    }
}
