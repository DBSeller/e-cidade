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

use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Mapper\ResultadoPorAreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;
use ValorAproveitamentoNivel;

/**
 * Class MaiorNivel
 * @package ECidade\Educacao\Escola\FormulaAvaliacao
 */
class MaiorNivel extends FormulaAvaliacao
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

        $maiorNivel = null;

        foreach ($avaliacoesCalculo as $avaliacaoAproveitamento) {
            if ($avaliacaoAproveitamento->isAmparado()) {
                continue;
            }

            $valorAproveitamento = $avaliacaoAproveitamento->getValorAproveitamento();


            if ($valorAproveitamento->getAproveitamento() === "") {
                continue;
            }

            if (is_null($maiorNivel) || $valorAproveitamento->getOrdem() > $maiorNivel->getOrdem()) {
                $maiorNivel = $valorAproveitamento;
            }
        }


        if (is_null($maiorNivel)) {
            return $avaliacaoPorArea;
        }

        $avaliacaoPorArea->setResultadoAvaliacao('R');

        if ($this->atingiuMinimo($areaProcedimentoAvaliacao->getFormaAvaliacao(), $maiorNivel)) {
            $avaliacaoPorArea->setResultadoAvaliacao('A');
        }

        $avaliacaoPorArea->setAvaliacao($maiorNivel->getAproveitamento());

        return $avaliacaoPorArea;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return ResultadoPorAreaConhecimento
     * @throws Exception
     */
    public function calcularResultado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $resultado = new ResultadoPorAreaConhecimento();
        $resultado->setAreaProcedimentoResultado($areaProcedimentoResultado);
        $resultado->setAmparado($this->resultadoIsAmparado($areaProcedimentoResultado));

        if ($resultado->isAmparado()) {
            $resultado->setResultadoAvaliacao('A');
            return $resultado;
        }

        $maiorNivel = null;

        $elementosCompoeResultado = $areaProcedimentoResultado->getComposicao();
        foreach ($elementosCompoeResultado as $composicaoResultado) {
            $avaliacaoCompoeResultado = $composicaoResultado->getAreaProcedimentoAvaliacao();
            $avaliacaoArea = $this->getAvaliacaoAreaPorElementoAvaliacao($avaliacaoCompoeResultado);



            if ($avaliacaoArea->isAmparado() || $avaliacaoArea->getAvaliacao() == '') {
                continue;
            }

            $conceitos = $avaliacaoArea->getAreaProcedimentoAvaliacao()->getFormaAvaliacao()->getConceitos();

            $avaliacao = $avaliacaoArea->getAvaliacao();

            $saida = array_filter($conceitos, function ($var) use ($avaliacao) {
                return $var->sConceito === $avaliacao;
            });

            $nivel = array_shift($saida);

            if (is_null($maiorNivel) || $nivel->iOrdem > $maiorNivel->iOrdem) {
                $maiorNivel = $nivel;
            }
        }

        if (is_null($maiorNivel)) {
            return $resultado;
        }

        $valorAproveitamentoNivel = new ValorAproveitamentoNivel($maiorNivel->sConceito, $maiorNivel->iOrdem);

        $resultado->setAvaliacao($maiorNivel->sConceito);

        if ($this->atingiuMinimo($areaProcedimentoResultado->getFormaAvaliacao(), $valorAproveitamentoNivel)) {
            $resultado->setResultadoAvaliacao('A');
        }

        if ($this->todasDisciplinasAreaNaoPossuemCaraterReprobatorio()) {
            $resultado->setResultadoAvaliacao('A');
        }

        $resultado->setResultadoFrequencia($this->processaResultadoDaFrequencia());

        return $resultado;
    }
}
