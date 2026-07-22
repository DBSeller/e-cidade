<?php

namespace App\Domain\Configuracao\Departamento\Repositories;

use App\Domain\Configuracao\Departamento\Models\Departamento;

class DepartamentoRepository
{
    private $dbDepart;

    public function __construct()
    {
        $this->dbDepart = new Departamento();
    }

    public function getByCodigo($codigo)
    {
        return $this->dbDepart->where(
            "coddepto",
            "=",
            $codigo
        )->first();
    }
}
