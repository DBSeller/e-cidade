<?php

namespace App\Domain\Financeiro\Contabilidade\Factories;

use App\Domain\Financeiro\Contabilidade\Contracts\AnexosFactoryInterface;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\LRF\RGF\AnexoCinco\AnexoCincoService;

class AnexoCincoRgfFactory extends AnexosFactory implements AnexosFactoryInterface
{
    public static function getDadosView($exercicio)
    {
        $programa = self::getProgramaRelatorio($exercicio);
        $relatorio = self::getCodigoRelatorio($exercicio);
        $rota = self::getRota($exercicio);
        return ['relatorio' => $relatorio, 'programa' => $programa, 'rota' => $rota];
    }

    public static function getCodigoRelatorio($exercicio)
    {
        return 266;
    }

    private static function getRota($exercicio)
    {
        return 'financeiro/contabilidade/relatorio/rgf/anexo-5';
    }

    /**
     * Service para processamento e impressão do relatório
     * @param $exercicio
     * @param $filtros
     * @return AnexoCincoService
     */
    public static function getService($exercicio, $filtros)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return new AnexoCincoService($filtros);
        }
    }
}
