<?php


namespace App\Domain\Financeiro\Contabilidade\Factories;

use ECidade\Configuracao\Opcao\Model\Opcao as OpcaoModel;
use ECidade\Configuracao\Opcao\Opcao;
use Exception;

abstract class AnexosFactory
{
    /**
     * view default do relatório
     * @param $exercicio
     * @return string
     */
    public static function getProgramaRelatorio($exercicio)
    {
        return 'pla2_anexos_rreo001.php';
    }

    /**
     * @param $exercicio
     * @return string
     * @throws Exception
     */
    public static function getModelo($exercicio)
    {
        $opcao = static::getOpcao($exercicio, static::OPCAO_MODELO);
        return $opcao->getValor();
    }

    /**
     * Usado apenas nos modelos configurados por parâmetro como os anexos 1 da RGF e 3 RREO
     * @param $exercicio
     * @param $modelo
     * @return OpcaoModel
     * @throws Exception
     */
    protected static function getOpcao($exercicio, $modelo)
    {
        return Opcao::get($modelo, $exercicio);
    }
}
