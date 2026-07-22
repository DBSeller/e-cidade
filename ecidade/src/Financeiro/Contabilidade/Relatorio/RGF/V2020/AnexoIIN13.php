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
use Exception;

/**
 * Class AnexoI
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020
 */
class AnexoIIN13 extends AnexoI
{
    /**
     * Código do relatório
     *
     * @type integer
     */
    const CODIGO_RELATORIO = 222;


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
        $this->linhaFinalQuadroDespesa = 18;
        $this->linhaDespesaCorrenteLiquida = 19;
        $this->linhaDespesaTotalComPessoal = 23;
        $this->linhaDespesasNaoComputadas = 12;
        $this->totalizadoresQuadroDespesa = [1, 2, 7, 12, 18];
        $this->linhasTotalizadoras = [
            2 => [3, 4, 5, 6],
            7 => [8, 9, 10],
            1 => [2, 7, 11],
            12 => [13, 14, 15, 16, 17]
        ];
        $this->linhasParaSubtrair = [6, 17];
    }


    /**
     * Retorna um stdClass com todos os dados necessários para a emissão.
     *
     * @return \stdClass[]
     * @throws Exception
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

    protected function processarCalculoPorMeses()
    {
        parent::processarCalculoPorMeses();

        $this->calculaLinha6();
    }

    /**
     * Calcula a RCL
     *
     * @throws Exception
     */
    protected function calcularRCL()
    {
        $iLimiteMaximo = $this->getLimiteMaximo();
        $rcl = ReceitaCorrenteFactory::getInstance($this->iAno, $this->getPeriodo()->getCodigo());
        $rcl->setInstituicoes($this->getInstituicoes());
        $stdDadosRCL = $rcl->getDadosSimplificado();

        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[21]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $this->aLinhasConsistencia[22]->valor = $stdDadosRCL->valor_rcl_mdf -
            $stdDadosRCL->valor_rcl_transferencia_individual - $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[19]->valor;

        $this->aLinhasConsistencia[19]->percentual = ' - ';
        $this->aLinhasConsistencia[20]->percentual = 0;
        $this->aLinhasConsistencia[21]->percentual = 0;
        $this->aLinhasConsistencia[22]->percentual = ' - ';

        if ($nValorRCL) {
            $this->aLinhasConsistencia[20]->percentual = ($this->aLinhasConsistencia[20]->valor / $nValorRCL) * 100;
            $this->aLinhasConsistencia[21]->percentual = ($this->aLinhasConsistencia[21]->valor / $nValorRCL) * 100;
        }

        $this->aLinhasConsistencia[21]->percentual = 0;
        if ($this->aLinhasConsistencia[19]->valor) {
            $percentual = ($this->aLinhasConsistencia[21]->valor / $this->aLinhasConsistencia[19]->valor) * 100;
            $this->aLinhasConsistencia[21]->percentual = $percentual;
        }

        $this->processaValorManualPorLinhaEColuna(20, 0);
        $this->processaValorManualPorLinhaEColuna(21, 0);

        $linhaTotalDespesa = $this->aLinhasConsistencia[18];
        $this->aLinhasConsistencia[23]->valor = $linhaTotalDespesa->liquidado_ultimo_ano +
            $linhaTotalDespesa->rp_nao_processado;
        $nValorRCLCalcular = $this->aLinhasConsistencia[22]->valor;

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        $this->aLinhasConsistencia[23]->percentual = 0;
        if ($this->aLinhasConsistencia[19]->valor) {
            $percentual = ($this->aLinhasConsistencia[23]->valor / $this->aLinhasConsistencia[19]->valor) * 100;
            $this->aLinhasConsistencia[23]->percentual = $percentual;
        }

        $this->aLinhasConsistencia[24]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[24]->percentual = $iLimiteMaximo;
        $this->aLinhasConsistencia[25]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[25]->percentual = $nLimitePrudencial;
        $this->aLinhasConsistencia[26]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[26]->percentual = $nLimiteMaximoAlerta;
    }


    public function getMesesParaProcessar()
    {
        $meses = [];
        $anoInicial = $this->getDataInicio()->getAno();
        $mesInicial = (int)$this->getDataInicio()->getMes();

        $anoFinal = $this->getDataFinal()->getAno();
        $totalMeses = $mesInicial + 11;
        $mesCorrente = $mesInicial;
        $anoCorrente = $anoInicial;
        $mesColuna = 1;
        $listaMeses = \DBDate::getMesesExtenso();
        for ($mes = $mesInicial; $mes <= $totalMeses; $mes++) {
            if ($mesCorrente > 12) {
                $anoCorrente = $anoFinal;
                $mesCorrente = 1;
            }
            $nomeMes = mb_strtolower(\DBString::removerAcentuacao($listaMeses[$mesCorrente]));
            $meses[] = [
                "competencia" => "{$mesCorrente}/{$anoCorrente}",
                "mes" => $nomeMes,
                "ano" => $anoCorrente,
                "nome_coluna" => "mes_{$mesColuna}",
                "label" => \DBDate::getMesAbreviado($mesCorrente) . "/{$anoCorrente}"
            ];
            $mesCorrente++;
            $mesColuna++;
        }
        return $meses;
    }

    protected function agruparValoresNasLinhas()
    {
        parent::agruparValoresNasLinhas();
    }

    private function calculaLinha6()
    {
        // BUSCA OS DADOS DA RECEITA PARA CALCULAR
        $receitaMensal = new Mensal();
        $receitaMensal->setDataInicial($this->getDataInicio()->getDate());
        $receitaMensal->setDataFinal($this->getDataFinal()->getDate());
        $receitaMensal->setInstitucoes(explode("-", $this->sListaInstit));

        $meses = $this->getMesesParaProcessar();
        $rsReceitas = $receitaMensal->getDados();
        $totalLinhas = pg_num_rows($rsReceitas);
        $receitas = [];
        for ($i = 0; $i < $totalLinhas; $i++) {
            $dadosReceita = \db_utils::fieldsMemory($rsReceitas, $i);
            if (empty($receitas[$dadosReceita->o57_fonte])) {
                $receita = new \stdClass();
                $receita->estrutural = $dadosReceita->o57_fonte;
                $receita->descricao = $dadosReceita->o57_descr;
                $receita->previsao_atualizada = 0;
                foreach ($meses as $mes) {
                    $receita->{$mes["nome_coluna"]} = 0;
                }
                $receitas[$dadosReceita->o57_fonte] = $receita;
            }

            $receita = $receitas[$dadosReceita->o57_fonte];
            $receita->previsao_atualizada += $dadosReceita->o70_valor + $dadosReceita->adicional;
            foreach ($meses as $mes) {
                if ($dadosReceita->o70_anousu == $mes["ano"]) {
                    $receita->{$mes["nome_coluna"]} += $dadosReceita->{$mes["mes"]};
                }
            }
        }

        // calcula apenas a linha 6
        $linha6 = $this->aLinhasConsistencia[6];

        $linha6->previsao_atualizada = 0;
        $linha6->total = 0;
        // cria coluna dos meses
        foreach ($meses as $mes) {
            $linha6->{$mes["nome_coluna"]} = 0;
        }

        // calcula as receitas
        foreach ($receitas as $receita) {
            $receitaClonada = clone $receita;

            $parametrosConfigurados = $linha6->parametros;
            foreach ($parametrosConfigurados->contas as $contas) {
                $match = $linha6->oLinhaRelatorio->match($contas, $parametrosConfigurados->orcamento, $receita, 3);
                if ($match->match) {
                    // Contas de exclusão, invertemos o sinal
                    if ($match->exclusao) {
                        $receitaClonada->previsao_atualizada *= -1;
                        foreach ($meses as $mes) {
                            $receitaClonada->{$mes["nome_coluna"]} *= -1;
                        }
                    }

                    $linha6->previsao_atualizada += $receitaClonada->previsao_atualizada;
                    foreach ($meses as $mes) {
                        $linha6->{$mes["nome_coluna"]} += $receitaClonada->{$mes["nome_coluna"]};
                        $linha6->total += $linha6->{$mes["nome_coluna"]};
                    }
                }
            }
        }

        $linha6->liquidado_ultimo_ano = $linha6->total;

        if (empty($linha6->meses)) {
            $linha6->meses = [];
        }

        foreach ($meses as $mes) {
            $this->aValoresDespesaPorLinhaMes[$linha6->ordem][$mes['competencia']] = $linha6->{$mes["nome_coluna"]};
        }
    }
}
