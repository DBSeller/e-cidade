<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoI as AnexoI2017;

class AnexoI extends AnexoI2017
{
    const CODIGO_RELATORIO = 175;
    const LINHA_RECEITAS_INTRA_ORCAMENTARIAS_II = 65;
    const LINHA_SUBTOTAL_RECEITAS_III = 66;
    const LINHA_OPERACOES_CREDITO_REFINANCIAMENTO_IV = 67;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_V = 74;
    const LINHA_DEFICIT_VI = 75;
    const LINHA_TOTAL_VII = 76;
    const LINHA_SALDOS_EXERCICIOS_ANTERIORES = 77;
    const LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS = 79;
    const LINHA_REABERTURA_CREDITOS_ADICIONAIS = 80;
    const LINHA_DESPESAS_EXCETO_INTRA_ORCAMENTARIAS_VIII = 81;
    const LINHA_RESERVA_CONTIGENCIA = 92;
    const LINHA_DESPESAS_INTRA_ORCAMENTARIAS_IX = 93;
    const LINHA_SUBTOTAL_DESPESAS_X = 94;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_XII = 102;
    const LINHA_SUPERAVIT_XIII = 103;
    const LINHA_TOTAL_XIV = 104;
    const LINHA_RESERVA_RPPS = 105;

    protected function processar()
    {
        parent::processar();

        $this->processarSaldosExerciciosAnteriores();
    }

    protected function calcularSuperavitDeficit()
    {
        parent::calcularSuperavitDeficit();

        $linhaSuperavitXIII = &$this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII];
        $linhaSuperavitXIII->empenhado_atebim = 0;
        $linhaSuperavitXIII->desppag = 0;

        //Calculo de Superavit de despesas empenhadas ate o bimestre
        if ($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim
            > $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim
        ) {
            $nCalculoEmpenhado = abs(
                $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim -
                $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim
            );

            $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->empenhado_atebim = $nCalculoEmpenhado;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->empenhado_atebim += $nCalculoEmpenhado;
        }

        //Calculo de Superavit de despesas pagas ate o bimestre
        if (isset($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->despaga)
            && $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim
                > $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->despaga
            ) {
            $nCalculoPago = abs(
                $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->desppag
                - $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim
            );

            $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->desppag = $nCalculoPago;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->desppag += $nCalculoPago;
        }
    }

    private function processarSaldosExerciciosAnteriores()
    {
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->previni = '-';
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->previni = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->recnobim = '-';
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->recnobim = '-';
    }

    /**
     * Retorna Os dados simplificados do Relatorio
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {

        $this->processar();
        
        $linhaSubtotalRef = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII];
        $nSaldoExerciciosAnteriores = $this->aLinhasConsistencia[static::LINHA_SALDOS_EXERCICIOS_ANTERIORES]->recatebim;
        $nEmpenhadasSemSuperavit = $linhaSubtotalRef->empenhado_atebim;

        $oDados = new \stdClass();
        $oDados->nPrevisaoInicial = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->previni;
        $oDados->nPrevisaoAtualizada = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->prevatu;
        $oDados->nReceitasRealizadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim;
        $oDados->nDeficitOrcamentario = $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recatebim;
        $oDados->nSaldoExerciciosAnteriores = $nSaldoExerciciosAnteriores;

        $oDados->nDotacaoInicial = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotini;
        $oDados->nDotacaoAtualizada = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotatu;
        $oDados->nCreditoAdicional = $oDados->nDotacaoAtualizada - $oDados->nDotacaoInicial;
        $oDados->nEmpenhadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->empenhado_atebim;
        $oDados->nEmpenhadasSemSuperavit = $nEmpenhadasSemSuperavit;
        $oDados->nLiquidadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->liquidado_atebim;
        $oDados->nPagas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->desppag;
        $oDados->nSuperavitOrcamentario = $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim;

        return $oDados;
    }
}
