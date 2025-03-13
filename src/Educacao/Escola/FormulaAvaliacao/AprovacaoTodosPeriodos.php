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
use ValorAproveitamentoNivel;

/**
 * Class MaiorNivel
 * @package ECidade\Educacao\Escola\FormulaAvaliacao
 */
class AprovacaoTodosPeriodos extends FormulaAvaliacao
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
        return new AvaliacaoPorAreaConhecimento();
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

        $resultado = new ResultadoPorAreaConhecimento();
        $resultado->setAreaProcedimentoResultado($areaProcedimentoResultado);
        $resultado->setAmparado($this->resultadoIsAmparado($areaProcedimentoResultado));

        if ($resultado->isAmparado()) {
            return $resultado;
        }

        $elementosCompoeResultado = $areaProcedimentoResultado->getComposicao();
        $menorNota = 999;
        foreach ($elementosCompoeResultado as $composicaoResultado) {
            $avaliacaoCompoeResultado = $composicaoResultado->getAreaProcedimentoAvaliacao();
            $avaliacaoArea = $this->getAvaliacaoAreaPorElementoAvaliacao($avaliacaoCompoeResultado);
            if ($avaliacaoArea->isAmparado()) {
                continue;
            }

            if ($menorNota > $avaliacaoArea->getAvaliacao()) {
                $menorNota = $avaliacaoArea->getAvaliacao();
            }

            if ($avaliacaoArea->getResultadoAvaliacao() == "R") {
                $resultado->setResultadoAvaliacao('R');
            }
        }
        $menorNota = $menorNota === 999 ? 0 : $menorNota;

        $nota = ArredondamentoNota::arredondar($menorNota, $ano);
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
}
