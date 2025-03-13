<?php
namespace App\Domain\RecursosHumanos\Pessoal\Repository\Helper;

class CompetenciaHelper
{
    /**
     * Legacy
     * @return \DBCompetencia
     */
    public static function get($ano = null, $mes = null)
    {
        if (!empty($ano) && !empty($mes)) {
            return new \DBCompetencia($ano, $mes);
        } else {
            return \DBPessoal::getCompetenciaFolha();
        }
    }

    public static function getFormatada($ano = null, $mes = null)
    {
        $competencia = self::get($ano, $mes);
        $competencia->dataInicial = "{$competencia->getAno()}-{$competencia->getMes()}-01";
        $competencia->dataFinal = "{$competencia->getAno()}-{$competencia->getMes()}-{$competencia->getUltimoDia()}";

        return $competencia;
    }
}
