<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Cadban;

class CadbanRepository
{
    private $cadban;

    public function __construct()
    {
        $this->cadban = new Cadban();
    }

    public function getBancoAgenciaTef()
    {
        return $this->cadban->where(
            "k15_bancotef",
            "=",
            "t"
        )->first([
            "k15_codbco",
            "k15_codage"
        ]);
    }
}
