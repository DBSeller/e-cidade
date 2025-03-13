<?php


namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RelatoriosLegaisBaseMSC;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoII as AnexoII2018;
use Exception;
use Periodo;

/**
 * Class AnexoII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019
 */
class AnexoII extends AnexoII2018
{
    /**
     *
     */
    const CODIGO_RELATORIO = 198;

    const LINHA_DIVIDA_CONSOLIDADA_DC_I = 1;
    const LINHA_DIVIDA_CONTRATUAL = 3;
    const LINHA_EMPRESTIMOS = 4;
    const LINHA_FINANCIAMENTOS = 8;
    const LINHA_PARCELAMENTO_E_RENEGOCIACAO_DE_DIVIDAS = 11;
    const LINHA_DEDUCOES_II = 20;
    const LINHA_DISPONIBILIDADE_DE_CAIXA = 21;
    const LINHA_DIVIDA_CONSOLIDADA_LIQUIDA_DCL_III = 25;
    const LINHA_RECEITA_CORRENTE_LIQUIDA_RCL = 26;
    const LINHA_PERCENTUAL_DA_DC_SOBRE_A_RCL_I_RCL = 27;
    const LINHA_PERCENTUAL_DA_DCL_SOBRE_A_RCL_III_RCL = 28;
    const LINHA_LIMITE_DEFINIDO_POR_RESOLUCAO_DO_SENADO_FEDERAL_120 = 29;
    const LINHA_LIMITE_DE_ALERTA_108 = 30;

    /**
     * @throws Exception
     */
    public function processar()
    {
        if (empty($this->aLinhas)) {
            $this->aLinhas = $this->getDados();
        }

        foreach (array_keys($this->aLinhasConsistencia) as $ordem) {
            $this->aLinhasConsistencia[$ordem]->primeiro_periodo = 0;
            $this->aLinhasConsistencia[$ordem]->segundo_periodo = 0;
            $this->aLinhasConsistencia[$ordem]->terceiro_periodo = 0;
        }

        $nRCLExercicioAnterior = array_sum($this->oRCL->calcularRCLAnterior());
        $this->aLinhas[static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL]->saldo_exercicio_anterior = $nRCLExercicioAnterior;

        foreach ($this->aPeriodoCalcular[$this->oPeriodo->getCodigo()] as $codigoPeriodo) {
            $ordemColuna = $this->aColunaRecalcularPeriodo[$codigoPeriodo];
            $dataFinal = Periodo::dataFinalPeriodo($codigoPeriodo, $this->iAno);

            $relatoriosLegaisMSC = new RelatoriosLegaisBaseMSC(
                $this->iAno,
                static::CODIGO_RELATORIO,
                $codigoPeriodo
            );
            $relatoriosLegaisMSC->executarMSC();

            foreach ($this->linhasMSC as $linhaMSC) {
                $dadosMSC = $relatoriosLegaisMSC->getDados();
                switch ($ordemColuna) {
                    case 1:
                        $this->aLinhasConsistencia[$linhaMSC]->primeiro_periodo = $dadosMSC[$linhaMSC]->primeiro_periodo;
                        break;
                    case 2:
                        $this->aLinhasConsistencia[$linhaMSC]->segundo_periodo = $dadosMSC[$linhaMSC]->segundo_periodo;
                        break;
                    case 3:
                        $this->aLinhasConsistencia[$linhaMSC]->terceiro_periodo = $dadosMSC[$linhaMSC]->terceiro_periodo;
                        break;
                }
            }

            $this->processarBalanceteVerificacaoParaColunaPorData($ordemColuna, $this->oDataInicio, $dataFinal);

            $nValorRCL = $this->oRCL->somaRCLPeriodo($codigoPeriodo);

            switch ($ordemColuna) {
                case 1:
                    $this->aLinhasConsistencia[static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL]->primeiro_periodo = $nValorRCL;
                    break;
                case 2:
                    $this->aLinhasConsistencia[static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL]->segundo_periodo = $nValorRCL;
                    break;
                case 3:
                    $this->aLinhasConsistencia[static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL]->terceiro_periodo = $nValorRCL;
                    break;
            }
        }

        $anoAnterior = $this->iAno - 1;
        $relatoriosLegaisMSC = new RelatoriosLegaisBaseMSC(
            $anoAnterior,
            static::CODIGO_RELATORIO,
            $this->getPeriodo()->getCodigo()
        );
        $relatoriosLegaisMSC->setDataFinal(new DBDate("{$anoAnterior}-12-31"));
        $relatoriosLegaisMSC->executarMSC();

        foreach ($this->linhasMSC as $linhaMSC) {
            $dadosMSC = $relatoriosLegaisMSC->getDados();
            $this->aLinhasConsistencia[$linhaMSC]->saldo_exercicio_anterior = $dadosMSC[$linhaMSC]->saldo_exercicio_anterior;
        }

        $aLinhaProcessarManual = array(
            static::LINHA_DIVIDA_CONSOLIDADA_DC_I,
            static::LINHA_DIVIDA_CONTRATUAL,
            static::LINHA_EMPRESTIMOS,
            static::LINHA_FINANCIAMENTOS,
            static::LINHA_PARCELAMENTO_E_RENEGOCIACAO_DE_DIVIDAS,
            static::LINHA_DEDUCOES_II,
            static::LINHA_DISPONIBILIDADE_DE_CAIXA,
            static::LINHA_DIVIDA_CONSOLIDADA_LIQUIDA_DCL_III,
            static::LINHA_PERCENTUAL_DA_DC_SOBRE_A_RCL_I_RCL,
            static::LINHA_PERCENTUAL_DA_DCL_SOBRE_A_RCL_III_RCL,
            static::LINHA_LIMITE_DEFINIDO_POR_RESOLUCAO_DO_SENADO_FEDERAL_120,
            static::LINHA_LIMITE_DE_ALERTA_108
        );

        foreach ($aLinhaProcessarManual as $aLinha) {
            $this->processarFormulaDaLinha($aLinha);
        }

        $this->aLinhas = $this->getDados();
    }
}
