<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Educacao\Escola\FormulaAvaliacao;

use ArredondamentoNota;
use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Mapper\ResultadoPorAreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;

/**
 * Class Soma
 * @package ECidade\Educacao\Escola\FormulaAvaliacao
 */
class Soma extends FormulaAvaliacao
{
    /**
     * Calcula um elemento de avaliação por vez... 1º Bimestre/Trimestre/Semestre...
     *
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return AvaliacaoPorAreaConhecimento
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

        $nota = 0;
        $notaMaximaDisciplinasCalculadas = 0;
        $calcularNota = false;
        foreach ($avaliacoesCalculo as $avaliacaoAproveitamento) {
            if ($avaliacaoAproveitamento->isAmparado()) {
                continue;
            }

            $maiorNota = $avaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getMaiorValor();
            $notaMaximaDisciplinasCalculadas += $maiorNota;
            $valorAproveitamento = $avaliacaoAproveitamento->getValorAproveitamento();
            if ($valorAproveitamento->getAproveitamento() === "") {
                continue;
            }

            $calcularNota = true;
            $nota += $valorAproveitamento->getAproveitamento();
        }

        $formaAvaliacao = $areaProcedimentoAvaliacao->getFormaAvaliacao();
        $notaMaximaPeriodo = $formaAvaliacao->getMaiorValor();

        $avaliacao = null;
        if ($calcularNota) {
            $avaliacao = ($nota * $notaMaximaPeriodo) / $notaMaximaDisciplinasCalculadas;
        }

        if ($this->atingiuMinimo($formaAvaliacao, $avaliacao)) {
            $avaliacaoPorArea->setResultadoAvaliacao('A');
        }

        $avaliacaoPorArea->setAvaliacao($avaliacao);
        return $avaliacaoPorArea;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return ResultadoPorAreaConhecimento
     * @throws Exception
     */
    public function calcularResultado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $diarioAvaliacaoDisciplina = $this->diarioAvaliacaoDisciplina[0]; // pega a primeira disciplina
        $ano = $diarioAvaliacaoDisciplina->getDiario()->getTurma()->getCalendario()->getAnoExecucao();
        $ordemPeriodosProporcionalidade = $diarioAvaliacaoDisciplina->getOrdemPeriodosAplicaProporcionalidade();
        $utilizaProporcionalidade = $this->utilizaProporcionalidade($areaProcedimentoResultado);

        $resultado = new ResultadoPorAreaConhecimento();
        $resultado->setAreaProcedimentoResultado($areaProcedimentoResultado);
        $resultado->setAmparado($this->resultadoIsAmparado($areaProcedimentoResultado));

        if ($resultado->isAmparado()) {
            return $resultado;
        }

        $somaMaiorNotaPeriodosAvaliados = 0;
        $nota = 0;
        $elementosCompoeResultado = $areaProcedimentoResultado->getComposicao();

        foreach ($elementosCompoeResultado as $composicaoResultado) {
            $avaliacaoCompoeResultado = $composicaoResultado->getAreaProcedimentoAvaliacao();

            $avaliacaoArea = $this->getAvaliacaoAreaPorElementoAvaliacao($avaliacaoCompoeResultado);
            if ($avaliacaoArea->isAmparado()) {
                continue;
            }

            $areaProcedimentoAvaliacao = $avaliacaoArea->getAreaProcedimentoAvaliacao();
            if ($utilizaProporcionalidade && !empty($ordemPeriodosProporcionalidade)) {
                if (!in_array($areaProcedimentoAvaliacao->getOrdemElemento(), $ordemPeriodosProporcionalidade)) {
                    continue;
                }
            }

            $somaMaiorNotaPeriodosAvaliados += $areaProcedimentoAvaliacao->getFormaAvaliacao()->getMaiorValor();
            $nota += $avaliacaoArea->getAvaliacao();
        }

        $maiorNotaResultado = $areaProcedimentoResultado->getFormaAvaliacao()->getMaiorValor();
        $nota = ($nota * $maiorNotaResultado) / $somaMaiorNotaPeriodosAvaliados;

        $nota = ArredondamentoNota::arredondar($nota, $ano);
        $resultado->setAvaliacao($nota);
        if ($this->atingiuMinimo($areaProcedimentoResultado->getFormaAvaliacao(), $nota)) {
            $resultado->setResultadoAvaliacao('A');
        }

        if ($this->todasDisciplinasAreaNaoPossuemCaraterReprobatorio()) {
            $resultado->setResultadoAvaliacao('A');
        }

        $resultado->setResultadoFrequencia($this->processaResultadoDaFrequencia());

        return $resultado;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return bool
     */
    private function utilizaProporcionalidade(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $resultados = $areaProcedimentoResultado->getAreaProcedimento()->getProcedimento()->getResultados();

        $utilizaProporcionalidade = false;
        foreach ($resultados as $resultado) {
            if ($resultado->geraResultadoFinal() && $resultado->utilizaProporcionalidade()) {
                $utilizaProporcionalidade = true;
            }
        }

        return $utilizaProporcionalidade;
    }
}
