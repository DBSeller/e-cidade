<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;

class AnexoI extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{

    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 163;
    const LINHA_RECEITAS_EXCETO_INTRA_ORCAMENTARIAS_I = 1;
    const LINHA_RECEITAS_CORRENTES = 2;
    const LINHA_RECEITAS_INTRA_ORCAMENTARIAS_II = 64;
    const LINHA_SUBTOTAL_RECEITAS_III = 65;
    const LINHA_OPERACOES_CREDITO_REFINANCIAMENTO_IV = 66;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_V = 73;
    const LINHA_DEFICIT_VI = 74;
    const LINHA_TOTAL_VII = 75;
    const LINHA_SALDOS_EXERCICIOS_ANTERIORES = 76;
    const LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS = 78;
    const LINHA_REABERTURA_CREDITOS_ADICIONAIS = 79;
    const LINHA_DESPESAS_EXCETO_INTRA_ORCAMENTARIAS_VIII = 80;
    const LINHA_RESERVA_CONTIGENCIA = 89;
    const LINHA_DESPESAS_INTRA_ORCAMENTARIAS_IX = 90;
    const LINHA_SUBTOTAL_DESPESAS_X = 91;
    const LINHA_SUBTOTAL_REFINANCIAMENTO_XII = 99;
    const LINHA_SUPERAVIT_XIII = 100;
    const LINHA_TOTAL_XIV = 101;
    const LINHA_RESERVA_RPPS = 102;

    /**
     * @var \Instituicao[]
     */
    private $aInstituicoesReservaContigente = array();

    /**
     * @var \Instituicao[]
     */
    private $aInstituicoesReservaRPPS = array();

    protected $aLinhasProcessar = array();

    /**
     * AnexoV constructor.
     *
     * @param int $iAnoUsu
     * @param int $iCodigoRelatorio
     * @param int $iCodigoPeriodo
     */
    public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
        $this->aLinhasProcessar = array(
            static::LINHA_RECEITAS_EXCETO_INTRA_ORCAMENTARIAS_I,
            static::LINHA_RECEITAS_CORRENTES,
            static::LINHA_RECEITAS_INTRA_ORCAMENTARIAS_II,
            static::LINHA_SUBTOTAL_RECEITAS_III,
            static::LINHA_OPERACOES_CREDITO_REFINANCIAMENTO_IV,
            static::LINHA_SUBTOTAL_REFINANCIAMENTO_V,
            static::LINHA_TOTAL_VII,
            static::LINHA_SALDOS_EXERCICIOS_ANTERIORES,
            static::LINHA_DESPESAS_INTRA_ORCAMENTARIAS_IX,
            static::LINHA_SUBTOTAL_DESPESAS_X,
            static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII,
            static::LINHA_TOTAL_XIV
        );
    }

    /**
     * Retorna um array contendo as linhas do relatório já processadas.
     * @return \stdClass[]
     */
    public function getLinhas()
    {

        if (count($this->aLinhasConsistencia) == 0) {
            $this->processar();
        }

        return $this->aLinhasConsistencia;
    }

    /**
     * Processa a busca e cálculo necessários para emissão do relatório
     */
    protected function processar()
    {

        $aInstituicao = $this->getInstituicoes(true);

        foreach ($aInstituicao as $oInstituicao) {
            if (in_array(
                $oInstituicao->getTipo(),
                array(\Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA, \Instituicao::TIPO_AUTARQUIA_RPPS)
            )) {
                $this->aInstituicoesReservaRPPS[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
            } else {
                $this->aInstituicoesReservaContigente[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
            }
        }

        $this->getDados();
        $this->calcularSuplementacao();
        $this->calcularReservaContingente();
        $this->calcularReservaRPPS();

        $this->processaTotalizadores($this->aLinhasConsistencia);
        $aLinhasProcessar = $this->aLinhasProcessar;
        foreach ($aLinhasProcessar as $linha) {
            $this->processarFormulaDaLinha($linha);
        }
        $this->calcularSuperavitDeficit();
    }

    /**
     * Processa os valores para a instituição que são do tipo Reserva de Contingente
     */
    protected function calcularReservaContingente()
    {

        $oLinhaContingencia = $this->aLinhasConsistencia[static::LINHA_RESERVA_CONTIGENCIA];
        foreach ($this->aLinhasConsistencia[static::LINHA_RESERVA_CONTIGENCIA]->colunas as $oStdColuna) {
            $this->aLinhasConsistencia[static::LINHA_RESERVA_CONTIGENCIA]->{$oStdColuna->o115_nomecoluna} = 0;
        }
        if (count($this->aInstituicoesReservaContigente) > 0) {
            $sWhereDespesa = " o58_instit in (" . implode(',', $this->aInstituicoesReservaContigente) . ")";
            $rsBalanceteDespesa = db_dotacaosaldo(
                8,
                2,
                2,
                true,
                $sWhereDespesa,
                $this->iAnoUsu,
                $this->getDataInicial()->getDate(),
                $this->getDataFinal()->getDate()
            );

            $aColunasProcessar = $this->getColunasPorLinha($oLinhaContingencia);
            \RelatoriosLegaisBase::calcularValorDaLinha(
                $rsBalanceteDespesa,
                $oLinhaContingencia,
                $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
            );
        }

        /**
         * Soma o valor encontrado para a linha na linha totalizdora do quadro
         */
        foreach ($this->aLinhasConsistencia[static::LINHA_RESERVA_CONTIGENCIA]->colunas as $oStdColuna) {
            $this->aLinhasConsistencia
            [static::LINHA_DESPESAS_EXCETO_INTRA_ORCAMENTARIAS_VIII]->{$oStdColuna->o115_nomecoluna} +=
                $this->aLinhasConsistencia[static::LINHA_RESERVA_CONTIGENCIA]->{$oStdColuna->o115_nomecoluna};
        }

        $this->processarFormulaDaLinha(static::LINHA_DESPESAS_EXCETO_INTRA_ORCAMENTARIAS_VIII);
    }


    /**
     * Processa os valores para a instituição que são do tipo RPPS
     */
    private function calcularReservaRPPS()
    {

        $iLinhaRPPS = static::LINHA_RESERVA_RPPS;

        foreach ($this->aLinhasConsistencia[$iLinhaRPPS]->colunas as $oStdColuna) {
            $this->aLinhasConsistencia[$iLinhaRPPS]->{$oStdColuna->o115_nomecoluna} = 0;
        }

        $oLinhaRPPS = $this->aLinhasConsistencia[$iLinhaRPPS];
        if (count($this->aInstituicoesReservaRPPS) > 0) {
            $sWhereDespesa = " o58_instit in (" . implode(',', $this->aInstituicoesReservaRPPS) . ")";
            $rsBalanceteDespesa = db_dotacaosaldo(
                8,
                2,
                2,
                true,
                $sWhereDespesa,
                $this->iAnoUsu,
                $this->getDataInicial()->getDate(),
                $this->getDataFinal()->getDate()
            );

            $aColunasProcessar = $this->getColunasPorLinha($oLinhaRPPS);
            \RelatoriosLegaisBase::calcularValorDaLinha(
                $rsBalanceteDespesa,
                $oLinhaRPPS,
                $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
            );
        }
        foreach ($oLinhaRPPS->colunas as $i => $value) {
            $this->processaValorManualPorLinhaEColuna($iLinhaRPPS, $i);
        }
    }


    /**
     * Ajusta os valores referente as linhas de Créditos Adicionais / Suplementação
     * @throws \Exception
     */
    protected function calcularSuplementacao()
    {

        $aWhereSuperavit = array(
            "o46_tiposup in (1008, 1003)",
            "o49_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
            "o46_instit in ({$this->getInstituicoes()})"
        );
        $oDaoOrcSuplem = new \cl_orcsuplem();
        $sSqlBuscaSuperavit = $oDaoOrcSuplem->sql_query_suplementacoes(
            null,
            "coalesce(sum(o47_valor), 0) as total",
            null,
            implode(" and ", $aWhereSuperavit)
        );
        $rsBuscaSuperavit = db_query($sSqlBuscaSuperavit);
        if (!$rsBuscaSuperavit) {
            throw new \Exception("Ocorreu um erro na busca dos valores de suplementação da coluna SUPERAVIT.");
        }


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
            $msg = "Ocorreu um erro na busca dos valores de suplementação da coluna CRÉDITOS ADICIONAIS.";
            throw new \Exception($msg);
        }

        $nValorSuperavit = \db_utils::fieldsMemory($rsBuscaSuperavit, 0)->total;
        $nValorCreditos = \db_utils::fieldsMemory($rsBuscaCreditos, 0)->total;

        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->prevatu +=
            $nValorSuperavit;
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS]->recatebim +=
            $nValorSuperavit;
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->prevatu += $nValorCreditos;
        $this->aLinhasConsistencia[static::LINHA_REABERTURA_CREDITOS_ADICIONAIS]->recatebim += $nValorCreditos;
    }

    // Funcao verifica se ouve superavit ou deficit
    protected function calcularSuperavitDeficit()
    {

        // linha 73 é do quadro de receitas
        // linha 99 é do quadro de despesas
        // linha 74 representa o deficit
        // linha 100 representa o superavit
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->dotini = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->dotatu = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->empenhado_nobim = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->empenhado_atebim = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_nobim = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim = 0;
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->desppag = '-';
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->rp_apagar = '-';
        $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->previni = '-';
        $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->prevatu = '-';
        $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recatebim = 0;
        $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recnobim = '-';
        $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recatebim = 0;
        $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim = 0;
        /**
         * Déficit
         */
        $nCalculoSuperavitDeficit =
            abs(($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->liquidado_atebim -
                $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim));

        if ($this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_V]->recatebim <
            $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->liquidado_atebim) {
            $this->aLinhasConsistencia[static::LINHA_DEFICIT_VI]->recatebim = $nCalculoSuperavitDeficit;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_VII]->recatebim += $nCalculoSuperavitDeficit;
        } else {
            $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim = $nCalculoSuperavitDeficit;
            $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->liquidado_atebim += $nCalculoSuperavitDeficit;
        }
    }


    /**
     * Retorna os dados para Demonstrativo Simplificado do Relatório Resumido da Execução Orçamentária
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
        $oDados->nSaldoExerciciosAnteriores =
            $this->aLinhasConsistencia[static::LINHA_SALDOS_EXERCICIOS_ANTERIORES]->recatebim;
        $oDados->nDotacaoInicial = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotini;
        $oDados->nDotacaoAtualizada = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->dotatu;
        $oDados->nCreditoAdicional = $oDados->nDotacaoAtualizada - $oDados->nDotacaoInicial;
        $oDados->nEmpenhadas = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->empenhado_atebim;
        $oDados->nLiquidadas = $this->aLinhasConsistencia[static::LINHA_SUBTOTAL_REFINANCIAMENTO_XII]->liquidado_atebim;
        $oDados->nPagas = $this->aLinhasConsistencia[static::LINHA_TOTAL_XIV]->desppag;
        $oDados->nSuperavitOrcamentario = $this->aLinhasConsistencia[static::LINHA_SUPERAVIT_XIII]->liquidado_atebim;

        return $oDados;
    }

    public function getCodigoRelatorio()
    {
        return static::CODIGO_RELATORIO;
    }

    public function getLinhaDeficitVI()
    {
        return static::LINHA_DEFICIT_VI;
    }

    public function getLinhaSuperavitXIII()
    {
        return static::LINHA_SUPERAVIT_XIII;
    }
}
