<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoVI as AnexoVI2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoVI as AnexoVI2019;

/**
 * Class AnexoVIFactory
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory
 */
final class AnexoVIFactory
{
    /**
     * @param int $ano
     * @return AnexoVI2018|AnexoVI2019
     */
    public static function factory($ano)
    {
        return new AnexoVI2018();
        /*
        switch ($ano) {
            case 2018:
                return new AnexoVI2018();
            case 2019:
                return new AnexoVI2019();
            default:
                return new AnexoVI2019();
        }
        */
    }
}
