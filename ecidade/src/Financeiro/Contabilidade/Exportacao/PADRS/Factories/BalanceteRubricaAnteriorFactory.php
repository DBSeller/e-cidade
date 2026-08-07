<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\BalanceteRubricaAnteriorService;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\v2022\BalanceteRubricaAnteriorService2022;

class BalanceteRubricaAnteriorFactory
{

    public static function getService($exercicio, array $instituicoes)
    {
        if ($exercicio < 2021) {
            return new BalanceteRubricaAnteriorService($instituicoes, $exercicio);
        }
        return new BalanceteRubricaAnteriorService2022($instituicoes, $exercicio);
    }
}
