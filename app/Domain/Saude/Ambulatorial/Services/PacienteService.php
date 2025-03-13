<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Saude\Ambulatorial\Models\CartaoSus;
use App\Domain\Saude\Ambulatorial\Models\CgsUnidade;

/**
 * @package App\Domain\Saude\Ambulatorial\Services
 */
class PacienteService
{
    /**
     * @param CgsUnidade $paciente
     * @return CartaoSus
     */
    public static function getCartaoSus(CgsUnidade $paciente)
    {
        $cns = $paciente->cgs->cartaoSusDefinitivo;
        if (is_null($cns)) {
            return $paciente->cgs->cartoesSus()->first();
        }

        return $cns;
    }
}
