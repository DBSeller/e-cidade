<?php

namespace ECidade\Educacao\Escola\FormulaAvaliacao;

use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Mapper\ResultadoPorAreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;

/**
 * Class Atribuido
 * @package ECidade\Educacao\Escola\FormulaAvaliacao
 */
class Atribuido extends FormulaAvaliacao
{
    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return AvaliacaoPorAreaConhecimento|mixed|void
     * @throws Exception
     */
    public function calcularAvaliacoes(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $avaliacaoPorArea = new AvaliacaoPorAreaConhecimento();
        $avaliacaoPorArea->setAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao);
        $avaliacoesCalculo = $this->getAvaliacoesCalcular($areaProcedimentoAvaliacao);

        if ($this->todasDisciplinasAreaNaoPossuemCaraterReprobatorio()) {
            $avaliacaoPorArea->setResultadoAvaliacao('A');
            return $avaliacaoPorArea;
        }

        $amparado = $this->amparadoNoPeriodo($avaliacoesCalculo);
        $avaliacaoPorArea->setAmparado($amparado);
        if ($amparado) {
            return $avaliacaoPorArea;
        }

        $pareceres = [];
        foreach ($avaliacoesCalculo as $avaliacaoAproveitamento) {
            if ($avaliacaoAproveitamento->isAmparado()) {
                continue;
            }

            $valorAproveitamento = $avaliacaoAproveitamento->getValorAproveitamento();
            if ($valorAproveitamento->getAproveitamento() === "") {
                continue;
            }

            $pareceres[] = $valorAproveitamento->getAproveitamento();
        }

        $avaliacaoAproveitamento = array_shift($avaliacoesCalculo);
        $avaliacaoResultadoFinal = $avaliacaoAproveitamento->getDiarioAvaliacaoDisciplina()->getResultadoFinal();
        $avaliacaoPorArea->setResultadoAvaliacao($avaliacaoResultadoFinal->getResultadoAprovacao());

        $avaliacaoPorArea->setAvaliacao(implode("\n", $pareceres));

        return $avaliacaoPorArea;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return ResultadoPorAreaConhecimento|mixed|void
     * @throws Exception
     */
    public function calcularResultado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $resultado = new ResultadoPorAreaConhecimento();
        $resultado->setAreaProcedimentoResultado($areaProcedimentoResultado);
        $resultado->setAmparado($this->resultadoIsAmparado($areaProcedimentoResultado));

        if ($resultado->isAmparado()) {
            return $resultado;
        }

        $pareceres = [];
        $elementosCompoeResultado = $areaProcedimentoResultado->getComposicao();
        foreach ($elementosCompoeResultado as $composicaoResultado) {
            $avaliacaoCompoeResultado = $composicaoResultado->getAreaProcedimentoAvaliacao();
            $avaliacaoArea = $this->getAvaliacaoAreaPorElementoAvaliacao($avaliacaoCompoeResultado);
            $pareceres[] = $avaliacaoArea->getAvaliacao();
        }

        $resultado->setAvaliacao(implode("\n", $pareceres));
        $resultado->setResultadoFrequencia($this->processaResultadoDaFrequencia());

        $diarioAvaliacaoDisciplina = $this->diarioAvaliacaoDisciplina[0];

        $resultado->setResultadoAvaliacao($diarioAvaliacaoDisciplina->getResultadoFinal()->getResultadoAprovacao());

        if ($this->todasDisciplinasAreaNaoPossuemCaraterReprobatorio()) {
            $resultado->setResultadoAvaliacao('A');
        }

        return $resultado;
    }
}
