<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoI as AnexoI2018;

class AnexoI extends AnexoI2018
{
    const CODIGO_RELATORIO = 190;
    const LINHA_RECEITAS_INTRA_ORCAMENTARIAS_II = 65;
    const LINHA_SUBTOTAL_RECEITAS_III = 66;
    const LINHA_OPERACOES_CREDITO_REFINANCIAMENTO_IV = 67;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_V = 74;
    const LINHA_DEFICIT_VI = 75;
    const LINHA_TOTAL_VII = 76;
    const LINHA_SALDOS_EXERCICIOS_ANTERIORES = 77;
    const LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS = 79;
    const LINHA_DESPESAS_EXCETO_INTRA_ORCAMENTARIAS_VIII = 80;
    const LINHA_RESERVA_CONTIGENCIA = 91;
    const LINHA_DESPESAS_INTRA_ORCAMENTARIAS_IX = 92;
    const LINHA_SUBTOTAL_DESPESAS_X = 93;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_XII = 101;
    const LINHA_SUPERAVIT_XIII = 102;
    const LINHA_TOTAL_XIV = 103;
    const LINHA_RESERVA_RPPS = 104;

    protected function processar()
    {
        parent::processar();
        $this->processarSaldosExerciciosAnteriores();
        $this->processarDadosPorAno();
    }

    /**
     * Altera o label de linhas do relatório quando o ano for 2020.
     */
    private function processarDadosPorAno()
    {
        if ($this->iAnoUsu >= 2020) {
            $dadosSobreescreve = array(
                74 => 'TOTAL DAS RECEITAS (V) = (III + IV)',
                76 => 'TOTAL COM DÉFICIT (VII) = (V + VI)',
                101 => 'TOTAL DAS DESPESAS (XII) = (X + XI)',
                103 => 'TOTAL COM SUPERÁVIT (XIV) = (XII + XIII)',
            );
            foreach ($dadosSobreescreve as $linha => $novaDescricao) {
                $this->aLinhasConsistencia[$linha]->descricao = $novaDescricao;
            }
        }
    }


    protected function calcularSuperavitDeficit()
    {
        parent::calcularSuperavitDeficit();

        $linhaSuperavitXIII = &$this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII];
        $linhaSuperavitXIII->empenhado_atebim = 0;
        $linhaSuperavitXIII->desppag = 0;

        //Calculo de Superavit de despesas empenhadas ate o bimestre
        $linhaRefinanciamento = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim;
        $subRefinanciamento = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim;
        if ($linhaRefinanciamento > $subRefinanciamento) {
            $nCalculoEmpenhado = abs($subRefinanciamento - $linhaRefinanciamento);
            $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->empenhado_atebim = $nCalculoEmpenhado;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->empenhado_atebim += $nCalculoEmpenhado;
        }

        //Calculo de Superavit de despesas pagas ate o bimestre. calculado tvm as despesas pagas
        $linhaRefDesPag = 0;
        if (isset($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->despaga)) {
            $linhaRefDesPag = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->despaga;
        }
        if ($linhaRefinanciamento > $linhaRefDesPag) {
            //$nCalculoPago = abs($linhaRefDesPag - $linhaRefinanciamento);
            $nCalculoPago = abs($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->desppag -
                $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim);
            $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->desppag = $nCalculoPago;
            $totalSuperavit  = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->desppag +
                $nCalculoPago;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->desppag = $totalSuperavit;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->liquidado_nobim = '-';
        }
    }

    private function processarSaldosExerciciosAnteriores()
    {
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->previni = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->recnobim = '-';
    }

    protected function calcularSuplementacao()
    {
        $oDaoOrcSuplem = new \cl_orcsuplem();
        $aWhereCreditos = array(
            "o46_tiposup in (1012, 1013)",
            "o49_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
            "o46_instit in ({$this->getInstituicoes()})"
        );
        $sSqlBuscaCreditos = $oDaoOrcSuplem->sql_query_suplementacoes(
            null,
            "coalesce(sum(o47_valor), 0) as total",
            null,
            implode(" and ", $aWhereCreditos)
        );
        $rsBuscaCreditos = db_query($sSqlBuscaCreditos);
        if (!$rsBuscaCreditos) {
            throw new \Exception("Ocorreu um erro na busca dos valores
             de suplementação da coluna CRÉDITOS ADICIONAIS.");
        }

        $nValorCreditos = \db_utils::fieldsMemory($rsBuscaCreditos, 0)->total;

        if (!isset($this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->prevatu)) {
            $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->prevatu = 0;
        }
        if (!isset($this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->recatebim)) {
            $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->recatebim = 0;
        }
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->prevatu += $nValorCreditos;
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->recatebim += $nValorCreditos;
    }

    /**
     * Retorna Os dados simplificados do Relatorio
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {

        $this->processar();

        $oDados = new \stdClass();
        $oDados->nPrevisaoInicial = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->previni;
        $oDados->nPrevisaoAtualizada = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->prevatu;
        $oDados->nReceitasRealizadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim;
        $oDados->nDeficitOrcamentario = $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recatebim;
        $valor = $this->aLinhasConsistencia[static::LINHA_SALDOS_EXERCICIOS_ANTERIORES]->recatebim;
        $oDados->nSaldoExerciciosAnteriores = $valor;

        $oDados->nDotacaoInicial = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotini;
        $oDados->nDotacaoAtualizada = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotatu;
        $oDados->nCreditoAdicional = $oDados->nDotacaoAtualizada - $oDados->nDotacaoInicial;
        $oDados->nEmpenhadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim;
        $valor = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim;
        $oDados->nEmpenhadasSemSuperavit = $valor;
        $oDados->nLiquidadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->liquidado_atebim;
        $oDados->nPagas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->desppag;
        $oDados->nSuperavitOrcamentario = $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim;

        return $oDados;
    }
}
