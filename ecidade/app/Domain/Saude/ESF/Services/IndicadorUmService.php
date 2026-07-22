<?php

namespace App\Domain\Saude\ESF\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Domain\Saude\ESF\Relatorios\IndicadorUmPdf;
use App\Domain\Saude\ESF\Services\IndicadorDesempenhoService;
use App\Domain\Saude\ESF\Builders\IndicadorUmBuilder;

/**
 * ### Indicador de Desempenho 01
 * - Proporção de gestantes com pelo menos 6 consultas pré-natal (PN) realizadas,
 * sendo a primeira até a 16ª semana de gestação.
 * @package App\Domain\Saude\ESF\Services
 */
class IndicadorUmService extends IndicadorDesempenhoService
{
    /**
     * @param array $dados
     * @return IndicadorUmPDf
     */
    protected function getRelatorio(array $dados)
    {
        return new IndicadorUmPdf($dados);
    }

    /**
     * @param Collection $atendimentos
     * @return array
     */
    protected function processar(Collection $atendimentos)
    {
        $builder = new IndicadorUmBuilder;
        $builder->setDados($atendimentos)->setPeriodoFim($this->getPeriodoFim());
        
        return $builder->build();
    }

    /**
     * @return array
     */
    protected function getFiltros()
    {
        return ['s170_problema' => 12];
    }
}
