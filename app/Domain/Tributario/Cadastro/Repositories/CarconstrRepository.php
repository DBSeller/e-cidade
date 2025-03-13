<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Carconstr;

class CarconstrRepository
{
    private $carconstr;

    public function __construct()
    {
        $this->carconstr = new Carconstr();
    }

    public function getCaracterSelecionadaByMatricConstrucao($matricula, $construcao, $grupo = null)
    {
        $cl_carconstr = new \cl_carconstr();

        $rResult = db_query($cl_carconstr->sql_caracteristicas_selecionadas($matricula, $construcao, $grupo));

        if (!$rResult) {
            throw new \Exception("Erro ao buscar as caracteristicas selecionadas da construção {$construcao}.");
        }

        if (!empty($grupo)) {
            return \db_utils::fieldsMemory($rResult, 0);
        }

        return \db_utils::getCollectionByRecord($rResult);
    }
}
