<?php

namespace App\Domain\Financeiro\Planejamento\Factories;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Outros\PrevisaoRclLdo;
use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Outros\PrevisaoRclLoa;
use Exception;

class OutrosAnexosRclFactory
{
    /**
     * @param $exercicio
     * @return array
     */
    public static function getDadosView($exercicio)
    {
        $programa = self::getProgramaRelatorio();
        $relatorio = self::getCodigoRelatorio($exercicio);
        $rota = self::getRota($exercicio);
        return ['relatorio' => $relatorio, 'programa' => $programa, 'rota' => $rota];
    }

    /**
     * @param $exercicio
     * @return int
     */
    public static function getCodigoRelatorio($exercicio)
    {
        switch ($exercicio) {
            case 2022:
                return 267;
            default:
                return 267;
        }
    }
        
    /**
     * getRota
     *
     * @param $exercicio
     * @return string
     */
    private static function getRota($exercicio)
    {
        switch ($exercicio) {
            case 2022:
                return 'financeiro/planejamento/relatorios/previsao-rcl-outros-anexos';
            default:
                return 'financeiro/planejamento/relatorios/previsao-rcl-outros-anexos';
        }
    }

    /**
     * view default do relatório
     * @param $exercicio
     * @return string
     */
    public static function getProgramaRelatorio()
    {
        return 'pla2_planejamento_previsao_rcl001.php';
    }
    
    /**
     * Gera a instância compatível com o tipo de relatório recebido(LDO,LOA)
     *
     * @param string $tipo
     * @return PrevisaoRclLdo|PrevisaoRclLoa
     * @throws Exception
     */
    public static function getModeloRelatorio($tipo)
    {
        switch ($tipo) {
            case 'LDO':
                return new PrevisaoRclLdo();
            case 'LOA':
                return new PrevisaoRclLoa();
            default:
                throw new Exception('Tipo de planejamento não permitido para o relatório escolhido.');
        }
    }
}
