<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Cargrup;

class CargrupRepository
{
    private $cargrup;

    public function __construct()
    {
        $this->cargrup = new Cargrup();
    }

    public function getByGrupo($grupo)
    {
        return $this->cargrup->where(
            "j32_grupo",
            "=",
            $grupo
        )->first();
    }
}
