<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as ReceitaCorrenteFactory;

/**
 * Class AnexoI
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020
 */
class AnexoIRondonia extends AnexoI
{
    /**
     * Código do relatório
     *
     * @type integer
     */
    const CODIGO_RELATORIO = 220;


    /**
     * AnexoIRondonia constructor.
     * @param $iAno
     * @param $oPeriodo
     * @param $aInstituicoes
     *
     * @throws \ParameterException
     */
    public function __construct($iAno, $oPeriodo, $aInstituicoes)
    {
        parent::__construct($iAno, $oPeriodo, $aInstituicoes);
        $this->linhaFinalQuadroDespesa = 19;
        $this->linhaDespesaCorrenteLiquida = 20;
        $this->linhaDespesaTotalComPessoal = 24;
        $this->linhaDespesasNaoComputadas = 11;
        $this->totalizadoresQuadroDespesa = [1, 2, 6, 11, 19];
        $this->linhasTotalizadoras = [
            2  => [3, 4, 5],
            6  => [7, 8, 9],
            1  => [2, 6, 10],
            11 => [12, 13, 14, 15, 16, 17, 18]
        ];
    }

    /**
     * Retorna um stdClass com todos os dados necessários para a emissão.
     *
     * @return \stdClass[]
     * @throws \ParameterException
     */
    public function getDados()
    {
        parent::getDados();
        $this->inicializaValoresDespesaPorLinhaMes();
        $this->processarCalculoPorMeses();
        $this->processarReceita();
        $this->calculaValorManual();
        $this->totalizarColunas();
        $this->calcularRCL();
        $this->agruparValoresNasLinhas();
        return $this->aLinhasConsistencia;
    }

    /**
     * Processa as linhas configuradas como receita.
     * @return bool
     * @throws \ParameterException
     */
    protected function processarReceita()
    {
        if (empty($this->aLinhasProcessarReceita)) {
            return false;
        }

        foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {
            list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
            $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
            $oDataInicialPeriodo = new \DBDate("01/{$iMes}/{$iAno}");
            $oDataFinalPeriodo = new \DBDate("{$iUltimoDiaMes}/{$iMes}/{$iAno}");

            $sWhereReceita = "o70_instit in ({$this->getInstituicoes()})";
            $rsReceitaSaldo = ReceitaSaldo(
                11,
                1,
                3,
                true,
                $sWhereReceita,
                $iAno,
                $oDataInicialPeriodo->getDate(),
                $oDataFinalPeriodo->getDate()
            );

            foreach ($this->aLinhasProcessarReceita as $iLinha) {
                $oLinha = $this->aLinhasConsistencia[$iLinha];
                $oLinha->mes = 0;
                $oLinha->liquidado_ultimo_ano = 0;
                $oLinha->rp_nao_processado = 0;
                if (empty($oLinha->colunas[0]->o116_formula)) {
                    $oLinha->colunas[0]->o116_formula = '#saldo_arrecadado';
                }
                $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(0));
                \RelatoriosLegaisBase::calcularValorDaLinha(
                    $rsReceitaSaldo,
                    $oLinha,
                    $aColunasProcessar,
                    \RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                );

                $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] = $oLinha->mes;
                $this->limparEstruturaBalanceteReceita();
            }
        }
    }



    /**
     * Calcula a RCL
     *
     * @throws \Exception
     */
    protected function calcularRCL()
    {
        $iLimiteMaximo = $this->getLimiteMaximo();
        $rcl = ReceitaCorrenteFactory::getInstance($this->iAno, $this->getPeriodo()->getCodigo());
        $rcl->setInstituicoes($this->getInstituicoes());
        $stdDadosRCL = $rcl->getDadosSimplificado();

        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[21]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[22]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $this->aLinhasConsistencia[23]->valor = $stdDadosRCL->valor_rcl_mdf -
            $stdDadosRCL->valor_rcl_transferencia_individual - $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[20]->valor;

        $this->aLinhasConsistencia[20]->percentual = ' - ';
        $this->aLinhasConsistencia[21]->percentual = 0;
        $this->aLinhasConsistencia[22]->percentual = 0;
        $this->aLinhasConsistencia[23]->percentual = ' - ';

        if ($nValorRCL) {
            $this->aLinhasConsistencia[21]->percentual = ($this->aLinhasConsistencia[21]->valor / $nValorRCL) * 100;
            $this->aLinhasConsistencia[22]->percentual = ($this->aLinhasConsistencia[22]->valor / $nValorRCL) * 100;
        }

        $this->aLinhasConsistencia[25]->percentual = 0;
        if ($this->aLinhasConsistencia[20]->valor) {
            $percentual = ($this->aLinhasConsistencia[25]->valor / $this->aLinhasConsistencia[20]->valor) * 100;
            $this->aLinhasConsistencia[25]->percentual = $percentual;
        }


        $linhaTotalDespesa = $this->aLinhasConsistencia[19];
        $this->aLinhasConsistencia[24]->valor = $linhaTotalDespesa->liquidado_ultimo_ano +
            $linhaTotalDespesa->rp_nao_processado;
        $nValorRCLCalcular = $this->aLinhasConsistencia[23]->valor;

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        $this->aLinhasConsistencia[24]->percentual = 0;
        if ($this->aLinhasConsistencia[20]->valor) {
            $percentual = ($this->aLinhasConsistencia[24]->valor / $this->aLinhasConsistencia[20]->valor) * 100;
            $this->aLinhasConsistencia[24]->percentual = $percentual;
        }

        $this->aLinhasConsistencia[25]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[25]->percentual = $iLimiteMaximo;
        $this->aLinhasConsistencia[26]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[26]->percentual = $nLimitePrudencial;
        $this->aLinhasConsistencia[27]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[27]->percentual = $nLimiteMaximoAlerta;
    }
}
