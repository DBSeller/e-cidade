<?php

namespace App\Domain\Financeiro\Contabilidade\Factories;

use App\Domain\Financeiro\Contabilidade\Contracts\AnexosFactoryInterface;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\LRF\RREO\AnexoUm\AnexoUmService;

class AnexoUmFactory extends AnexosFactory implements AnexosFactoryInterface
{

    /**
     * @inheritDoc
     */
    public static function getDadosView($exercicio)
    {
        $programa = self::getProgramaRelatorio($exercicio);
        $relatorio = self::getCodigoRelatorio($exercicio);
        $rota = 'financeiro/contabilidade/relatorio/rreo/anexo-1';
        return ['relatorio' => $relatorio, 'programa' => $programa, 'rota' => $rota];
    }

    public static function getCodigoRelatorio($exercicio)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return 268;
        }
    }

    /**
     * @param $exercicio
     * @param $filtros
     * @return AnexoUmService
     */
    public static function getService($exercicio, $filtros)
    {
        return new AnexoUmService($filtros);
    }
}
