<?php

namespace ECidade\RecursosHumanos\Pessoal\Factory;

/**
 * Class DirfFactory
 * @author Augusto Berwaldt <augusto.oliveira@dbseller.com.br>
 * @package ECidade\RecursosHumanos\Pessoal\Factory
 */
class DirfArquivoFactory
{
    /**
     * Metodo responsavel por crear Obejeto geracao do arquiv da dirf
     *
     * @param $year
     * @param $layout
     * @return \ArquivoDirf2012|\ArquivoDirf2015|\ArquivoDirf2018
     */
    public static function create($year, $layout)
    {
        switch (true) {
            case ($year >= 2017):
                $oDirfArq = new \ArquivoDirf2018($layout);
                break;
            case ($year < 2017 && $year > 2014):
                $oDirfArq = new \ArquivoDirf2015($layout);
                break;
            default:
                $oDirfArq = new \ArquivoDirf2012($layout);
        }

        return $oDirfArq;
    }

}