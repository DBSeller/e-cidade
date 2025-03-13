<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoXIII as Layout2018;
use Exception;

/**
 * Class AnexoXIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout
 */
class AnexoXIII
{
    /**
     * @param $ano
     * @return Layout2018
     * @throws Exception
     */
    public static function getInstance($ano)
    {
        if ($ano >= 2018) {
                return new Layout2018();
        } else {
                throw new Exception("Não existe layout para o Anexo XIII no ano {$ano}.");
        }
    }
}
