<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as ReceitaCorrenteFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoI as AnexoI;

/**
 * Class AnexoI
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021
 */
class AnexoIRondonia extends AnexoI
{
    /**
     * Código do relatório
     *
     * @type integer
     */
    const CODIGO_RELATORIO = 249;


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
        $this->linhaFinalQuadroDespesa = 20;
        $this->linhaDespesaCorrenteLiquida = 21;
        $this->linhaDespesaTotalComPessoal = 25;
        $this->linhaDespesasNaoComputadas = 12;
        $this->totalizadoresQuadroDespesa = [1, 2, 6, 12, 20];
        $this->linhasTotalizadoras = [
            2  => [3, 4],
            6  => [7, 8],
            1  => [2, 6, 10, 11],
            12 => [13, 14, 15, 16, 17, 18, 19]
        ];

        $this->linhasParaSubtrair = [
            20 => [ 1, 12 ]
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
   * Dados preparados para serem emitidos no Anexo VI - Simplificado
   * @return \stdClass
   */
    public function getDadosSimplificado()
    {
        $this->getDados();

        $oStdAnexo = new \stdClass();
        $oStdAnexo->total_despesa_pessoal = round($this->aLinhasConsistencia[25]->valor, 2);
        $oStdAnexo->percentual_despesa_pessoal = round($this->aLinhasConsistencia[25]->percentual, 2);

        $oStdAnexo->total_limite_maximo = round($this->aLinhasConsistencia[26]->valor, 2);
        $oStdAnexo->percentual_limite_maximo = round($this->aLinhasConsistencia[26]->percentual, 2);

        $oStdAnexo->total_limite_prudencial = round($this->aLinhasConsistencia[27]->valor, 2);
        $oStdAnexo->percentual_limite_prudencial = round($this->aLinhasConsistencia[27]->percentual, 2);

        $oStdAnexo->total_limite_alerta = round($this->aLinhasConsistencia[28]->valor, 2);
        $oStdAnexo->percentual_limite_alerta = round($this->aLinhasConsistencia[28]->percentual, 2);

        return $oStdAnexo;
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

        $this->aLinhasConsistencia[21]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[22]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[23]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $this->aLinhasConsistencia[24]->valor = $stdDadosRCL->valor_rcl_mdf -
            $stdDadosRCL->valor_rcl_transferencia_individual - $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[21]->valor;

        $this->aLinhasConsistencia[21]->percentual = ' - ';
        $this->aLinhasConsistencia[22]->percentual = 0;
        $this->aLinhasConsistencia[23]->percentual = 0;
        $this->aLinhasConsistencia[24]->percentual = ' - ';

        if ($nValorRCL) {
            $this->aLinhasConsistencia[22]->percentual = ($this->aLinhasConsistencia[22]->valor / $nValorRCL) * 100;
            $this->aLinhasConsistencia[23]->percentual = ($this->aLinhasConsistencia[23]->valor / $nValorRCL) * 100;
        }

        $this->aLinhasConsistencia[26]->percentual = 0;
        if ($this->aLinhasConsistencia[21]->valor) {
            $percentual = ($this->aLinhasConsistencia[26]->valor / $this->aLinhasConsistencia[21]->valor) * 100;
            $this->aLinhasConsistencia[26]->percentual = $percentual;
        }


        $linhaTotalDespesa = $this->aLinhasConsistencia[20];
        $this->aLinhasConsistencia[25]->valor = $linhaTotalDespesa->liquidado_ultimo_ano +
            $linhaTotalDespesa->rp_nao_processado;
        $nValorRCLCalcular = $this->aLinhasConsistencia[24]->valor;

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        $this->aLinhasConsistencia[25]->percentual = 0;
        if ($this->aLinhasConsistencia[21]->valor) {
            $percentual = ($this->aLinhasConsistencia[25]->valor / $this->aLinhasConsistencia[21]->valor) * 100;
            $this->aLinhasConsistencia[25]->percentual = $percentual;
        }

        $this->aLinhasConsistencia[26]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[26]->percentual = $iLimiteMaximo;
        $this->aLinhasConsistencia[27]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[27]->percentual = $nLimitePrudencial;
        $this->aLinhasConsistencia[28]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[28]->percentual = $nLimiteMaximoAlerta;
    }
}
