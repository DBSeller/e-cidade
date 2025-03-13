<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptucalc;

class IptucalcRepository
{
    private $iptucalc;

    public function __construct()
    {
        $this->iptucalc = new Iptucalc();
    }

    public function getByAnoMatricula($ano, $matricula)
    {
        return $this->iptucalc->where(
            "j23_anousu",
            "=",
            $ano
        )->where(
            "j23_matric",
            "=",
            $matricula
        )->firstOrFail();
    }
}
