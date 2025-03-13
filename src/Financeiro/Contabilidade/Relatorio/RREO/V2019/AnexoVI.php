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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoII;

class AnexoVI extends \RelatoriosLegaisBase
{
    /**
     * Código do Relatório no E-Cidade
     * @type integer
     */
    const CODIGO_RELATORIO = 192;

    /**
     * @return array
     * @throws \Exception
     * @throws \ParameterException
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $this->carregarDadosRelatorio();
        $this->processarRestosAPagar();
        $this->processarLinhasComIndicadorDeSuperavit();
        $this->processarTotalizadoresCalculoResultadoNominal();
        foreach (array(56, 60, 76, 77) as $linha) {
            $this->processarFormulaDaLinha($linha);
        }

        return $this->aLinhasConsistencia;
    }

    /**
     * Carrega os dados do relatório legal base
     */
    protected function carregarDadosRelatorio()
    {
        parent::getDados(true);
    }

    /**
     * Executa os restos a pagar para as linhas e colunas definidas
     * @throws \Exception
     * @throws \ParameterException
     */
    protected function processarRestosAPagar()
    {

        $linhasDeRestos = array(41, 42, 43, 46, 48, 49, 50, 51, 52);
        $colunasParaProcessar = array(4 => '#vlrpag', 5 => '#vlrliq', 6 => '#vlrpagnproc');
        /*
         * configura a formula a ser utilizada para as linhas e colunas
         */
        foreach ($linhasDeRestos as $codigoLinha) {
            foreach ($colunasParaProcessar as $codigoColuna => $formula) {
                $this->aLinhasConsistencia[$codigoLinha]->colunas[$codigoColuna]->o116_formula = $formula;
            }
        }

        /*
         * processa os valores de para as colunas de acordo com as fórmulas configuradas
         */
        $colunasParaProcessar = array_keys($colunasParaProcessar);
        $this->executarRestosPagar($linhasDeRestos, $colunasParaProcessar);
        foreach ($linhasDeRestos as $codigoLinha) {
            foreach ($colunasParaProcessar as $codigoColuna => $formula) {
                $this->aLinhasConsistencia[$codigoLinha]->colunas[$codigoColuna]->o116_formula = '';
                $this->processaValorManualPorLinhaEColuna($codigoLinha, $codigoColuna);
            }
        }
        $this->processaTotalizadores($this->aLinhasConsistencia);
    }

    /**
     * @throws \Exception
     * @throws \ParameterException
     */
    protected function processarLinhasComIndicadorDeSuperavit()
    {
        $this->processarFormulaDaLinha(70);
    }

    /**
     * @return \stdClass
     * @throws \Exception
     * @throws \ParameterException
     */
    public function getDadosSimplificado()
    {
        $linhas = $this->getDados();
        $dados = new \stdClass();
        $dados->metaResultadoNominal = $linhas[61]->valor_corrente;
        $dados->resultadoNominal = $linhas[60]->valor_incorrido;
        $dados->percentualMetaNominal = 0;

        if ($dados->metaResultadoNominal > 0) {
            $dados->percentualMetaNominal = round(($dados->resultadoNominal / $dados->metaResultadoNominal) * 100, 2);
        }

        $dados->metaResultadoPrimario = $linhas[57]->valor_corrente;
        $dados->resultadoPrimario = $linhas[56]->valor_corrente;
        $dados->percentualMetaPrimario = 0;

        if ($dados->metaResultadoPrimario > 0) {
            $dados->percentualMetaPrimario = round(
                ($dados->resultadoPrimario / $dados->metaResultadoPrimario) * 100,
                2
            );
        }

        return $dados;
    }

    protected function processarTotalizadoresCalculoResultadoNominal()
    {
        $linhaDeducoes = $this->aLinhasConsistencia[63];
        $linhaDisponibilidadeCaixa = $this->aLinhasConsistencia[64];
        $linhaDisponibilidadeCaixaBruta = $this->aLinhasConsistencia[65];
        $linhaRestosPagarProcessados = $this->aLinhasConsistencia[66];
        $linhaDemaisHaveresFinanceiros = $this->aLinhasConsistencia[67];
        $linhaDividaConsolidada = $this->aLinhasConsistencia[62];
        $linhaDividaConsolidadaLiquida = $this->aLinhasConsistencia[68];

        $linhaDisponibilidadeCaixa->saldo_bimestre_anterior = $linhaDisponibilidadeCaixaBruta->saldo_bimestre_anterior -
            $linhaRestosPagarProcessados->saldo_bimestre_anterior;
        $linhaDisponibilidadeCaixa->saldo_bimestre_atual = $linhaDisponibilidadeCaixaBruta->saldo_bimestre_atual -
            $linhaRestosPagarProcessados->saldo_bimestre_atual;

        if ($linhaDisponibilidadeCaixa->saldo_bimestre_anterior < 0) {
            $linhaDisponibilidadeCaixa->saldo_bimestre_anterior = 0;
        }

        if ($linhaDisponibilidadeCaixa->saldo_bimestre_atual < 0) {
            $linhaDisponibilidadeCaixa->saldo_bimestre_atual = 0;
        }

        $calc = $linhaDisponibilidadeCaixa->saldo_bimestre_anterior +
            $linhaDemaisHaveresFinanceiros->saldo_bimestre_anterior;
        $linhaDeducoes->saldo_bimestre_anterior = $calc;
        $calc = $linhaDisponibilidadeCaixa->saldo_bimestre_atual + $linhaDemaisHaveresFinanceiros->saldo_bimestre_atual;
        $linhaDeducoes->saldo_bimestre_atual = $calc;

        $calc = $linhaDividaConsolidada->saldo_bimestre_anterior - $linhaDeducoes->saldo_bimestre_anterior;
        $linhaDividaConsolidadaLiquida->saldo_bimestre_anterior = $calc;
        $calc = $linhaDividaConsolidada->saldo_bimestre_atual - $linhaDeducoes->saldo_bimestre_atual;
        $linhaDividaConsolidadaLiquida->saldo_bimestre_atual = $calc;

        $this->processarFormulaDaLinha(69);
    }
}
