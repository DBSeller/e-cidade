<?php


namespace ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao;

use AvaliacaoPeriodica;
use ResultadoAvaliacaoComposicao;

class ResultadoAvaliacaoComposicaoResource
{
    /**
     * @param ResultadoAvaliacaoComposicao[] $resultadosAvaliacaoComposicao
     * @return array
     */
    public static function toArray(array $resultadosAvaliacaoComposicao)
    {
        $elementos = [];
        foreach ($resultadosAvaliacaoComposicao as $resultadoAvaliacaoComposicao) {
            $elementoAvaliacao = $resultadoAvaliacaoComposicao->getElementoAvaliacao();

            if ($elementoAvaliacao instanceof AvaliacaoPeriodica) {
                $elemento = AvaliacaoPeriodicaResource::toStdClass($elementoAvaliacao);
            } else {
                $elemento = ResultadoAvaliacaoResource::toStdClass($elementoAvaliacao);
            }
            $elementos[] = (object) [
                'elemento' => $elemento,
                'obrigatorio' => $resultadoAvaliacaoComposicao->isObrigatorio(),
                'peso' => $resultadoAvaliacaoComposicao->getPeso(),
                'ordem' => $resultadoAvaliacaoComposicao->getOrdem(),
            ];
        }

        return $elementos;
    }
}
