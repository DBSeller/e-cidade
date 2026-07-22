<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptucale;

class IptucaleRepository
{
    private $iptucale;

    public function __construct()
    {
        $this->iptucale = new Iptucale();
    }

    public function getByAnoMatricula($ano, $matricula)
    {
        return $this->iptucale->where(
            "j22_anousu",
            "=",
            $ano
        )->where(
            "j22_matric",
            "=",
            $matricula
        )->get();
    }
}
