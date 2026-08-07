<?php

namespace App\Domain\Saude\ESF\Factories;

use App\Domain\Saude\ESF\Services\IndicadorUmService;
use App\Domain\Saude\ESF\Services\IndicadorDesempenhoService;

/**
 * @package App\Domain\Saude\ESF\Factories
 */
class IndicadoresDesempenhoFactory
{
    /**
     * @param integer $tipo
     * @return IndicadorDesempenhoService
     */
    public static function getService($tipo)
    {
        switch ($tipo) {
            case IndicadorDesempenhoService::UM:
                return new IndicadorUmService;
            default:
                throw new \Exception('Indicador de desempenho não configurado! Selecione um tipo válido.');
        }
    }
}
