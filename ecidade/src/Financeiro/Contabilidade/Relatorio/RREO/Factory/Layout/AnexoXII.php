<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 13/03/2019
 * Time: 08:07
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout\AnexoXII as Layout2019;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout\AnexoXII as Layout2020;
use Exception;

class AnexoXII
{

    /**
     * @param $ano
     * @return Layout2018
     * @throws Exception
     */
    public static function getInstance($ano)
    {
        if ($ano == 2019) {
            return new Layout2019();
        } elseif ($ano >= 2020) {
            return new Layout2020();
        } else {
            throw new Exception("Não existe layout para o Anexo XIII no ano {$ano}.");
        }
    }
}
