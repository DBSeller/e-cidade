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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoVIII;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

class RREODemonstrativoReceitasDespesasMDE extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOReceitasEDespesasMDE';

    /**
     * @var string[]
     */
    protected $template = [
        "mdeCodigoEntidade",
        "mdeBimestre",
        "mdeMesAnoMovimento",
        "mdeContaLRF",
        "mdeDescricaoContaLRF",
        "mdePrevisaoInicial",
        "mdePrevisaoAtualizada",
        "mdeReceitaRealateBim",
        "mdePercReceitaRealateBim",
        "mdeDotacaoInicial",
        "mdeDotacaoAtualizada",
        "mdeDespEmpateBim",
        "mdePercDespEmpeateBim",
        "mdeDespLiqateBim",
        "mdePercDespLiqateBim",
        "mdeDespInscRAPNProcessados",
        "mdeSaldoateBim",
        "mdeRAPCancelados",
        "mdeExercRAPCancelados",
        "mdeValorFundeb",
        "mdeValorSalEducacao",
        "mdeValorUnico"
    ];

    /**
     * @var array
     */
    protected $linhasProcessadas = [];

    protected function processar()
    {
        $this->getLinhasProcessadas();
    }

    public function getLinhasProcessadas()
    {
        $layout = new AnexoVIII($this->ano, $this->periodo->getCodigo());
        $layout->setInstituicoes(implode(', ', $this->codigoInstituicoes));
        $this->linhasProcessadas = $layout->getLinhas();
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Demonstrativo_Receitas_Despesas_MDE.php');
        return $this->linhasTemplate;
    }

    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];

        if ($linha_ecidade >= 1 && $linha_ecidade < 50) {
            return $this->criarLinhaReceita($linha);
        }

        if ($linha_ecidade < 57) {
            return $this->criarLinhaDespesasFundeb($linha);
        }

        if ($linha_ecidade < 70) {
            return $this->criarLinhaDeducoesLimiteFundeb($linha);
        }

        if ($linha_ecidade < 85) {
            return $this->criarLinhaDespesasFundeb($linha);
        }

        if ($linha_ecidade < 94) {
            return $this->criarLinhaDeducoesLimiteFundeb($linha);
        }

        if ($linha_ecidade < 100) {
            return $this->criarLinhaDespesasFundeb($linha);
        }

        if ($linha_ecidade < 103) {
            return $this->criarLinhaRestosPagarDespesas($linha);
        }

        return $this->criarLinhaControleDisponibilidadeFinanceira($linha);
    }

    private function criarLinhaReceita($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $percentual = 0;
        if ($linhaRelatorio->prevatu > 0) {
            $percentual = ($linhaRelatorio->recatebim/$linhaRelatorio->prevatu) * 100;
        }

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdePrevisaoInicial" => $this->formatarValor($linhaRelatorio->previni),
            "mdePrevisaoAtualizada" => $this->formatarValor($linhaRelatorio->prevatu),
            "mdeReceitaRealateBim" => $this->formatarValor($linhaRelatorio->recatebim),
            "mdePercReceitaRealateBim" => $this->formatarValor($percentual),
            "mdeExercRAPCancelados" => 0,
        ];
    }

    private function criarLinhaDespesasFundeb($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $percentualEmepenhadas = 0;
        $percentualLiquidadas = 0;
        if ($linhaRelatorio->dotatu > 0) {
            $percentualEmepenhadas = ($linhaRelatorio->empenhado_atebim/$linhaRelatorio->dotatu) * 100;
            $percentualLiquidadas = ($linhaRelatorio->liquidado_atebim/$linhaRelatorio->dotatu) * 100;
        }

        $restosPagar = 0;
        if (isset($linhaRelatorio->rp_apagar)) {
            $restosPagar = $linhaRelatorio->rp_apagar;
        }

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeDotacaoInicial" => $this->formatarValor($linhaRelatorio->dotini),
            "mdeDotacaoAtualizada" => $this->formatarValor($linhaRelatorio->dotatu),
            "mdeDespEmpateBim" => $this->formatarValor($linhaRelatorio->empenhado_atebim),
            "mdePercDespEmpeateBim" => $this->formatarValor($percentualEmepenhadas),
            "mdeDespLiqateBim" => $this->formatarValor($linhaRelatorio->liquidado_atebim),
            "mdePercDespLiqateBim" => $this->formatarValor($percentualLiquidadas),
            "mdeDespInscRAPNProcessados" => $this->formatarValor($restosPagar),
            "mdeExercRAPCancelados" => 0,
        ];
    }

    private function criarLinhaDeducoesLimiteFundeb($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeValorUnico" => $this->formatarValor($linhaRelatorio->valor),
            "mdeExercRAPCancelados" => 0,
        ];
    }

    private function criarLinhaDespesasCusteadasFundeb($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeValorUnico" => $this->formatarValor($linhaRelatorio->valor),
            "mdeExercRAPCancelados" => 0,
        ];
    }

    private function criarLinhaRestosPagarDespesas($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeSaldoateBim" => $this->formatarValor($linhaRelatorio->saldo),
            "mdeRAPCancelados" => $this->formatarValor($linhaRelatorio->cancelados),
            "mdeExercRAPCancelados" => $this->ano,
        ];
    }

    private function criarLinhaControleDisponibilidadeFinanceira($linha)
    {
        $linhaRelatorioFundeb = $this->linhasProcessadas[$linha['linha_ecidade']];
        $linhaRelatorioSalarioEducacao = $this->linhasProcessadas[$linha['linha_ecidade'] + 13];

        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeValorFundeb" => $this->formatarValor($linhaRelatorioFundeb->valor),
            "mdeValorSalEducacao" => $this->formatarValor($linhaRelatorioSalarioEducacao->valor),
            "mdeExercRAPCancelados" => 0,
        ];
    }

    protected function criaLinhaTitulo($linha)
    {
        return [
            "mdeContaLRF" => $linha['conta_lrf'],
            "mdeDescricaoContaLRF" => $linha['descricao'],
            "mdeExercRAPCancelados" => 0,
        ];
    }

    protected function criaEstruturaCabecalho()
    {
        return [
            "mdeCodigoEntidade" => $this->codigoTCE,
            "mdeBimestre" =>  PeriodoDePara::bimestre($this->periodo),
            "mdeMesAnoMovimento" => $this->periodo->getDataFinal($this->ano)->getDate(),
        ];
    }
}
