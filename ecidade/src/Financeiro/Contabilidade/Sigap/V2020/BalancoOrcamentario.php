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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout\AnexoI;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use RelatoriosLegaisBase;

/**
 * Class BalancoOrcamentario
 * @package ECidade\Financeiro\Orcamento\Sigap
 */
class BalancoOrcamentario extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOBalancoOrcamentario';

    /**
     * Referência do relatório no xml de notas explicativas.
     */
    const CODIGO_RELATORIO_AUXILIAR = 219;

    protected $template = [
        'booCodigoEntidade',
        'booBimestre',
        'booMesAnoMovimento',
        'booContaLRF',
        'booDescricaoContaLRF',
        'booPrevisaoInicial',
        'booPrevisaoAtualizada',
        'booReceitaRealnoBim',
        'booPercReceitaRealnoBim',
        'booReceitaRealateBim',
        'booPercReceitaRealateBim',
        'booSaldoaRealizar',
        'booDotacaoInicial',
        'booDotacaoAtualizada',
        'booDespEmpnoBim',
        'booDespEmpateBim',
        'booSaldoDODespEmpenhada',
        'booDespLiqnoBim',
        'booDespLiqateBim',
        'booSaldoDODespLiquidada',
        'booDespPaqateBim',
        'booDespInscRAPNProcessados',
        'booValorUnico'
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
    private $linhasAuxiliares;

    /**
     * @return array
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Balanco_Orcamentario_AnexoI.php');
        return $this->linhasTemplate;
    }

    public function getLinhasProcessadas()
    {
        $layout = new AnexoI($this->ano, $this->periodo, implode(', ', $this->codigoInstituicoes));
        $layout->processarFiscal();
        $this->linhasProcessadas = $layout->getLinhas();
    }

    protected function processar()
    {
        $this->getLinhasProcessadas();
        $this->getLinhasAuxiliares();
    }

    /**
     * @return array
     */
    protected function criaEstruturaCabecalho()
    {
        $periodo = $this->periodo->getDataFinal($this->ano);
        return [
            'booCodigoEntidade' => $this->codigoTCE,
            'booBimestre' => PeriodoDePara::bimestre($this->periodo),
            'booMesAnoMovimento' => $periodo->getDate(),
        ];
    }

    /**
     * @param $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            'booContaLRF' => $linha['conta_lrf'],
            'booDescricaoContaLRF' => $linha['descricao'],
        ];
    }

    /**
     * @param $linha
     * @return array
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];
        if ($linha_ecidade >= 1 && $linha_ecidade < 80) {
            if ($linha_ecidade == 75) {
                return $this->criaLinhaUnicaReceita($linha);
            }
            return $this->criaLinhaReceita($linha);
        } elseif ($linha_ecidade >= 80 && $linha_ecidade < 105) {
            return $this->criaLinhaDespesa($linha);
        } elseif (in_array($linha_ecidade, [998, 999])) {
            return $this->criaLinhaLDO($linha);
        }
    }

    /**
     * @param $linha
     * @return array
     */
    private function criaLinhaReceita($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $saldo = 0.0;
        $fPorcentagem1 = 0.0;
        $fPorcentagem2 = 0.0;

        if ($linhaRelatorio->previni !== '-') {
            if (!empty($linhaRelatorio->recnobim) && !empty($linhaRelatorio->prevatu)) {
                $fPorcentagem1 = ((float)$linhaRelatorio->recnobim / (float)$linhaRelatorio->prevatu) * 100;
                $fPorcentagem2 = ((float)$linhaRelatorio->recatebim / (float)$linhaRelatorio->prevatu) * 100;
            }

            $saldo = $linhaRelatorio->prevatu - $linhaRelatorio->recatebim;
        }

        return [
            'booContaLRF' => $linha['conta_lrf'],
            'booDescricaoContaLRF' => $linha['descricao'],
            'booPrevisaoInicial' => $this->formatarValor($linhaRelatorio->previni),
            'booPrevisaoAtualizada' => $this->formatarValor($linhaRelatorio->prevatu),
            'booReceitaRealnoBim' => $this->formatarValor($linhaRelatorio->recnobim),
            'booPercReceitaRealnoBim' => $this->formatarValor($fPorcentagem1),
            'booReceitaRealateBim' => $this->formatarValor($linhaRelatorio->recatebim),
            'booPercReceitaRealateBim' => $this->formatarValor($fPorcentagem2),
            'booSaldoaRealizar' => $this->formatarValor($saldo),
        ];
    }

    private function criaLinhaDespesa($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $saldo1 = $linhaRelatorio->dotatu - $linhaRelatorio->empenhado_atebim;
        $saldo2 = $linhaRelatorio->dotatu - $linhaRelatorio->liquidado_atebim;

        $valorRestosApagar = 0.0;
        if ($this->periodo->getCodigo() == 11) {
            $valorRestosApagar = $linhaRelatorio->rp_apagar;
        }

        return [
            'booContaLRF' => $linha['conta_lrf'],
            'booDescricaoContaLRF' => $linha['descricao'],
            'booDotacaoInicial' => $this->formatarValor($linhaRelatorio->dotini),
            'booDotacaoAtualizada' => $this->formatarValor($linhaRelatorio->dotatu),
            'booDespEmpnoBim' => $this->formatarValor($linhaRelatorio->empenhado_nobim),
            'booDespEmpateBim' => $this->formatarValor($linhaRelatorio->empenhado_atebim),
            'booSaldoDODespEmpenhada' => $this->formatarValor($saldo1),
            'booDespLiqnoBim' => $this->formatarValor($linhaRelatorio->liquidado_nobim),
            'booDespLiqateBim' => $this->formatarValor($linhaRelatorio->liquidado_atebim),
            'booSaldoDODespLiquidada' => $this->formatarValor($saldo2),
            'booDespPaqateBim' => $this->formatarValor($linhaRelatorio->desppag),
            'booDespInscRAPNProcessados' => $this->formatarValor($valorRestosApagar),
        ];
    }

    private function criaLinhaUnicaReceita(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];
        return $this->criaLinhaUnica($linha, $linhaRelatorio->recatebim);
    }

    /**
     * @param $linha
     * @return array
     */
    protected function criaLinhaUnica(array $linha, $valor)
    {
        return [
            'booContaLRF' => $linha['conta_lrf'],
            'booDescricaoContaLRF' => $linha['descricao'],
            'booValorUnico' => $this->formatarValor($valor),
        ];
    }

    private function getLinhasAuxiliares()
    {
        $relatorio = new RelatoriosLegaisBase(
            $this->ano,
            self::CODIGO_RELATORIO_AUXILIAR,
            $this->periodo->getCodigo()
        );

        $this->linhasAuxiliares = $relatorio->getDados();
    }

    private function criaLinhaLDO(array $linha)
    {
        $index = $linha['linha_ecidade'] === 998 ? 1 : 2;
        $linhaRelatorio = $this->linhasAuxiliares[$index];
        return $this->criaLinhaUnica($linha, $linhaRelatorio->valor);
    }
}
