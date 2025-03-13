<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptuender;

class IptuenderRepository
{
    private $iptuender;

    public function __construct()
    {
        $this->iptuender = new Iptuender();
    }

    public function getByMatricu($matric)
    {
        return $this->iptuender->where(
            "j43_matric",
            "=",
            $matric
        )->first();
    }
}
