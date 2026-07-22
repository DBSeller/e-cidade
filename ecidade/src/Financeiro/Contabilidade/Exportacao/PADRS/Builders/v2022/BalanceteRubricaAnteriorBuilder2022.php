<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020\BalanceteRubricaAnteriorBuilder2020;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2022\BalanceteRubricaAnterior;

class BalanceteRubricaAnteriorBuilder2022 extends BalanceteRubricaAnteriorBuilder2020
{
    protected function create()
    {
        $this->layout = new BalanceteRubricaAnterior();
    }
}
