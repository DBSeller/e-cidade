<?php

namespace App\Domain\Financeiro\Contabilidade\Factories;

use Exception;

class AnexoUmRgfFactory extends AnexosFactory
{
    const OPCAO_MODELO = 'modelo_anexo_1_rgf';

    /**
     * @param $exercicio
     * @return array
     * @throws Exception
     */
    public static function getDadosView($exercicio)
    {
        $programa = self::getProgramaRelatorio($exercicio);
        $relatorio = self::getCodigoRelatorio($exercicio);
        $rota = self::getRota($exercicio);
        return ['relatorio' => $relatorio, 'programa' => $programa, 'rota' => $rota];
    }

    /**
     * @param $exercicio
     * @return int
     * @throws Exception
     */
    public static function getCodigoRelatorio($exercicio)
    {
        $opcao = static::getOpcao($exercicio, static::OPCAO_MODELO);

        switch ($opcao->getValor()) {
            case 'in13':
                return static::getCodigoRelatorioInRS($exercicio);
            case 'mdf':
                return static::getCodigoRelatorioMDF($exercicio);
            default:
                throw new Exception("Não foi implementado o modelo para configuração atual.");
        }
    }

    /**
     * @param $exercicio
     * @return int
     */
    private static function getCodigoRelatorioMDF($exercicio)
    {
        switch ($exercicio) {
            case 2021:
                return 260;
            default:
                return 260;
        }
    }

    /**
     * @param $exercicio
     * @return int
     */
    private static function getCodigoRelatorioInRS($exercicio)
    {
        switch ($exercicio) {
            case 2021:
                return 261;
            default:
                return 261;
        }
    }

    private static function getRota($exercicio)
    {
        $opcao = static::getOpcao($exercicio, static::OPCAO_MODELO);

        switch ($opcao->getValor()) {
            case 'in13':
                return 'financeiro/contabilidade/relatorio/rgf/anexo-1-in-rs';
            case 'mdf':
                return 'financeiro/contabilidade/relatorio/rgf/anexo-1-mdf';
            default:
                throw new Exception("Não foi implementado o modelo para configuração atual.");
        }
    }
}
