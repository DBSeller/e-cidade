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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;

/**
 * Class AnexoVI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 */
class AnexoVI extends \RelatoriosLegaisBase
{

    /**
     * Código do Relatório no E-Cidade
     * @type integer
     */
    const CODIGO_RELATORIO = 177;

    /**
     * @return array
     * @throws \Exception
     * @throws \ParameterException
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        parent::getDados($trazerConfiguracaoPadrao);

        $this->processarRestosAPagar();
        $this->processarLinhasComIndicadorDeSuperavit();

        $this->processarTotalizadoresCalculoResultadoNominal();

        foreach (array(56, 60, 74, 75) as $linha) {
            $this->processarFormulaDaLinha($linha);
        }

        return $this->aLinhasConsistencia;
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

        $linhasComSuperavit = array(66, 67);
        $where = array();
        foreach ($linhasComSuperavit as $linhas) {
            $contasLinha = $this->aLinhasConsistencia[$linhas]->parametros->contas;
            /*
             * Limpa os valores para as colunas dessa linha
             */
            foreach ($this->aLinhasConsistencia[$linhas]->colunas as $dadosColuna) {
                $this->aLinhasConsistencia[$linhas]->{$dadosColuna->o115_nomecoluna} = 0;
            }

            foreach ($contasLinha as $dadosConta) {
                $estrutura = new Estrutural($dadosConta->estrutural);
                $estruturaPesquisar = $estrutura->getEstruturalAteNivel();
                $where[] = "(
                c60_estrut ilike '{$estruturaPesquisar}%'
                and c60_identificadorfinanceiro = 'F'
                and c60_anousu = {$this->getAno()}
                )";
            }
        }
        $this->processaValorManualPorLinhaEColuna(66, 0);
        $this->processaValorManualPorLinhaEColuna(66, 1);
        $this->processaValorManualPorLinhaEColuna(67, 0);
        $this->processaValorManualPorLinhaEColuna(67, 1);
        if (!empty($where)) {
            $where = implode(' or ', $where);
            $this->executarBalanceteVerificacao($linhasComSuperavit, null, $where);
        }

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

    private function processarTotalizadoresCalculoResultadoNominal()
    {
        $linhaDeducoes = $this->aLinhasConsistencia[63];
        $linhaDisponibilidadeCaixa = $this->aLinhasConsistencia[64];
        $linhaDisponibilidadeCaixaBruta = $this->aLinhasConsistencia[65];
        $linhaRestosPagarProcessados = $this->aLinhasConsistencia[66];
        $linhaDemaisHaveresFinanceiros = $this->aLinhasConsistencia[67];
        $linhaDividaConsolidada = $this->aLinhasConsistencia[62];
        $linhaDividaConsolidadaLiquida = $this->aLinhasConsistencia[68];

        $saldoBimestreAnterior = $linhaDisponibilidadeCaixaBruta->saldo_bimestre_anterior;
        $saldoBimestreAnteriorRP = $linhaRestosPagarProcessados->saldo_bimestre_anterior;
        $linhaDisponibilidadeCaixa->saldo_bimestre_anterior = $saldoBimestreAnterior - $saldoBimestreAnteriorRP;
        $saldoBimestreAtual = $linhaDisponibilidadeCaixaBruta->saldo_bimestre_atual;
        $saldoBimestreAtualRP = $linhaRestosPagarProcessados->saldo_bimestre_atual;
        $linhaDisponibilidadeCaixa->saldo_bimestre_atual = $saldoBimestreAtual - $saldoBimestreAtualRP;

        $saldoBimestreAnteriorCaixa = $linhaDisponibilidadeCaixa->saldo_bimestre_anterior;
        $saldoBimestreAnteriorHaveres = $linhaDemaisHaveresFinanceiros->saldo_bimestre_anterior;
        $linhaDeducoes->saldo_bimestre_anterior = $saldoBimestreAnteriorCaixa + $saldoBimestreAnteriorHaveres;

        $saldoBimestreAtual = $linhaDisponibilidadeCaixa->saldo_bimestre_atual;
        $saldoBimestreAtual1 = $linhaDemaisHaveresFinanceiros->saldo_bimestre_atual;
        $linhaDeducoes->saldo_bimestre_atual = $saldoBimestreAtual + $saldoBimestreAtual1;

        $saldoBimAntDivida = $linhaDividaConsolidada->saldo_bimestre_anterior;
        $saldoBimAntDeducoes = $linhaDeducoes->saldo_bimestre_anterior;
        $linhaDividaConsolidadaLiquida->saldo_bimestre_anterior = $saldoBimAntDivida - $saldoBimAntDeducoes;
        $saldoBimAtual = $linhaDividaConsolidada->saldo_bimestre_atual;
        $linhaDividaConsolidadaLiquida->saldo_bimestre_atual = $saldoBimAtual - $linhaDeducoes->saldo_bimestre_atual;

        $this->processarFormulaDaLinha(69);
    }
}
