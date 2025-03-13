<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as ReceitaCorrenteFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoI as AnexoI;

/**
 * Class AnexoI
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021
 */
class AnexoIMDF extends AnexoI
{
    /**
     * Código do relatório
     *
     * @type integer
     */
    const CODIGO_RELATORIO = 247;



    /**
     * AnexoIRondonia constructor.
     *
     * @param $iAno
     * @param $oPeriodo
     * @param $aInstituicoes
     *
     * @throws \ParameterException
     */
    public function __construct($iAno, $oPeriodo, $aInstituicoes)
    {

        parent::__construct($iAno, $oPeriodo, $aInstituicoes);
        $this->linhaFinalQuadroDespesa = 17;
        $this->linhaDespesaCorrenteLiquida = 18;
        $this->linhaDespesaTotalComPessoal = 22;
        $this->linhaDespesasNaoComputadas = 12;
        $this->totalizadoresQuadroDespesa = [1, 2, 6, 12, 17];
        $this->linhasTotalizadoras = [
            2  => [3, 4],
            6  => [7, 8],
            1  => [2, 6, 10, 11],
            12 => [13, 14, 15, 16]
        ];

        $this->linhasParaSubtrair = [
            17 => [1, 12]
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
        $oStdAnexo->total_despesa_pessoal = round($this->aLinhasConsistencia[22]->valor, 2);
        $oStdAnexo->percentual_despesa_pessoal = round($this->aLinhasConsistencia[22]->percentual, 2);

        $oStdAnexo->total_limite_maximo = round($this->aLinhasConsistencia[23]->valor, 2);
        $oStdAnexo->percentual_limite_maximo = round($this->aLinhasConsistencia[23]->percentual, 2);

        $oStdAnexo->total_limite_prudencial = round($this->aLinhasConsistencia[24]->valor, 2);
        $oStdAnexo->percentual_limite_prudencial = round($this->aLinhasConsistencia[24]->percentual, 2);

        $oStdAnexo->total_limite_alerta = round($this->aLinhasConsistencia[25]->valor, 2);
        $oStdAnexo->percentual_limite_alerta = round($this->aLinhasConsistencia[25]->percentual, 2);

        return $oStdAnexo;
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

        $this->aLinhasConsistencia[18]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $this->aLinhasConsistencia[21]->valor = $stdDadosRCL->valor_rcl_mdf -
            $stdDadosRCL->valor_rcl_transferencia_individual - $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[18]->valor;

        $this->aLinhasConsistencia[18]->percentual = ' - ';
        $this->aLinhasConsistencia[19]->percentual = 0;
        $this->aLinhasConsistencia[20]->percentual = 0;
        $this->aLinhasConsistencia[21]->percentual = ' - ';

        if ($nValorRCL) {
            $this->aLinhasConsistencia[19]->percentual = ($this->aLinhasConsistencia[19]->valor / $nValorRCL) * 100;
            $this->aLinhasConsistencia[20]->percentual = ($this->aLinhasConsistencia[20]->valor / $nValorRCL) * 100;
        }

        $this->aLinhasConsistencia[23]->percentual = 0;
        if ($this->aLinhasConsistencia[18]->valor) {
            $percentual = ($this->aLinhasConsistencia[23]->valor / $this->aLinhasConsistencia[18]->valor) * 100;
            $this->aLinhasConsistencia[23]->percentual = $percentual;
        }

        $linhaTotalDespesa = $this->aLinhasConsistencia[17];
        $this->aLinhasConsistencia[22]->valor = $linhaTotalDespesa->liquidado_ultimo_ano +
            $linhaTotalDespesa->rp_nao_processado;

        $nValorRCLCalcular = $this->aLinhasConsistencia[21]->valor;

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        $this->aLinhasConsistencia[22]->percentual = 0;
        if ($this->aLinhasConsistencia[18]->valor) {
            $percentual = ($this->aLinhasConsistencia[22]->valor / $this->aLinhasConsistencia[18]->valor) * 100;
            $this->aLinhasConsistencia[22]->percentual = $percentual;
        }

        $this->aLinhasConsistencia[23]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[23]->percentual = $iLimiteMaximo;
        $this->aLinhasConsistencia[24]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[24]->percentual = $nLimitePrudencial;
        $this->aLinhasConsistencia[25]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[25]->percentual = $nLimiteMaximoAlerta;
    }
}
