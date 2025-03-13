<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoVI as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoVI as Anexo2019;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoVI as Anexo2020;

/**
 * Class AnexoVI
 * Retorna uma instância do Relatório
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory
 */
class AnexoVI
{
    /**
     * @param $ano
     * @param $periodo
     * @return Anexo2018|Anexo2019|Anexo2020
     */
    public static function getInstance($ano, $periodo)
    {
        if ($ano >= 2020) {
            return new Anexo2020($ano, Anexo2020::CODIGO_RELATORIO, $periodo);
        }

        if ($ano >= 2019) {
            return new Anexo2019($ano, Anexo2019::CODIGO_RELATORIO, $periodo);
        }

        if ($ano >= 2018) {
            return new Anexo2018($ano, Anexo2018::CODIGO_RELATORIO, $periodo);
        }
    }
}
