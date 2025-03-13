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

namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoXII;
use RelatoriosLegaisBase;

/**
 * Class RREOReceitasDespesasSaude
 * @package ECidade\Financeiro\Orcamento\Sigap
 */
class RREOReceitasDespesasSaude extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOReceitasEDespesasSaude';

    /**
     * Referência do relatório no xml de notas explicativas.
     */
    const CODIGO_NOTA_EXPLICATIVA = '11';

    /**
     * Template do xml (Array contendo nome das tags)
     * @property array
     */
    protected $template = [
        'assCodigoEntidade',
        'assBimestre',
        'assMesAnoMovimento',
        'assContaLRF',
        'assDescricaoContaLRF',
        'assPrevisaoInicial',
        'assPrevisaoAtualizada',
        'assReceitaRealateBim',
        'assPercReceitaRealateBim',
        'assDotacaoInicial',
        'assDotacaoAtualizada',
        'assDespEmpateBim',
        'assVlrPercDespEmp',
        'assDespLiqateBim',
        'assVlrPercDespLiquid',
        'assDespInscRAPNProcessados',
        'assDespPagateBim',
        'assVlrPercDespPaga',
        'assSaldoInicialLNC',
        'assDespEmpLNC',
        'assDespLiqLNC',
        'assDespPagaLNC',
        'assSaldoFinalLNC',
        'assValorMinimoRAP',
        'assValorAplicadoRAP',
        'assValorAplicadoAlemMinRAP',
        'assInscritoRAPExercicio',
        'assInscritoRAPExercicioSemDisponibilidade',
        'assRAPInscritos',
        'assRAPPagos',
        'assRAPaPagar',
        'assRAPCancelados',
        'assDiferenca',
        'assSaldoInicial',
        'assSaldoFinal',
        'assValorUnico',
        'assValorUnicoPerc'
    ];

    /**
     * Linhas do template
     * @property array
     */
    private $linhasTemplate = [];

    /**
     * Linhas processadas do relatorio
     * @property array
     */
    private $linhasProcessadas = [];

    /**
     * Função que busca as linhas do template do xml
     *
     * @return array
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require(
            $path . DS . 'linhas_RREO_Demonstrativo_Receitas_Despesas_saude.php'
        );
        return $this->linhasTemplate;
    }

    /**
     * Função processa os dados do relatorio
     *
     * @param $linha
     * @return array
     */
    protected function processar()
    {
        $anexo = AnexoXII::getInstance($this->ano, $this->periodo->getCodigo());
        $anexo->setInstituicoes(implode(', ', $this->codigoInstituicoes));

        $this->getLinhasTemplate();

        $this->linhasProcessadas = $anexo->getDados();
        $this->notasExplicativas = $anexo->getTextoNotaExplicativa();
    }

    /**
     * Função que cria gere a criação de linhas
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];

        switch (true) {
            case ($linha_ecidade >= 0 && $linha_ecidade <= 21):
            case ($linha_ecidade >= 70 && $linha_ecidade <= 76):
                return $this->criaLinhaReceitas($linha);
            case ($linha_ecidade >= 22 && $linha_ecidade <= 43):
            case ($linha_ecidade >= 77 && $linha_ecidade <= 108):
                return $this->criaLinhaDespesas($linha);
            case ($linha_ecidade >= 44 && $linha_ecidade <= 48):
                return $this->criarLinhaASPS($linha);
            case ($linha_ecidade >= 49 && $linha_ecidade <= 53):
            case ($linha_ecidade >= 63 && $linha_ecidade <= 65):
                $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];
                if ($linha_ecidade == 53) {
                    return $this->criaLinhaUnicaPercentual($linha, $linhaRelatorio->valor);
                }
                return $this->criaLinhaUnica($linha, $linhaRelatorio->valor);
            case ($linha_ecidade >= 54 && $linha_ecidade <= 57):
                return $this->criaLinhaLimiteNaoCumprido($linha);
            case ($linha_ecidade >= 58 && $linha_ecidade <= 62):
                return $this->criaLinhaRestosAPagar($linha);
            case ($linha_ecidade >= 66 && $linha_ecidade <= 69):
                return $this->criaLinhaRAPCanceladosPrescritos($linha);
                break;
        }
    }

    /**
     * Função que cria uma linha de titulo
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            'assContaLRF' => $linha['conta_lrf'],
            'assDescricaoContaLRF' => $linha['descricao']
        ];
    }

    /**
     * Função que cria a linha de cabeçalho
     *
     * @param $linha
     * @return array
     */
    protected function criaEstruturaCabecalho()
    {
        return [
            'assCodigoEntidade' => $this->codigoTCE,
            'assBimestre' => PeriodoDePara::bimestre($this->periodo),
            'assMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
        ];
    }

    /**
     * Função que cria uma linha de valor único
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaUnica(array $linha, $valor)
    {
        return [
            'assContaLRF' => $linha['conta_lrf'],
            'assDescricaoContaLRF' => $linha['descricao'],
            'assValorUnico' => $this->formatarValor($valor),
        ];
    }

    /**
     * Função que cria uma linha de valor único percentual
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaUnicaPercentual(array $linha, $valor)
    {
        return [
            'assContaLRF' => $linha['conta_lrf'],
            'assDescricaoContaLRF' => $linha['descricao'],
            'assValorUnicoPerc' => $this->formatarValor($valor),
        ];
    }

    /**
     * Função que cria uma linha de receita
     *
     * @param $linha []
     * @return []
     */
    private function criaLinhaReceitas($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $previsaoInicial = $linhaRelatorio->previni;
        $previsaoAtualizada = $linhaRelatorio->prevatu;
        $ateOBim = $linhaRelatorio->recrealiza;
        $percentual = $previsaoAtualizada > 0 ? ($ateOBim / $previsaoAtualizada * 100) : 0;

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assPrevisaoInicial" => $this->formatarValor($previsaoInicial),
            "assPrevisaoAtualizada" => $this->formatarValor($previsaoAtualizada),
            "assReceitaRealateBim" => $this->formatarValor($ateOBim),
            "assPercReceitaRealateBim" => $this->formatarValor($percentual),
        ];
    }

    /**
     * Função que cria uma linha de despesas
     *
     * @param $linha []
     * @return []
     */
    private function criaLinhaDespesas($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $dotacaoInicial = $linhaRelatorio->dotini;
        $dotacaoAtualizada = $linhaRelatorio->dotatu;
        $despesasEmpenhadas = $linhaRelatorio->despemp;
        $despesasLiquidas = $linhaRelatorio->despliq;
        $despesasPagas = $linhaRelatorio->desppag;
        $restosAPagar = $linhaRelatorio->insc_rp_np;
        $percentualDespesasEmpenhadas = $dotacaoAtualizada > 0 ? ($despesasEmpenhadas / $dotacaoAtualizada * 100) : 0;
        $percentualDespesasLiquidas = $dotacaoAtualizada > 0 ? ($despesasLiquidas / $dotacaoAtualizada * 100) : 0;
        $percentualDespesasPagas = $dotacaoAtualizada > 0 ? ($despesasPagas / $dotacaoAtualizada * 100) : 0;

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assDotacaoInicial" => $this->formatarValor($dotacaoInicial),
            "assDotacaoAtualizada" => $this->formatarValor($dotacaoAtualizada),
            "assDespEmpateBim" => $this->formatarValor($despesasEmpenhadas),
            "assVlrPercDespEmp" => $this->formatarValor($percentualDespesasEmpenhadas),
            "assDespLiqateBim" => $this->formatarValor($despesasLiquidas),
            "assVlrPercDespLiquid" => $this->formatarValor($percentualDespesasLiquidas),
            "assDespInscRAPNProcessados" => $this->formatarValor($restosAPagar),
            "assDespPagateBim" => $this->formatarValor($despesasPagas),
            "assVlrPercDespPaga" => $this->formatarValor($percentualDespesasPagas),
        ];
    }

    /**
     * Função que cria uma linha da sessão limite não cumprido
     *
     * @param $linha []
     * @return []
     */
    private function criaLinhaLimiteNaoCumprido($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assSaldoInicial" => $this->formatarValor($linhaRelatorio->saldo_inicial),
            "assDespEmpLNC" => $this->formatarValor($linhaRelatorio->despemp),
            "assDespLiqLNC" => $this->formatarValor($linhaRelatorio->despliq),
            "assDespPagaLNC" => $this->formatarValor($linhaRelatorio->desppag),
            "assSaldoFinalLNC" => $this->formatarValor($linhaRelatorio->saldo_inicial - $linhaRelatorio->despliq)
        ];
    }

    /**
     * Função que cria uma linha da sessão restos a pagar
     *
     * @param $linha []
     * @return []
     */
    private function criaLinhaRestosAPagar($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $valorMinimo = $linhaRelatorio->valor_minino_aplicado;
        $valorAplicado = $linhaRelatorio->valor_aplicado_asps_exercicio;
        $totalInscrito = $linhaRelatorio->rp_inscrit;
        $inscritoIndevidamente = $linhaRelatorio->rpnp_inscritos_indevidamente;
        $totalCancelados = $linhaRelatorio->rp_cancel;

        $calculoAlemLimiteMinimo = $valorAplicado - $valorMinimo;
        $valorAplicadoAlemLimite = ($calculoAlemLimiteMinimo > 0) ? $calculoAlemLimiteMinimo : 0;
        $calculoInscritoLimite = ($totalInscrito - ($valorAplicadoAlemLimite + $inscritoIndevidamente));
        $valorInscritoConsideradoLimite = ($calculoInscritoLimite > 0) ? $calculoInscritoLimite : 0;
        $alemDoLimiteTotal = (($valorAplicadoAlemLimite + $inscritoIndevidamente) - $totalCancelados);

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assValorMinimoRAP" => $this->formatarValor($valorMinimo),
            "assValorAplicadoRAP" => $this->formatarValor($valorAplicado),
            "assValorAplicadoAlemMinRAP" => $this->formatarValor($valorAplicadoAlemLimite),
            "assInscritoRAPExercicio" => $this->formatarValor($totalInscrito),
            "assInscritoRAPExercicioSemDisponibilidade" => $this->formatarValor($inscritoIndevidamente),
            "assRAPInscritos" => $this->formatarValor($valorInscritoConsideradoLimite),
            "assRAPPagos" => $this->formatarValor($linhaRelatorio->rp_pagos),
            "assRAPaPagar" => $this->formatarValor($linhaRelatorio->rp_apagar),
            "assRAPCancelados" => $this->formatarValor($totalCancelados),
            "assDiferenca" => $this->formatarValor($alemDoLimiteTotal),
        ];
    }

    /**
     * Função que cria uma linha da sessão ASPS
     *
     * @param $linha []
     * @return []
     */
    private function criarLinhaASPS($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $despesasEmpenhadas = $linhaRelatorio->despemp;
        $despesasLiquidas = $linhaRelatorio->despliq;
        $despesasPagas = $linhaRelatorio->desppag;

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assDespEmpateBim" => $this->formatarValor($despesasEmpenhadas),
            "assDespLiqateBim" => $this->formatarValor($despesasLiquidas),
            "assDespPagateBim" => $this->formatarValor($despesasPagas)
        ];
    }

    /**
     * Função que cria uma linha da sessão Restos a Pagar cancelados ou prescritos
     *
     * @param $linha []
     * @return []
     */
    private function criaLinhaRAPCanceladosPrescritos($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "assContaLRF" => $linha['conta_lrf'],
            "assDescricaoContaLRF" => $linha['descricao'],
            "assSaldoInicial" => $this->formatarValor($linhaRelatorio->saldo_inicial),
            "assSaldoFinal" => $this->formatarValor($linhaRelatorio->saldo_final)
        ];
    }
}
