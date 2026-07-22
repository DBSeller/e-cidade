<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP;

use BalancoPatrimonialDCASP2015;
use BalancoPatrimonialDCASP2017;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\BalancoPatrimonialDCASP2018;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\BalancoPatrimonialDCASP2019;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\BalancoPatrimonialDCASP2020;
use InvalidArgumentException;

final class RelatorioBalancoPatrimonialFactory
{
    public static function getInstance($ano, $periodo)
    {
        if ($ano >= 2015 && $ano <= 2016) {
            return new BalancoPatrimonialDCASP2015($ano, BalancoPatrimonialDCASP2015::CODIGO_RELATORIO, $periodo);
        }

        if ($ano == 2017) {
            return new BalancoPatrimonialDCASP2017($ano, BalancoPatrimonialDCASP2017::CODIGO_RELATORIO, $periodo);
        }

        if ($ano == 2018) {
            return new BalancoPatrimonialDCASP2018($ano, BalancoPatrimonialDCASP2018::CODIGO_RELATORIO, $periodo);
        }

        if ($ano == 2019) {
            return new BalancoPatrimonialDCASP2019($ano, BalancoPatrimonialDCASP2019::CODIGO_RELATORIO, $periodo);
        }
        if ($ano >= 2020) {
            return new BalancoPatrimonialDCASP2020($ano, BalancoPatrimonialDCASP2020::CODIGO_RELATORIO, $periodo);
        }


        throw new InvalidArgumentException("Nenhum relatório encontrado para o ano de {$ano}.");
    }
}
