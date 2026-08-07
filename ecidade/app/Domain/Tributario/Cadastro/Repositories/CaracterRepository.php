<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Caracter;

class CaracterRepository
{
    private $caracter;

    public function __construct()
    {
        $this->caracter = new Caracter();
    }

    public function getByGrupo($codigo)
    {
        return $this->caracter->where(
            "j31_grupo",
            "=",
            $codigo
        )->get();
    }
}
