<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout\AnexoIII as Layout2020;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout
 */
class AnexoIII
{

    /**
     * @param $ano
     * @return Layout2020
     * @throws \Exception
     */
    public static function getInstance($ano)
    {
        if ($ano >= 2020) {
            return new Layout2020();
        } else {
            throw new \Exception("Não existe layout para o Anexo III no ano {$ano}.");
        }
    }
}
