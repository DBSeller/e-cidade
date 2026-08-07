<?php
namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020\BalanceteReceitaAnteriorBuilder2020;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2022\BalanceteReceitaAnterior;

class BalanceteReceitaAnteriorBuilder2022 extends BalanceteReceitaAnteriorBuilder2020
{
    protected function create()
    {
        $this->layout = new BalanceteReceitaAnterior();
    }
}
