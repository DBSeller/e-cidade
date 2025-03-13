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
use Exception;
use RelatoriosLegaisBase;

/**
 * Class BalancoFuncao
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class BalancoFuncao extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOBalancoFuncao';

    const LINHA_TOTAL_DESPESA = 3;
    const LINHA_DESPESA_INTRA = 33;

    /**
     * @var string[]
     */
    protected $template = [
        'bfuCodigoEntidade',
        'bfuBimestre',
        'bfuMesAnoMovimento',
        'bfuContaLRF',
        'bfuDescricaoContaLRF',
        'bfuDotacaoInicial',
        'bfuDotacaoAtualizada',
        'bfuDespEmpnoBim',
        'bfuDespEmpateBim',
        'bfuPercDespEmpateBim',
        'bfuSaldoDODespEmpenhada',
        'bfuDespLiqnoBim',
        'bfuDespLiqateBim',
        'bfuPercDespLiqateBim',
        'bfuSaldoDODespLiquidada',
        'bfuRAPNaoProcessados',
        'bfuValorUnico',
    ];

    /**
     * @var array
     */
    private $linhasTemplate = [];
    /**
     * @var array
     */
    private $linhasProcessadas = [];

    /**
     * @var array
     */
    private $despesas = [];
    /**
     * @var array
     */
    private $despesasIntraOrcamentaria = [];

    /**
     * @throws Exception
     */
    protected function processar()
    {
        $this->getLinhasTemplate();

        $this->despesas = $this->buscarDespesas();
        $this->despesasIntraOrcamentaria = $this->buscarDespesas(true);

        foreach ($this->linhasTemplate as $linha => $dadosLinha) {
            switch ($linha) {
                case 1:
                case 2:
                    $this->linhasProcessadas[$linha] = $this->criaLinhaTitulo($dadosLinha);
                    break;
                case 3:
                    $this->linhasProcessadas[$linha] = $this->totalizaDespesas($dadosLinha);
                    break;
                case 33:
                    $this->linhasProcessadas[$linha] = $this->totalizaIntraOrcamentaria($dadosLinha);
                    break;
                case 34:
                    $this->linhasProcessadas[$linha] = $this->calculaLinhaTotal($dadosLinha);
                    break;
                default:
                    $this->linhasProcessadas[$linha] = $this->calculaDespesa($dadosLinha);
                    break;
            }
        }

        $this->calculaLinhasPercentuais();
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Funcao_SubFuncao.php');
        return $this->linhasTemplate;
    }

    /**
     * @param array $linhasTemplate
     * @return BalancoFuncao
     */
    public function setLinhasTemplate($linhasTemplate)
    {
        $this->linhasTemplate = $linhasTemplate;
        return $this;
    }

    protected function criaLinhaCalculo($linha)
    {
        // TODO: Implement criaLinhaCalculo() method.
    }

    protected function criaLinhaTitulo($linha)
    {
        return [
            'bfuCodigoEntidade' => $this->codigoTCE,
            'bfuBimestre' => PeriodoDePara::bimestre($this->periodo),
            'bfuMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'bfuContaLRF' => $linha['conta_lrf'],
            'bfuDescricaoContaLRF' => $linha['descricao'],
        ];
    }

    protected function criaEstruturaCabecalho()
    {
        // TODO: Implement criaEstruturaCabecalho() method.
    }

    /**
     * Retorna as despesas indexadas pelo código da funcao
     * @param bool $intraOrcamentaria
     * @return array
     */
    private function buscarDespesas($intraOrcamentaria = false)
    {
        $where = [
            "o58_instit in (" . implode(', ', $this->codigoInstituicoes) . ")",
            "o58_subfuncao <> 997"
        ];

        if ($intraOrcamentaria) {
            $where[] = "substr(o56_elemento,4,2) = '91'";
        } else {
            $where[] = "substr(o56_elemento,4,2) != '91'";
        }

        $sqlDespesa = db_dotacaosaldo(
            3,
            2,
            4,
            true,
            implode(' and ', $where),
            $this->ano,
            $this->periodo->getDataInicial($this->ano)->getDate(),
            $this->periodo->getDataFinal($this->ano)->getDate(),
            8,
            0,
            true,
            1,
            false
        );

        $sql = "
            select o58_coddot,
                   o58_funcao,
                   o52_descr,
                   o58_subfuncao,
                   o53_descr,
                   o58_elemento,
                   sum(dot_ini) as dot_ini_p,
                   sum(suplementado_acumulado) as suplementado_p,
                   sum(reduzido_acumulado) as reduzir_p,
                   sum(empenhado) as empenhado_p,
                   sum(anulado) as anulado_p,
                   sum(empenhado_acumulado) as empenhado_acumulado_p,
                   sum(anulado_acumulado) as anulado_acumulado_p,
                   sum(liquidado) as liquidado_p,
                   sum(liquidado_acumulado) as liquidado_acumulado_p,
                   sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p
              from ($sqlDespesa) as x
             group by o58_subfuncao,o53_descr,o58_funcao,o52_descr,o58_elemento,o58_coddot
             order by o58_funcao, o58_subfuncao
        ";

        $rs = db_query($sql);
        $despesas = [];

        while ($dados = pg_fetch_array($rs)) {
            $funcao = $dados['o58_funcao'];

            $dotacaoAtualizada = $dados['dot_ini_p'] + $dados['suplementado_p'] - $dados['reduzir_p'];
            $empenhadoNoBi = $dados['empenhado_p'] - $dados['anulado_p'];
            $totalEmpenhadoAteBi = $dados['empenhado_acumulado_p'] - $dados['anulado_acumulado_p'];
            $liquidadoNoBi = $dados['liquidado_p'];
            $totalLiquidadoAteBi = $dados['liquidado_acumulado_p'];

            $valorRestosApagar = '0.00';
            if ($this->periodo->getCodigo() == 11) {
                $valorRestosApagar = abs($totalEmpenhadoAteBi - $totalLiquidadoAteBi);
            }

            $despesas[$funcao]['nome'] = trim($dados['o52_descr']); // só para facilitar o debug
            $despesas[$funcao]['bfuDotacaoInicial'] = $dados['dot_ini_p'];
            $despesas[$funcao]['bfuDotacaoAtualizada'] = (float)$dotacaoAtualizada;
            $despesas[$funcao]['bfuDespEmpnoBim'] = (float)$empenhadoNoBi;
            $despesas[$funcao]['bfuDespEmpateBim'] = (float)$totalEmpenhadoAteBi;
            $despesas[$funcao]['bfuSaldoDODespEmpenhada'] = (float)($dotacaoAtualizada - $totalEmpenhadoAteBi);
            $despesas[$funcao]['bfuDespLiqnoBim'] = (float)$liquidadoNoBi;
            $despesas[$funcao]['bfuDespLiqateBim'] = (float)$totalLiquidadoAteBi;
            $despesas[$funcao]['bfuSaldoDODespLiquidada'] = (float)($dotacaoAtualizada - $totalLiquidadoAteBi);
            $despesas[$funcao]['bfuRAPNaoProcessados'] = $valorRestosApagar;
            $despesas[$funcao]['bfuValorUnico'] = '0.00';
        }
        return $despesas;
    }

    /**
     * @param $dadosLinha
     * @return array
     */
    private function totalizaDespesas($dadosLinha)
    {
        $dados = $this->criaLinhaTitulo($dadosLinha);
        foreach ($this->despesas as $despesa) {
            foreach ($despesa as $tag => $valor) {
                if (!array_key_exists($tag, $dados)) {
                    $dados[$tag] = 0;
                }

                $dados[$tag] += $valor;
            }
        }

        $dados['bfuPercDespEmpateBim'] = 100;
        $dados['bfuPercDespLiqateBim'] = 100;

        return $dados;
    }

    /**
     * @param $dadosLinha
     * @return array
     * @throws Exception
     */
    private function calculaDespesa($dadosLinha)
    {
        $funcao = $dadosLinha['funcao'];
        if (empty($funcao)) {
            throw new Exception("Funcão não informada. Linha: {$dadosLinha['descricao']}");
        }

        $dados = $this->criaLinhaTitulo($dadosLinha);
        if (isset($this->despesas[$funcao])) {
            foreach ($this->despesas[$funcao] as $tag => $valor) {
                $dados[$tag] = $valor;
            }
        }

        return $dados;
    }

    /**
     * @return array
     */
    private function totalizaIntraOrcamentaria($dadosLinha)
    {
        $dados = $this->criaLinhaTitulo($dadosLinha);

        foreach ($this->despesasIntraOrcamentaria as $despesas) {
            foreach ($despesas as $tag => $valor) {
                if (!array_key_exists($tag, $dados)) {
                    $dados[$tag] = 0;
                }
                $dados[$tag] += $valor;
            }
        }
        return $dados;
    }

    /**
     * Totaliza o relatório.
     * Linha 3 e 33
     * @param $dadosLinha
     * @return array
     */
    private function calculaLinhaTotal($dadosLinha)
    {
        $dados = $this->criaLinhaTitulo($dadosLinha);
        $linhaDespesa = $this->linhasProcessadas[self::LINHA_TOTAL_DESPESA];
        $linhaDespesaIntra = $this->linhasProcessadas[self::LINHA_DESPESA_INTRA];

        $ignorar = [
            'bfuCodigoEntidade',
            'bfuBimestre',
            'bfuMesAnoMovimento',
            'bfuContaLRF',
            'bfuDescricaoContaLRF'
        ];
        foreach ($this->template as $tag) {
            if (in_array($tag, $ignorar)) {
                continue;
            }
            $valorDespesa = isset($linhaDespesa[$tag]) ? $linhaDespesa[$tag] : 0;
            $valorDespesaIntra = isset($linhaDespesaIntra[$tag]) ? $linhaDespesaIntra[$tag] : 0;
            $dados[$tag] = $valorDespesa + $valorDespesaIntra;
        }

        $dados['bfuPercDespEmpateBim'] = '0.00';
        $dados['bfuPercDespLiqateBim'] = '0.00';
        return $dados;
    }

    private function calculaLinhasPercentuais()
    {
        $linhaDespesa = $this->linhasProcessadas[self::LINHA_TOTAL_DESPESA];
        $totalDespEmpenhadaAteBi = $linhaDespesa['bfuDespEmpateBim'];
        $totalDespLiquidadaAteBi = $linhaDespesa['bfuDespLiqateBim'];

        $totalDespEmpenhadaAteBiIntra = 0;
        $totalDespLiquidadaAteBiIntra = 0;
        if (isset($this->linhasProcessadas[self::LINHA_DESPESA_INTRA]['bfuDespEmpateBim'])) {
            $linhaDespesaIntra = $this->linhasProcessadas[self::LINHA_DESPESA_INTRA];
            $totalDespEmpenhadaAteBiIntra = $linhaDespesaIntra['bfuDespEmpateBim'];
            $totalDespLiquidadaAteBiIntra = $linhaDespesaIntra['bfuDespLiqateBim'];
        }

        $totalEmpenhado = $totalDespEmpenhadaAteBi + $totalDespEmpenhadaAteBiIntra;
        $totalLiquidado = $totalDespLiquidadaAteBi + $totalDespLiquidadaAteBiIntra;

        // percentual das DESPESAS (EXCETO INTRA- ORÇAMENTÁRIAS) (I)
        $this->linhasProcessadas[self::LINHA_TOTAL_DESPESA]['bfuPercDespEmpateBim'] = $this->calculaPercentual(
            $totalDespEmpenhadaAteBi,
            $totalEmpenhado
        );
        $this->linhasProcessadas[self::LINHA_TOTAL_DESPESA]['bfuPercDespLiqateBim'] = $this->calculaPercentual(
            $totalDespLiquidadaAteBi,
            $totalLiquidado
        );

        // percentual das DESPESAS (INTRA-ORÇAMENTÁRIAS) (II)
        if (isset($this->linhasProcessadas[self::LINHA_DESPESA_INTRA]['bfuDespEmpateBim'])) {
            $this->linhasProcessadas[self::LINHA_DESPESA_INTRA]['bfuPercDespEmpateBim'] = $this->calculaPercentual(
                $totalDespEmpenhadaAteBiIntra,
                $totalEmpenhado
            );
            $this->linhasProcessadas[self::LINHA_DESPESA_INTRA]['bfuPercDespLiqateBim'] = $this->calculaPercentual(
                $totalDespLiquidadaAteBiIntra,
                $totalLiquidado
            );
        }

        foreach ($this->linhasProcessadas as $linha => $dadosLinha) {
            // linhas de cabeçalho, totalizalizadoras e intra-orçamentária
            if (in_array($linha, [1, 2, 3, 33, 34])) {
                continue;
            }

            $percentual = '0.00';
            if (isset($dadosLinha['bfuDespEmpateBim'])) {
                $percentual = $this->calculaPercentual($dadosLinha['bfuDespEmpateBim'], $totalDespEmpenhadaAteBi);
            }
            $this->linhasProcessadas[$linha]['bfuPercDespEmpateBim'] = $percentual;

            $percentual = '0.00';
            if (isset($dadosLinha['bfuDespLiqateBim'])) {
                $percentual = $this->calculaPercentual($dadosLinha['bfuDespLiqateBim'], $totalDespLiquidadaAteBi);
            }
            $this->linhasProcessadas[$linha]['bfuPercDespLiqateBim'] = $percentual;
        }
    }

    private function calculaPercentual($valor1, $valor2)
    {
        $percentual = round(($valor1 / $valor2) * 100, 2);
        return number_format($percentual, 2, '.', '');
    }

    /**
     * @return string
     * @throws Exception
     */
    public function emitirXML()
    {
        $this->processar();
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);

        foreach ($this->linhasProcessadas as $linha => $colunas) {
            $elementoLinha = $xml->createElement('Elem' . static::TAG);

            if (empty($colunas)) {
                $colunas = $this->criaLinhaTitulo($this->linhasTemplate[$linha]);
            }

            foreach ($this->template as $tag) {
                if (array_key_exists($tag, $colunas)) {
                    $elementoColuna = $xml->createElement($tag, $colunas[$tag]);
                } else {
                    $elementoColuna = $xml->createElement($tag, '0.00');
                }

                $elementoLinha->appendChild($elementoColuna);
            }

            $principalNode->appendChild($elementoLinha);
        }

        $xml->appendChild($principalNode);

        $filePath = 'tmp' . DS . static::TAG . '_' . $this->codigoTCE . '.xml';
        file_put_contents($filePath, $xml->saveXML());

        return $filePath;
    }
}
