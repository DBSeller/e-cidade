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
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoVI;
use RelatoriosLegaisBase;

/**
 * Class ResultadoPrimarioNominal
 * @package ECidade\Financeiro\Orcamento\Sigap
 */
class ResultadoPrimarioNominal extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOResultadoPrimarioENominal';

    /**
     * Template do xml (Array contendo nome das tags)
     * @property array
     */
    protected $template = [
        'rpnCodigoEntidade',
        'rpnBimestre',
        'rpnMesAnoMovimento',
        'rpnContaLRF',
        'rpnDescricaoContaLRF',
        'rpnPrevisaoAtualizada',
        'rpnReceitaRealateBim',
        'rpnDotacaoAtualizada',
        'rpnDespEmpateBim',
        'rpnDespLiqateBim',
        'rpnDespPagateBim',
        'rpnDespInscRAPProcePag',
        'rpnDespInscRAPNProceLiq',
        'rpnDespInscRAPNProcePag',
        'rpnSaldoExercAnterior',
        'rpnSaldoBimAtual',
        'rpnValorUnico'
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
            $path . DS . 'linhas_RREO_Demonstrativo_Resultados_Primario_Nominal_AnexoVI.php'
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
        $anexo = AnexoVI::getInstance($this->ano, $this->periodo->getCodigo());
        $anexo->setInstituicoes(implode(', ', $this->codigoInstituicoes));

        $this->getLinhasTemplate();
        $this->linhasProcessadas = $anexo->getDados();
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
        if ($linha_ecidade >= 0 && $linha_ecidade <= 39) {
            return $this->criaLinhaReceita($linha);
        } elseif ($linha_ecidade >= 40 && $linha_ecidade <= 55) {
            if ($linha_ecidade == 54) {
                return $this->criaLinhaDespesa($linha, true);
            }
            return $this->criaLinhaDespesa($linha);
        } elseif ($linha_ecidade >= 62 && $linha_ecidade <= 68) {
            return $this->criaLinhaResultadoNominal($linha);
        } else {
            return $this->criaLinhaUnica($linha);
        }
    }

    /**
     * Função que cria uma linha de receita
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaReceita($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            'rpnContaLRF' => $linha['conta_lrf'],
            'rpnDescricaoContaLRF' => $linha['descricao'],
            'rpnPrevisaoAtualizada' => $this->formatarValor($linhaRelatorio->previni),
            'rpnReceitaRealateBim' => $this->formatarValor($linhaRelatorio->saldo_bimestre_atual)
        ];
    }

    /**
     * Função que cria uma linha de despesa
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaDespesa($linha, $checkaValores = false)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        if ($checkaValores) {
            isset($linhaRelatorio->dotatu) || $linhaRelatorio->dotatu = '-';
            isset($linhaRelatorio->dotatu) || $linhaRelatorio->dotatu = '-';
            isset($linhaRelatorio->despemp) || $linhaRelatorio->despemp = '-';
            isset($linhaRelatorio->despliq) || $linhaRelatorio->despliq = '-';
            isset($linhaRelatorio->desppag) || $linhaRelatorio->desppag = '-';
            isset($linhaRelatorio->rp_proc_pago) || $linhaRelatorio->rp_proc_pago = '-';
            isset($linhaRelatorio->rp_nao_processado) || $linhaRelatorio->rp_nao_processado = '-';
            isset($linhaRelatorio->rp_pagos) || $linhaRelatorio->rp_pagos = '-';
        }

        return [
            'rpnContaLRF' => $linha['conta_lrf'],
            'rpnDescricaoContaLRF' => $linha['descricao'],
            'rpnDotacaoAtualizada' => $this->formatarValor($linhaRelatorio->dotatu),
            'rpnDespEmpateBim' => $this->formatarValor($linhaRelatorio->despemp),
            'rpnDespLiqateBim' => $this->formatarValor($linhaRelatorio->despliq),
            'rpnDespPagateBim' => $this->formatarValor($linhaRelatorio->desppag),
            'rpnDespInscRAPProcePag' => $this->formatarValor($linhaRelatorio->rp_proc_pago),
            'rpnDespInscRAPNProceLiq' => $this->formatarValor($linhaRelatorio->rp_nao_processado),
            'rpnDespInscRAPNProcePag' => $this->formatarValor($linhaRelatorio->rp_pagos),
        ];
    }

    /**
     * Função que cria uma linha de resultado nominal
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaResultadoNominal($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            'rpnContaLRF' => $linha['conta_lrf'],
            'rpnDescricaoContaLRF' => $linha['descricao'],
            'rpnSaldoExercAnterior' => $this->formatarValor($linhaRelatorio->saldo_bimestre_anterior),
            'rpnSaldoBimAtual' => $this->formatarValor($linhaRelatorio->saldo_bimestre_atual),
        ];
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
            'rpnContaLRF' => $linha['conta_lrf'],
            'rpnDescricaoContaLRF' => $linha['descricao']
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
            'rpnCodigoEntidade' => $this->codigoTCE,
            'rpnBimestre' => PeriodoDePara::bimestre($this->periodo),
            'rpnMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
        ];
    }

    /**
     * Função que cria uma linha de valor único
     *
     * @param $linha
     * @return array
     */
    protected function criaLinhaUnica(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        !isset($linhaRelatorio->valor_corrente) || $valor = $linhaRelatorio->valor_corrente;
        !isset($linhaRelatorio->valor_incorrido) || $valor = $linhaRelatorio->valor_incorrido;
        !isset($linhaRelatorio->saldo) || $valor = $linhaRelatorio->saldo;
        !isset($linhaRelatorio->previsao) || $valor = $linhaRelatorio->previsao;

        return [
            'rpnContaLRF' => $linha['conta_lrf'],
            'rpnDescricaoContaLRF' => $linha['descricao'],
            'rpnValorUnico' => $this->formatarValor($valor),
        ];
    }
}
