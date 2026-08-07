<?php

namespace App\Domain\Financeiro\Contabilidade\Factories;

use App\Domain\Financeiro\Contabilidade\Contracts\PlanoContasPcaspInterface;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022\RjMapper;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022\RoMapper;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022\RsMapper;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022\UniaoMapper;

class PlanoContasPcaspFactory
{
    /**
     * @param $plano
     * @param $exercicio
     * @param string $uf
     * @return PlanoContasPcaspInterface
     */
    public static function layout($plano, $exercicio, $uf = '')
    {
        if ($plano === 'uniao') {
            return self::layoutUniao($exercicio);
        }

        return self::layoutUF($uf, $exercicio);
    }

    private static function layoutUniao($exercicio)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return new UniaoMapper();
        }
    }

    private static function layoutUF($uf, $exercicio)
    {
        switch ($uf) {
            case 'RS':
                return self::layoutRS($exercicio);
            case 'RJ':
                return self::layoutRJ($exercicio);
            case 'RO':
                return self::layoutRO($exercicio);
        }
    }

    private static function layoutRS($exercicio)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return new RsMapper();
        }
    }

    private static function layoutRJ($exercicio)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return new RjMapper();
        }
    }

    private static function layoutRO($exercicio)
    {
        switch ($exercicio) {
            case 2022:
            default:
                return new RoMapper();
        }
    }
}
