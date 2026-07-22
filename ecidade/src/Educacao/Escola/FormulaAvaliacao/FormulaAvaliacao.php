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

use AvaliacaoAproveitamento;
use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;
use FormaAvaliacao;
use ValorAproveitamentoParecer;

abstract class FormulaAvaliacao implements Formula
{
    /**
     * São as avaliações das disciplinas de uma área de conhecimento
     * @var DiarioAvaliacaoDisciplina[]
     */
    protected $diarioAvaliacaoDisciplina = [];
    /**
     * @var AvaliacaoPorAreaConhecimento[]
     */
    protected $avaliacaoPorAreaConhecimentos = [];



    /**
     * @param DiarioAvaliacaoDisciplina[] $avaliacaoDisciplina
     */
    public function setAvaliacoesDisciplina(array $avaliacaoDisciplina)
    {
        $this->diarioAvaliacaoDisciplina = $avaliacaoDisciplina;
    }

    /**
     * @param AvaliacaoPorAreaConhecimento[] $avaliacaoPorAreaConhecimentos
     */
    public function setAvaliacoesAreaConhecimento(array $avaliacaoPorAreaConhecimentos)
    {
        $this->avaliacaoPorAreaConhecimentos = $avaliacaoPorAreaConhecimentos;
    }

    public function calcularAvaliacoes(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        // TODO: Implement calcular() method.
    }
    /**
     * @inheritDoc
     */
    public function calcularResultado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        // TODO: Implement calcularResultado() method.
    }

    /**
     * Retorna os elementos de avaliação do período de avaliação informado
     *
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return AvaliacaoAproveitamento[]
     * @throws Exception
     */
    protected function getAvaliacoesCalcular(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $avaliacoesCalcular = [];
        foreach ($this->diarioAvaliacaoDisciplina as $diarioAvaliacaoDisciplina) {
            if (!$diarioAvaliacaoDisciplina->getRegencia()->possuiCaracterReprobatorio()) {
                continue;
            }

            $avaliacoes = $diarioAvaliacaoDisciplina->getAvaliacoes();

            foreach ($avaliacoes as $avaliacaoAproveitamento) {
                $ordemElementoDisciplina = (int)$avaliacaoAproveitamento->getElementoAvaliacao()->getOrdemSequencia();
                $ordemElementoArea = (int)$areaProcedimentoAvaliacao->getOrdemElemento();
                if ($ordemElementoDisciplina === $ordemElementoArea) {
                    $avaliacoesCalcular[] = $avaliacaoAproveitamento;
                }
            }
        }

        return $avaliacoesCalcular;
    }

    /**
     * @param AvaliacaoAproveitamento[] $avaliacoesCalculo
     * @return bool
     */
    protected function amparadoNoPeriodo(array $avaliacoesCalculo)
    {
        $numeroAvaliacoes = count($avaliacoesCalculo);
        if ($numeroAvaliacoes === 0) {
            return false;
        }

        $avaliacoesAmparadas = 0;
        foreach ($avaliacoesCalculo as $avaliacaoAproveitamento) {
            if ($avaliacaoAproveitamento->isAmparado()) {
                $avaliacoesAmparadas ++;
            }
        }

        return $numeroAvaliacoes === $avaliacoesAmparadas;
    }

    /**
     * @param AreaProcedimentoAvaliacao $avaliacaoCompoeResultado
     * @return AvaliacaoPorAreaConhecimento
     * @throws Exception
     */
    protected function getAvaliacaoAreaPorElementoAvaliacao(AreaProcedimentoAvaliacao $avaliacaoCompoeResultado)
    {
        foreach ($this->avaliacaoPorAreaConhecimentos as $avaliacaoPorAreaConhecimento) {
            $areaProcedimentoAvaliacao = $avaliacaoPorAreaConhecimento->getAreaProcedimentoAvaliacao();

            if ($avaliacaoCompoeResultado->getCodigo() == $areaProcedimentoAvaliacao->getCodigo()) {
                return $avaliacaoPorAreaConhecimento;
            }
        }

        throw new Exception("Não foi encontrado uma avaliação compatível com a composição do resultado.");
    }

    /**
     * @param FormaAvaliacao $formaAvaliacao
     * @param mixed $avaliacao
     * @return bool
     * @throws Exception
     */
    protected function atingiuMinimo(FormaAvaliacao $formaAvaliacao, $avaliacao)
    {

        $tipo = $formaAvaliacao->getTipo();
        if ($avaliacao instanceof ValorAproveitamentoParecer &&
            !$avaliacao->hasOrdem() &&
            $formaAvaliacao->getTipo() != "NOTA") {
            $tipo = "PARECER";
        }

        switch ($tipo) {
            case 'NOTA':
                return $avaliacao >= $formaAvaliacao->getAproveitamentoMinino();
            case 'NIVEL':
                return $avaliacao->getOrdem() >= $formaAvaliacao->getConceitoMinimo()->iOrdem;
            case 'PARECER':
                return true;
            break;
        }

        throw new Exception("Forma de avaliação desconhecida. @todo ainda falta implementar NIVEL e PARECER");
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return bool
     * @throws Exception
     */
    protected function resultadoIsAmparado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $elementosCompoeResultado = $areaProcedimentoResultado->getComposicao();

        $numeroElementos = count($elementosCompoeResultado);
        $numeroElementosAmparados = 0;
        foreach ($elementosCompoeResultado as $composicaoResultado) {
            $avaliacaoCompoeResultado = $composicaoResultado->getAreaProcedimentoAvaliacao();

            $avaliacaoArea = $this->getAvaliacaoAreaPorElementoAvaliacao($avaliacaoCompoeResultado);
            if ($avaliacaoArea->isAmparado()) {
                $numeroElementosAmparados ++;
            }
        }

        return $numeroElementos === $numeroElementosAmparados;
    }

    /**
     * Retorna o resultado da frequênciaa
     * @return string
     */
    protected function processaResultadoDaFrequencia()
    {
        $resultadosFrequencia = [];
        foreach ($this->diarioAvaliacaoDisciplina as $diarioAvaliacaoDisciplina) {
            $resultadosFrequencia[] = $diarioAvaliacaoDisciplina->getResultadoFinal()->getResultadoFrequencia();
        }

        $resultado = 'A';
        if (in_array('R', $resultadosFrequencia)) {
            $resultado = 'R';
        }

        return $resultado;
    }

    /**
     * Verifica se todas disciplinas da área não possuem carater reprobatório
     *
     * @return bool
     */
    protected function todasDisciplinasAreaNaoPossuemCaraterReprobatorio()
    {
        $totalDisciplinas = count($this->diarioAvaliacaoDisciplina);
        $totalDisciplinasNaoPossuiCaraterReprobatorio = 0;
        foreach ($this->diarioAvaliacaoDisciplina as $diarioAvaliacaoDisciplina) {
            if (!$diarioAvaliacaoDisciplina->getRegencia()->possuiCaracterReprobatorio()) {
                $totalDisciplinasNaoPossuiCaraterReprobatorio ++;
            }
        }

        return $totalDisciplinas === $totalDisciplinasNaoPossuiCaraterReprobatorio;
    }
}
