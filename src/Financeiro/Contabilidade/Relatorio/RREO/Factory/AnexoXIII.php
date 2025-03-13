<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoXIII as Anexo2018;
use Exception;

/**
 * Class AnexoXIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory
 */
class AnexoXIII
{
    /**
     * @param $ano
     * @param $periodo
     * @return Anexo2018
     * @throws Exception
     */
    public static function getInstance($ano, $periodo)
    {
        if ($ano >= 2020) {
            return new \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoXIII($ano, $periodo);
        } elseif ($ano >= 2018) {
                return new Anexo2018($ano, $periodo);
        } else {
                throw new Exception("Não foi encontrado Anexo XIII para o ano de {$ano}.");
        }
    }
}
