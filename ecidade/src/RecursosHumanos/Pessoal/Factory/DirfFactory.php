<?php

namespace ECidade\RecursosHumanos\Pessoal\Factory;

use ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2017\Dirf as Dirf2017;
use ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2018\Dirf as Dirf2018;

/**
 * Class DirfFactory
 * @author Augusto Berwaldt <augusto.oliveira@dbseller.com.br>
 * @package ECidade\RecursosHumanos\Pessoal\Factory
 */
class DirfFactory
{

    /**
     * Metodo responsavel por crear Obejeto geracao do arquiv da dirf
     *
     * @param $year integer
     * @param $cnpj string
     * @return Dirf|Dirf2012|Dirf2015|Dirf2018
     */
    public static function create($year, $cnpj)
    {
        switch (true) {
            case ($year >= 2017):
                $oDirf = new Dirf2018($year, $cnpj);
                break;
            case ($year <= 2014 && $year > 2012):
                $oDirf = new Dirf2015($year, $cnpj);
                break;
            case ($year < 2014 && $year >= 2012):
                $oDirf = new Dirf2012($year, $cnpj);
                break;
            default:
                $oDirf = new \Dirf($year, $cnpj);
                $oDirf->setCodigoLayout(93);
        }

        return $oDirf;
    }

}