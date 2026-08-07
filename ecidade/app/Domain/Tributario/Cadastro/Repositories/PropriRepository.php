<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Propri;

class PropriRepository
{
    private $propri;

    public function __construct()
    {
        $this->propri = new Propri();
    }

    public function getAllByMatricula($matricula)
    {
        $clpropri = new \cl_propri();

        $rsPropri = db_query($clpropri->sql_query($matricula));

        if (!$rsPropri) {
            throw new \Exception($clpropri->erro_msg);
        }

        return \db_utils::getCollectionByRecord($rsPropri);
    }
}
