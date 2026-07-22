<?php


namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use AnexoRREOFactory;
use AnexoXVIIIResumido;
use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Simplificado;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

/**
 * Class RreoDemonstrativoSimplificado
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RreoDemonstrativoSimplificado extends ArquivoSigapFiscal
{
    /**
     * @var []
     */
    protected $linhasProcessadas;

    /**
     * @var string
     */
    protected $notasExplicativas;

    /**
     * @return mixed
     */
    public function getNotasExplicativas()
    {
        return $this->notasExplicativas;
    }


    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREODemonstrativoSimplificado';

    /**
     * Referência do relatório no xml de notas explicativas.
     */
    const CODIGO_NOTA_EXPLICATIVA = '13';

    /**
     * @var string[]
     */
    protected $template = [
        "reoCodigoEntidade",
        "reoBimestre",
        "reoMesAnoMovimento",
        "reoContaLRF",
        "reoDescricaoContaLRF",
        "reoRealizadaAteBim",
        "reoMetaFixada",
        "reoResultadoatebimestre",
        "reoPercRelacaMeta",
        "reoInscritosaoFinalExercAnterior",
        "reoCancelados",
        "reoPagos",
        "reoSaldo",
        "reoValorApurateBim",
        "reoPercAplicateBim",
        "reoSaldoNaoRealiz",
        "reoSaldoARealizar",
        "reoValorProjAtExercAnterior",
        "reoValorDecExerProj",
        "reoValorVigExerProj",
        "reoValorTriQuiExerProj",
        "reoPercMinimoExerc",
        "reoValorUnico",
    ];

    protected function processar()
    {
        $relatorio = new Simplificado($this->ano, Simplificado::CODIGO_RELATORIO, $this->periodo->getCodigo());
        $relatorio->setInstituicoes(implode(',', $this->codigoInstituicoes));

        $this->processarLinhas($relatorio->getBalancoOrcamentario());
        $this->processarLinhas($relatorio->getDemostrativoDespesaPorFuncaoSubfuncao());
        $this->processarLinhas($relatorio->getReceitaCorrenteLiquida());
        $this->processarLinhas($relatorio->getRegimeDePrevidencia());
        $this->processarLinhas($relatorio->getResultadoNominalPrimario());
        $this->processarLinhas($relatorio->getRestosAPagar());
        $this->processarLinhas($relatorio->getDespesasComManutencaoDesenvolvimentoEnsino());
        $this->processarLinhas($relatorio->getDespesasAcoesServicosPublicosSaude());
        if (PeriodoDePara::bimestre($this->periodo) == 6) {
            $this->processarLinhas($relatorio->getReceitasOperacoesCreditoDespesasCapital());
            $this->processarLinhas($relatorio->getProjecaoAtuarialRegimesPrevidencia());
            $this->processarLinhas($relatorio->getReceitaAlienacaoAtivosAplicacaoRecursos());
            $this->processarLinhas($relatorio->getDespesasCaraterContinuadoDerivadasPPP());
        }
    }

    /**
     * @return array|mixed
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Demonstrativo_Simplificado.php');
        return $this->linhasTemplate;
    }

    /**
     * @param $linha []
     * @return []
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];
        if ($linha_ecidade >= 2 && $linha_ecidade <= 25) {
            return $this->criaLinhaAteBimestre($linha);
        }
        if ($linha_ecidade <= 27) {
            return $this->metasFixadasLDO($linha);
        }
        if ($linha_ecidade <= 39) {
            return $this->restosAPagar($linha);
        }
        if ($linha_ecidade <= 42) {
            return $this->despesasManutencaoDesenvolvimentoEnsino($linha);
        }
        if ($linha_ecidade <= 44) {
            return $this->receitasOperacoesCredito($linha);
        }
        if ($linha_ecidade >= 46 && $linha_ecidade < 53) {
            return $this->projetoAtuarialRegimesPrevidencia($linha);
        }
        if ($linha_ecidade <= 54) {
            return $this->receitasAlienacaoAtivos($linha);
        }
        if ($linha_ecidade == 55) {
            return $this->despesasAcoesServicosPublicosSaude($linha);
        }
        if ($linha_ecidade == 56) {
            return $this->criaLinhaPPP($linha);
        }
    }

    /**
     * @param $linha []
     * @return []
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
        ];
    }

    /**
     * @return []
     * @throws \ParameterException
     */
    protected function criaEstruturaCabecalho()
    {
        $periodo = $this->periodo->getDataFinal($this->ano);
        return [
            "reoCodigoEntidade" => $this->codigoTCE,
            "reoBimestre" => PeriodoDePara::bimestre($this->periodo),
            "reoMesAnoMovimento" => $periodo->convertTo(DBDate::DATA_EN),
        ];
    }

    private function processarLinhas(array $linhas)
    {
        foreach ($linhas as $linha) {
            if (!empty($linha->ordem)) {
                $this->linhasProcessadas[$linha->ordem] = $linha;
                continue;
            }
            $ultimoItem = array_slice($this->linhasProcessadas, -1, 1, true);
            $ordem = key($ultimoItem) + 0.1;
            $this->linhasProcessadas["$ordem"] = $linha;
        }
    }

    private function criaLinhaAteBimestre(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoRealizadaAteBim" => $this->formatarValor($linhaProcessada->ate_bimestre),
        ];
    }

    private function metasFixadasLDO(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoMetaFixada" => $this->formatarValor($linhaProcessada->meta_fixada_anexo_metas_fiscais),
            "reoResultadoatebimestre" => $this->formatarValor($linhaProcessada->resultado_apurado_ate_bimestre),
            "reoPercRelacaMeta" => $this->formatarValor($linhaProcessada->relacao_meta),
        ];
    }

    private function restosAPagar(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoInscritosaoFinalExercAnterior" => $this->formatarValor($linhaProcessada->inscricao),
            "reoCancelados" => $this->formatarValor($linhaProcessada->cancelamento_ate_bimestre),
            "reoPagos" => $this->formatarValor($linhaProcessada->pagamento_ate_bimestre),
            "reoSaldo" => $this->formatarValor($linhaProcessada->saldo_pagar),
        ];
    }

    private function despesasManutencaoDesenvolvimentoEnsino(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoValorApurateBim" => $this->formatarValor($linhaProcessada->valor_apurado_ate_bimestre),
            "reoPercAplicateBim" => $this->formatarValor($linhaProcessada->aplicado_ate_bimestre),
            "reoPercMinimoExerc" => $this->formatarValor($linhaProcessada->minimo_aplicar_exercicio),
        ];
    }

    private function receitasOperacoesCredito(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoValorApurateBim" => $this->formatarValor($linhaProcessada->valor_apurado_ate_bimestre),
            "reoSaldoNaoRealiz" => $this->formatarValor($linhaProcessada->saldo_nao_realizado),
        ];
    }

    private function projetoAtuarialRegimesPrevidencia(array $linha)
    {
        $linha_ecidade = substr($linha['linha_ecidade'], 0, 2);
        $coluna = substr($linha['linha_ecidade'], 3, 1);

        $linhaProcessada = $this->linhasProcessadas[$linha_ecidade];

        $exercicio = 0;
        $exercicio10 = 0;
        $exercicio20 = 0;
        $exercicio35 = 0;

        if (!$coluna) {
            $exercicio = $linhaProcessada->valor_apurado_ate_bimestre;
        }
        if ($coluna == 1) {
            $exercicio10 = $linhaProcessada->valor_apurado_ate_bimestre;
        }
        if ($coluna == 2) {
            $exercicio20 = $linhaProcessada->valor_apurado_ate_bimestre;
        }
        if ($coluna == 3) {
            $exercicio35 = $linhaProcessada->valor_apurado_ate_bimestre;
        }

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoValorProjAtExercAnterior" => $this->formatarValor($exercicio),
            "reoValorDecExerProj" => $this->formatarValor($exercicio10),
            "reoValorVigExerProj" => $this->formatarValor($exercicio20),
            "reoValorTriQuiExerProj" => $this->formatarValor($exercicio35),
        ];
    }

    private function receitasAlienacaoAtivos(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoValorApurateBim" => $this->formatarValor($linhaProcessada->valor_apurado_ate_bimestre),
            "reoSaldoARealizar" => $this->formatarValor($linhaProcessada->saldo_realizar),
        ];
    }

    private function despesasAcoesServicosPublicosSaude(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoSaldoARealizar" => $this->formatarValor($linhaProcessada->saldo_realizar),
            "reoPercMinimoExerc" => $this->formatarValor($linhaProcessada->minimo_aplicar_exercicio),
            "reoPercAplicateBim" => $this->formatarValor($linhaProcessada->aplicado_ate_bimestre),
        ];
    }

    private function criaLinhaPPP(array $linha)
    {
        $linhaProcessada = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "reoContaLRF" => $linha['conta_lrf'],
            "reoDescricaoContaLRF" => $linha['descricao'],
            "reoValorUnico" => $this->formatarValor($linhaProcessada->valor_apurado_ate_bimestre),
        ];
    }
}
