<?php

namespace App\Domain\Patrimonial\Protocolo\Repository;

use App\Domain\Patrimonial\Protocolo\Model\Cgm;

class CgmRepository
{
    private $cgm;

    public function __construct()
    {
        $this->cgm = new Cgm();
    }

    public function getByNumcgm($numcgm)
    {
        return $this->cgm->where(
            "z01_numcgm",
            "=",
            $numcgm
        )->first();
    }

    public function getByCpfCnpj($cpfCnpj)
    {
        return $this->cgm->where(
            "z01_cgccpf",
            "=",
            $cpfCnpj
        )->get();
    }
}
