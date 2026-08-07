<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\v2022;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022\BalanceteRubricaAnteriorBuilder2022;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\BalanceteRubricaAnteriorService;

class BalanceteRubricaAnteriorService2022 extends BalanceteRubricaAnteriorService
{
    protected function getBuilder()
    {
        return new BalanceteRubricaAnteriorBuilder2022();
    }
}
