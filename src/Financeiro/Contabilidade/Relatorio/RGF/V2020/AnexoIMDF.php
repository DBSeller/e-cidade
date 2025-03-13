<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020;

use ECidade\Financeiro\Contabilidade\Balancete\Receita\Mensal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoI as AnexoI2018;

use ECidade\Financeiro\Contabilidade\Calculo\Despesa;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as ReceitaCorrenteFactory;

/**
 * Class AnexoI
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020
 */
class AnexoIMDF extends AnexoI
{
    /**
     * Código do relatório
     *
     * @type integer
     */
    const CODIGO_RELATORIO = 221;



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
        $this->linhaFinalQuadroDespesa = 16;
        $this->linhaDespesaCorrenteLiquida = 17;
        $this->linhaDespesaTotalComPessoal = 21;
        $this->linhaDespesasNaoComputadas = 11;
        $this->totalizadoresQuadroDespesa = [1, 2, 6, 11, 16];
        $this->linhasTotalizadoras = [
            2  => [3, 4, 5],
            6  => [7, 8, 9],
            1  => [2, 6, 10],
            11 => [12, 13, 14, 15]
        ];
    }


    /**
     * Retorna um stdClass com todos os dados necessários para a emissão.
     *
     * @return \stdClass[]
     * @throws \ParameterException
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        parent::getDados($trazerConfiguracaoPadrao);
        $this->inicializaValoresDespesaPorLinhaMes();
        $this->processarCalculoPorMeses();
        $this->calculaValorManual();
        $this->totalizarColunas();
        $this->calcularRCL();
        $this->agruparValoresNasLinhas();
        return $this->aLinhasConsistencia;
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

        $this->aLinhasConsistencia[17]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[18]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_mdf -
            $stdDadosRCL->valor_rcl_transferencia_individual - $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[17]->valor;

        $this->aLinhasConsistencia[17]->percentual = ' - ';
        $this->aLinhasConsistencia[18]->percentual = 0;
        $this->aLinhasConsistencia[19]->percentual = 0;
        $this->aLinhasConsistencia[20]->percentual = ' - ';

        if ($nValorRCL) {
            $this->aLinhasConsistencia[18]->percentual = ($this->aLinhasConsistencia[18]->valor / $nValorRCL) * 100;
            $this->aLinhasConsistencia[19]->percentual = ($this->aLinhasConsistencia[19]->valor / $nValorRCL) * 100;
        }

        $this->aLinhasConsistencia[22]->percentual = 0;
        if ($this->aLinhasConsistencia[17]->valor) {
            $percentual = ($this->aLinhasConsistencia[22]->valor / $this->aLinhasConsistencia[17]->valor) * 100;
            $this->aLinhasConsistencia[22]->percentual = $percentual;
        }

        $linhaTotalDespesa = $this->aLinhasConsistencia[16];
        $this->aLinhasConsistencia[21]->valor = $linhaTotalDespesa->liquidado_ultimo_ano +
            $linhaTotalDespesa->rp_nao_processado;

        $nValorRCLCalcular = $this->aLinhasConsistencia[20]->valor;

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        $this->aLinhasConsistencia[21]->percentual = 0;
        if ($this->aLinhasConsistencia[17]->valor) {
            $percentual = ($this->aLinhasConsistencia[21]->valor / $this->aLinhasConsistencia[17]->valor) * 100;
            $this->aLinhasConsistencia[21]->percentual = $percentual;
        }

        $this->aLinhasConsistencia[22]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[22]->percentual = $iLimiteMaximo;
        $this->aLinhasConsistencia[23]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[23]->percentual = $nLimitePrudencial;
        $this->aLinhasConsistencia[24]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[24]->percentual = $nLimiteMaximoAlerta;
    }
}
