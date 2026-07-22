<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use AnexoRREOFactory;
use AnexoXVIIIResumido;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoI as FactoryAnexoI;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as AnexoIIIFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIV as FactoryAnexoIV;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoVI as FactoryRelatorio;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoVIII as FactoryAnexoVIII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoXII as FactoryAnexoXII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoXIII as FactoryAnexoXIII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\LinhaAnexoVII;
use Exception;
use InstituicaoRepository;
use ParameterException;
use Periodo;
use stdClass;

/**
 * Class Simplificado
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 */
class Simplificado extends AnexoXVIIIResumido
{
    /**
     * @var int
     */
    const CODIGO_RELATORIO = 181;

    /**
     * @var
     */
    private $dadosAnexoI;

    /**
     * Simplificado constructor.
     * @param $iAnoUsu
     * @param $iCodigoRelatorio
     * @param $iCodigoPeriodo
     */
    public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, self::CODIGO_RELATORIO, $iCodigoPeriodo);

        $this->emitir();
    }

    /**
     * Emite os dados do balancete Orçamentário
     */
    public function getBalancoOrcamentario()
    {
        $dadosBalanco = $this->calcularAnexoI();
        /**
         * receita
         */
        $this->aLinhasConsistencia[2]->ate_bimestre = $dadosBalanco->nPrevisaoInicial;
        $this->aLinhasConsistencia[3]->ate_bimestre = $dadosBalanco->nPrevisaoAtualizada;
        $this->aLinhasConsistencia[4]->ate_bimestre = $dadosBalanco->nReceitasRealizadas;
        $this->aLinhasConsistencia[5]->ate_bimestre = $dadosBalanco->nDeficitOrcamentario;
        $this->aLinhasConsistencia[6]->ate_bimestre = $dadosBalanco->nSaldoExerciciosAnteriores;
        /**
         * Despesa
         */
        $this->aLinhasConsistencia[8]->ate_bimestre = $dadosBalanco->nDotacaoInicial;
        $this->aLinhasConsistencia[9]->ate_bimestre = $dadosBalanco->nCreditoAdicional;
        $this->aLinhasConsistencia[10]->ate_bimestre = $dadosBalanco->nDotacaoAtualizada;
        $this->aLinhasConsistencia[11]->ate_bimestre = $dadosBalanco->nEmpenhadas;
        $this->aLinhasConsistencia[12]->ate_bimestre = $dadosBalanco->nLiquidadas;
        $this->aLinhasConsistencia[13]->ate_bimestre = $dadosBalanco->nPagas;
        $this->aLinhasConsistencia[14]->ate_bimestre = $dadosBalanco->nSuperavitOrcamentario;
        $linhasBalanco = array_slice($this->aLinhasConsistencia, 0, 14, true);
        return $linhasBalanco;
    }

    /**
     * Emite o demonstrativo de Funcao/Sub-função
     * @return array
     */
    public function getDemostrativoDespesaPorFuncaoSubfuncao()
    {
        $dadosBalanco = $this->calcularAnexoI();
        $this->aLinhasConsistencia[15]->ate_bimestre = $dadosBalanco->nEmpenhadasSemSuperavit;
        $this->aLinhasConsistencia[16]->ate_bimestre = $dadosBalanco->nLiquidadas;

        return array_slice($this->aLinhasConsistencia, 14, 2);
    }

    /**
     * Emite os valores da Receita Corrente Líquida
     * @return array
     */
    public function getReceitaCorrenteLiquida()
    {
        /**
         * Versao Antiga da RCL
         */
        if ($this->iAnoUsu < 2020) {
            $receitaCorrenteLiquidaCorrente = new ReceitaCorrenteLiquida($this->iAnoUsu, null, 178);
            $valorRcl = $receitaCorrenteLiquidaCorrente->somaRCLPeriodo($this->iCodigoPeriodo);
            $this->aLinhasConsistencia[17]->ate_bimestre = $valorRcl;

            return array($this->aLinhasConsistencia[17]);
        }
        $instituicoes = InstituicaoRepository::getInstituicoes();
        $codigoInstituicoes = implode(',', array_keys($instituicoes));

        $dadosAnexoXII = AnexoIIIFactory::getInstance($this->iAnoUsu, $this->iCodigoPeriodo);
        $dadosAnexoXII->setInstituicoes($codigoInstituicoes);

        $dadosSimplificado = $dadosAnexoXII->getDadosSimplificado();
        $this->aLinhasConsistencia[17]->ate_bimestre = $dadosSimplificado->valor_rcl_mdf;
        $dadosEndividamento = new \stdClass();
        $dadosEndividamento->descricao = "Receita Corrente Líquida Ajustada para Cálculo dos Limites de Endividamento";
        $dadosEndividamento->ate_bimestre = $dadosSimplificado->valor_rcl_endividamento;
        $dadosEndividamento->totalizar = false;
        $dadosEndividamento->nivel = 1;

        $dadosPessoal = new \stdClass();
        $dadosPessoal->descricao = "Receita Corrente Líquida Ajustada para Cálculo dos Limites da Despesa ";
        $dadosPessoal->descricao .= "com Pessoal";
        $dadosPessoal->totalizar = false;
        $dadosPessoal->nivel = 1;
        $dadosPessoal->ate_bimestre = $dadosSimplificado->valor_rcl_pessoal;

        return  [$this->aLinhasConsistencia[17], $dadosEndividamento, $dadosPessoal];
    }

    /**
     * Emite os dados do relatorio
     * @return array
     */
    public function emitir()
    {
        $this->aLinhasConsistencia = $this->getLinhasRelatorio();

        return $this->aLinhasConsistencia;
    }

    /**
     * Calcula os valores do Anexo I
     * @return stdClass
     */
    protected function calcularAnexoI()
    {
        if (empty($this->dadosAnexoI)) {
            $oBalancoOrcamentario = FactoryAnexoI::getInstance($this->iAnoUsu, $this->oPeriodo);
            $oBalancoOrcamentario->setDataInicial($oBalancoOrcamentario->getDataInicialPeriodo());
            $oBalancoOrcamentario->setInstituicoes($this->getInstituicoes());
            $this->dadosAnexoI = $oBalancoOrcamentario->getDadosSimplificado();
        }

        return $this->dadosAnexoI;
    }

    /**
     * Retorna os dados do demonstrativo RPPS
     * @return array
     * @throws ParameterException
     */
    public function getRegimeDePrevidencia()
    {
        $oAnexo = FactoryAnexoIV::getInstance($this->iAnoUsu, $this->oPeriodo->getCodigo());
        $dadosAnexo = $oAnexo->getDadosSimplificado();
        $totalBimestreLinha21 = $this->aLinhasConsistencia[19]->ate_bimestre -
            $this->aLinhasConsistencia[20]->ate_bimestre;
        $this->aLinhasConsistencia[19]->ate_bimestre = $dadosAnexo->receitasRealizadasPlanoPrevidenciario;
        $this->aLinhasConsistencia[20]->ate_bimestre = $dadosAnexo->despesasLiquidadasPlanoPrevidenciario;
        $this->aLinhasConsistencia[21]->ate_bimestre = $totalBimestreLinha21;

        $this->aLinhasConsistencia[23]->ate_bimestre = $dadosAnexo->receitasRealizadasPlanoFinanceiro;
        $this->aLinhasConsistencia[24]->ate_bimestre = $dadosAnexo->despesasLiquidadasPlanoFinanceiro;
        $this->aLinhasConsistencia[25]->ate_bimestre = $this->aLinhasConsistencia[23]->ate_bimestre -
            $this->aLinhasConsistencia[24]->ate_bimestre;

        return array_slice($this->aLinhasConsistencia, 17, 8, true);
    }

    /**
     * @return array
     * @throws Exception
     * @throws ParameterException
     */
    public function getResultadoNominalPrimario()
    {
        $dadosAnexoVI = FactoryRelatorio::getInstance($this->getAno(), $this->getPeriodo()->getCodigo());
        $dadosAnexoVI->setInstituicoes($this->getInstituicoes());
        $dadosSimplificado = $dadosAnexoVI->getDadosSimplificado();

        $this->aLinhasConsistencia[26]->descricao .= " - Acima da Linha";
        $this->aLinhasConsistencia[26]->meta_fixada_anexo_metas_fiscais = $dadosSimplificado->metaResultadoNominal;
        $this->aLinhasConsistencia[26]->resultado_apurado_ate_bimestre = $dadosSimplificado->resultadoNominal;
        $this->aLinhasConsistencia[26]->relacao_meta = $dadosSimplificado->percentualMetaNominal;

        $this->aLinhasConsistencia[27]->descricao .= " - Acima da Linha";
        $this->aLinhasConsistencia[27]->meta_fixada_anexo_metas_fiscais = $dadosSimplificado->metaResultadoPrimario;
        $this->aLinhasConsistencia[27]->resultado_apurado_ate_bimestre = $dadosSimplificado->resultadoPrimario;
        $this->aLinhasConsistencia[27]->relacao_meta = $dadosSimplificado->percentualMetaPrimario;

        return array(
            $this->aLinhasConsistencia[27],
            $this->aLinhasConsistencia[26],
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getRestosAPagar()
    {
        $oRelatorio = new AnexoVII();
        $oRelatorio->setAno($this->getAno());
        $oRelatorio->setPeriodo($this->getPeriodo());
        $aInstitucoes = $this->getInstituicoes(true);
        foreach ($aInstitucoes as $instituicao) {
            $oRelatorio->adicionarInstituicao($instituicao);
        }

        $dadosSimplificado = $oRelatorio->getDadosSimplificado();

        /**
         * Poder Executivo
         */
        if (!empty($dadosSimplificado["linhas"][LinhaAnexoVII::PODER_EXECUTIVO])) {
            $linhaPoderExecutivo = $dadosSimplificado["linhas"][LinhaAnexoVII::PODER_EXECUTIVO];
            $this->aLinhasConsistencia[29]->inscricao = $linhaPoderExecutivo->nProcessadoInscrito;
            $this->aLinhasConsistencia[35]->inscricao = $linhaPoderExecutivo->nNaoProcessadoInscrito;
            $this->aLinhasConsistencia[29]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoCancelado;
            $this->aLinhasConsistencia[35]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoCancelado;
            $this->aLinhasConsistencia[29]->pagamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoPago;
            $this->aLinhasConsistencia[35]->pagamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoPago;
            $this->aLinhasConsistencia[29]->saldo_pagar = $linhaPoderExecutivo->nProcessadoPagar;
            $this->aLinhasConsistencia[35]->saldo_pagar = $linhaPoderExecutivo->nNaoProcessadoPagar;
        }
        /**
         * Poder Legislativo
         */
        if (!empty($dadosSimplificado["linhas"][LinhaAnexoVII::PODER_LEGISLATIVO])) {
            $linhaPoderExecutivo = $dadosSimplificado["linhas"][LinhaAnexoVII::PODER_LEGISLATIVO];
            $this->aLinhasConsistencia[30]->inscricao = $linhaPoderExecutivo->nProcessadoInscrito;
            $this->aLinhasConsistencia[36]->inscricao = $linhaPoderExecutivo->nNaoProcessadoInscrito;
            $this->aLinhasConsistencia[30]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoCancelado;
            $this->aLinhasConsistencia[36]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoCancelado;
            $this->aLinhasConsistencia[30]->pagamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoPago;
            $this->aLinhasConsistencia[36]->pagamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoPago;
            $this->aLinhasConsistencia[30]->saldo_pagar = $linhaPoderExecutivo->nProcessadoPagar;
            $this->aLinhasConsistencia[36]->saldo_pagar = $linhaPoderExecutivo->nNaoProcessadoPagar;
        }
        /**
         * Poder Judiciario
         */
        if (!empty($dadosSimplificado["linhas"][LinhaAnexoVII::PODER_JUDICIARIO])) {
            $linhaPoderExecutivo = $dadosSimplificado["linhas"][LinhaAnexoVII::PODER_JUDICIARIO];
            $this->aLinhasConsistencia[31]->inscricao = $linhaPoderExecutivo->nProcessadoInscrito;
            $this->aLinhasConsistencia[37]->inscricao = $linhaPoderExecutivo->nNaoProcessadoInscrito;
            $this->aLinhasConsistencia[31]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoCancelado;
            $this->aLinhasConsistencia[37]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoCancelado;
            $this->aLinhasConsistencia[31]->pagamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoPago;
            $this->aLinhasConsistencia[37]->pagamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoPago;
            $this->aLinhasConsistencia[31]->saldo_pagar = $linhaPoderExecutivo->nProcessadoPagar;
            $this->aLinhasConsistencia[37]->saldo_pagar = $linhaPoderExecutivo->nNaoProcessadoPagar;
        }
        /**
         * Ministério publico
         */
        if (!empty($dadosSimplificado["linhas"][LinhaAnexoVII::MINISTERIO_PUBLICO])) {
            $linhaPoderExecutivo = $dadosSimplificado["linhas"][LinhaAnexoVII::MINISTERIO_PUBLICO];
            $this->aLinhasConsistencia[32]->inscricao = $linhaPoderExecutivo->nProcessadoInscrito;
            $this->aLinhasConsistencia[38]->inscricao = $linhaPoderExecutivo->nNaoProcessadoInscrito;
            $this->aLinhasConsistencia[32]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoCancelado;
            $this->aLinhasConsistencia[38]->cancelamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoCancelado;
            $this->aLinhasConsistencia[32]->pagamento_ate_bimestre = $linhaPoderExecutivo->nProcessadoPago;
            $this->aLinhasConsistencia[38]->pagamento_ate_bimestre = $linhaPoderExecutivo->nNaoProcessadoPago;
            $this->aLinhasConsistencia[32]->saldo_pagar = $linhaPoderExecutivo->nProcessadoPagar;
            $this->aLinhasConsistencia[38]->saldo_pagar = $linhaPoderExecutivo->nNaoProcessadoPagar;
        }

        /**
         * Somamos o total dos restos a pagar e total geral
         */
        foreach (range(29, 33) as $i) {
            $cancelamentoAteBim = $this->aLinhasConsistencia[$i]->cancelamento_ate_bimestre;
            $pagoAteBim = $this->aLinhasConsistencia[$i]->pagamento_ate_bimestre;
            $this->aLinhasConsistencia[28]->inscricao += $this->aLinhasConsistencia[$i]->inscricao;
            $this->aLinhasConsistencia[28]->cancelamento_ate_bimestre += $cancelamentoAteBim;
            $this->aLinhasConsistencia[28]->pagamento_ate_bimestre += $pagoAteBim;
            $this->aLinhasConsistencia[28]->saldo_pagar += $this->aLinhasConsistencia[$i]->saldo_pagar;

            $cancelamentoAteBim = $this->aLinhasConsistencia[$i]->cancelamento_ate_bimestre;
            $pagoAteBim = $this->aLinhasConsistencia[$i]->pagamento_ate_bimestre;
            $this->aLinhasConsistencia[40]->inscricao += $this->aLinhasConsistencia[$i]->inscricao;
            $this->aLinhasConsistencia[40]->cancelamento_ate_bimestre += $cancelamentoAteBim;
            $this->aLinhasConsistencia[40]->pagamento_ate_bimestre += $pagoAteBim;
            $this->aLinhasConsistencia[40]->saldo_pagar += $this->aLinhasConsistencia[$i]->saldo_pagar;
        }
        /**
         * Somamos o total dos restos a pagar não processados e total geral
         */
        foreach (range(35, 39) as $i) {
            $cancelamentoAteBim = $this->aLinhasConsistencia[$i]->cancelamento_ate_bimestre;
            $pagoAteBim = $this->aLinhasConsistencia[$i]->pagamento_ate_bimestre;
            $this->aLinhasConsistencia[34]->inscricao += $this->aLinhasConsistencia[$i]->inscricao;
            $this->aLinhasConsistencia[34]->cancelamento_ate_bimestre += $cancelamentoAteBim;
            $this->aLinhasConsistencia[34]->pagamento_ate_bimestre += $pagoAteBim;
            $this->aLinhasConsistencia[34]->saldo_pagar += $this->aLinhasConsistencia[$i]->saldo_pagar;

            $this->aLinhasConsistencia[40]->inscricao += $this->aLinhasConsistencia[$i]->inscricao;
            $this->aLinhasConsistencia[40]->cancelamento_ate_bimestre += $cancelamentoAteBim;
            $this->aLinhasConsistencia[40]->pagamento_ate_bimestre += $pagoAteBim;
            $this->aLinhasConsistencia[40]->saldo_pagar += $this->aLinhasConsistencia[$i]->saldo_pagar;
        }

        return array(
            $this->aLinhasConsistencia[28],
            $this->aLinhasConsistencia[29],
            $this->aLinhasConsistencia[30],
            $this->aLinhasConsistencia[31],
            $this->aLinhasConsistencia[32],
            $this->aLinhasConsistencia[33],
            $this->aLinhasConsistencia[34],
            $this->aLinhasConsistencia[35],
            $this->aLinhasConsistencia[36],
            $this->aLinhasConsistencia[37],
            $this->aLinhasConsistencia[38],
            $this->aLinhasConsistencia[39],
            $this->aLinhasConsistencia[40]
        );
    }

    /**
     * @return array
     */
    public function getDespesasComManutencaoDesenvolvimentoEnsino()
    {

        $oAnexoMDE    = FactoryAnexoVIII::getInstance($this->iAnoUsu, $this->oPeriodo->getCodigo());
        $oInstituicao = \InstituicaoRepository::getInstituicaoPrefeitura();
        $oAnexoMDE->setInstituicoes($oInstituicao->getCodigo());
        $dadosSimplificado = $oAnexoMDE->getDadosSimplificado();
        $this->aLinhasConsistencia[41]->valor_apurado_ate_bimestre = $dadosSimplificado->nMinimoAtualMDEAteBimestre;
        $this->aLinhasConsistencia[41]->minimo_aplicar_exercicio   = 25;
        $this->aLinhasConsistencia[41]->aplicado_ate_bimestre      = $dadosSimplificado->nPercentualAplicadoComMDE;

        $this->aLinhasConsistencia[42]->valor_apurado_ate_bimestre = $dadosSimplificado->nMinimoAtualFUNDEBAteBimestre;
        $this->aLinhasConsistencia[42]->minimo_aplicar_exercicio   = 60;
        $this->aLinhasConsistencia[42]->aplicado_ate_bimestre      = $dadosSimplificado->nPercentualAplicadoComFUNDEB;

        return array(
            $this->aLinhasConsistencia[41],
            $this->aLinhasConsistencia[42],
        );
    }

    /**
     * @return array
     */
    public function getReceitasOperacoesCreditoDespesasCapital()
    {
        $oRelatorio = AnexoRREOFactory::getAnexoRREO(
            AnexoRREOFactory::ANEXO_IX,
            $this->iAnoUsu,
            new Periodo($this->iCodigoPeriodo)
        );
        $sInstituicoes = $this->getInstituicoes();
        $oRelatorio->setInstituicoes($sInstituicoes);
        $aDados = $oRelatorio->getDadosSimplificado();

        $this->aLinhasConsistencia[43]->valor_apurado_ate_bimestre = $aDados[0]->nReceitasRealizadas;
        $this->aLinhasConsistencia[43]->saldo_nao_realizado = $aDados[0]->nSaldoNaoRealizado;
        $this->aLinhasConsistencia[44]->valor_apurado_ate_bimestre = $aDados[1]->nDespesasEmpenhadas;
        $this->aLinhasConsistencia[44]->saldo_nao_realizado = $aDados[1]->nSaldoNaoExecutado;

        return array(
            $this->aLinhasConsistencia[43],
            $this->aLinhasConsistencia[44],
        );
    }

    /**
     * @return array
     */
    public function getProjecaoAtuarialRegimesPrevidencia()
    {
        return array(
            $this->aLinhasConsistencia[45],
            $this->aLinhasConsistencia[46],
            $this->aLinhasConsistencia[47],
            $this->aLinhasConsistencia[48],
            $this->aLinhasConsistencia[49],
            $this->aLinhasConsistencia[50],
            $this->aLinhasConsistencia[51],
            $this->aLinhasConsistencia[52],
        );
    }

    /**
     * @return array
     */
    public function getReceitaAlienacaoAtivosAplicacaoRecursos()
    {
        $oRelatorio = AnexoRREOFactory::getAnexoRREO(
            AnexoRREOFactory::ANEXO_XI,
            $this->iAnoUsu,
            new Periodo($this->iCodigoPeriodo)
        );

        $sInstituicoes = $this->getInstituicoes();
        $oRelatorio->setInstituicoes($sInstituicoes);
        $aDados = $oRelatorio->getDadosSimplificado();

        $this->aLinhasConsistencia[53]->valor_apurado_ate_bimestre = $aDados[0]->nAteBimestre;
        $this->aLinhasConsistencia[53]->saldo_realizar = $aDados[0]->nSaldoRealizar;
        $this->aLinhasConsistencia[54]->valor_apurado_ate_bimestre = $aDados[1]->nAteBimestre;
        $this->aLinhasConsistencia[54]->saldo_realizar = $aDados[1]->nSaldoRealizar;

        return array(
            $this->aLinhasConsistencia[53],
            $this->aLinhasConsistencia[54],
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDespesasAcoesServicosPublicosSaude()
    {
        $oAnexoXII = FactoryAnexoXII::getInstance($this->iAnoUsu, $this->iCodigoPeriodo);
        $oAnexoXII->setInstituicoes($this->getInstituicoes());
        $dadosSimplificado = $oAnexoXII->getDadosSimplificado();
        $this->aLinhasConsistencia[55]->valor_apurado_ate_bimestre = $dadosSimplificado->nTotalDespesasSaudeComImpostos;
        $this->aLinhasConsistencia[55]->minimo_aplicar_exercicio = $dadosSimplificado->nPercentualMinimoAplicar;
        $this->aLinhasConsistencia[55]->aplicado_ate_bimestre = $dadosSimplificado->nPercentualDespesasSaudeComImpostos;

        return array($this->aLinhasConsistencia[55]);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDespesasCaraterContinuadoDerivadasPPP()
    {
        $relatorio = FactoryAnexoXIII::getInstance($this->getAno(), $this->getPeriodo()->getCodigo());
        $relatorio->setInstituicoes($this->getInstituicoes());

        $totalDespesasRCL = $relatorio->getDadosSimplificado();

        $this->aLinhasConsistencia[56]->valor_apurado_ate_bimestre = $totalDespesasRCL->exercicio_corrente;

        return array($this->aLinhasConsistencia[56]);
    }
}
