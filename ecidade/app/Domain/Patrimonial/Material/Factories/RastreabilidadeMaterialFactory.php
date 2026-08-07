<?php

namespace App\Domain\Patrimonial\Material\Factories;

use App\Domain\Patrimonial\Material\Services\RastreabilidadeMaterialService;
use App\Domain\Saude\Farmacia\Services\RastreabilidadeMedicamentoService;

class RastreabilidadeMaterialFactory
{
    const MEDICAMENTO = 1;

    /**
     * @param integer $tipo
     * @return \App\Domain\Patrimonial\Material\Services\RastreabilidadeMaterialService
     */
    public static function getService($tipo)
    {
        switch ($tipo) {
            case self::MEDICAMENTO:
                return new RastreabilidadeMedicamentoService;
            default:
                return new RastreabilidadeMaterialService;
        }
    }
}
