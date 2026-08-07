<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptubase;

class IptubaseRepository
{
    private $iptubase;

    public function __construct()
    {
        $this->iptubase = new Iptubase();
    }

    public function getDadosRegImovByMatric($matricula)
    {
        $cliptubase = new \cl_iptubase();

        $rsConsultaDadosMatric = $cliptubase->sql_record($cliptubase->sql_query_regmovel($matricula));

        return \db_utils::fieldsMemory($rsConsultaDadosMatric, 0);
    }

    public function getCamposDadosRegImovByMatric($matricula, $campos)
    {
        $cliptubase = new \cl_iptubase();

        $rsConsultaDadosMatric = $cliptubase->sql_record($cliptubase->sql_query_regmovel($matricula, $campos));

        return \db_utils::fieldsMemory($rsConsultaDadosMatric, 0);
    }
}
