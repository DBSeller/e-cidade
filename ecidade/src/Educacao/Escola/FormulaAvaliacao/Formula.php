<?php


namespace ECidade\Educacao\Escola\FormulaAvaliacao;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;

/**
 * Interface Formula
 * @package ECidade\Educacao\Escola\FormulaAvaliacao
 */
interface Formula
{
    /**
     * @param DiarioAvaliacaoDisciplina[] $avaliacaoDisciplina
     */
    public function setAvaliacoesDisciplina(array $avaliacaoDisciplina);

    /**
     * @param AvaliacaoPorAreaConhecimento[] $avaliacaoPorAreaConhecimentos
     * @return AvaliacaoPorAreaConhecimento
     */
    public function setAvaliacoesAreaConhecimento(array $avaliacaoPorAreaConhecimentos);

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return mixed
     */
    public function calcularAvaliacoes(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao);

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return mixed
     */
    public function calcularResultado(AreaProcedimentoResultado $areaProcedimentoResultado);
}
