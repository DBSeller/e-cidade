<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\BalanceteReceitaAnteriorService;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\v2022\BalanceteReceitaAnteriorService2022;

class BalanceteReceitaAnteriorFactory
{
    /**
     * @param $exercicio
     * @param array $instituicoes
     * @return BalanceteReceitaAnteriorService|BalanceteReceitaAnteriorService2022
     */
    public static function getService($exercicio, array $instituicoes)
    {
        if ($exercicio < 2021) {
            return new BalanceteReceitaAnteriorService($instituicoes, $exercicio);
        }

        return new BalanceteReceitaAnteriorService2022($instituicoes, $exercicio);
    }
}
