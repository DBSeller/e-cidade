<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\DecretoService;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\v2022\DecretoService2022;

class DecretoFactory
{
    /**
     * @param $exercicio
     * @param $instituicoes
     * @param $dataInicial
     * @param $dataFinal
     * @return DecretoService|DecretoService2022
     */
    public static function getService($exercicio, $instituicoes, $dataInicial, $dataFinal)
    {
        if ($exercicio < 2022) {
            return new DecretoService($instituicoes, $exercicio, $dataInicial, $dataFinal);
        }

        return new DecretoService2022($instituicoes, $exercicio, $dataInicial, $dataFinal);
    }
}
