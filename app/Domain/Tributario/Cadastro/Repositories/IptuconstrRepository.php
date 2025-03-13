<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptuconstr;

class IptuconstrRepository
{
    private $iptuconstr;

    public function __construct()
    {
        $this->iptuconstr = new Iptuconstr();
    }

    public function getByMatric($matricula, $campos = ["*"])
    {
        return $this->iptuconstr->where(
            "j39_matric",
            "=",
            $matricula
        )->get($campos);
    }
}
