<?php


namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoIV;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

/**
 * Class RreoDespesaReceitaRPPS
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RreoDespesaReceitaRPPS extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREODespesaReceitaRPPS';

    /**
     * @var []
     */
    protected $linhasProcessadas;

    /**
     * @var string[]
     */
    protected $template = [
        "rppsCodigoEntidade",
        "rppsBimestre",
        "rppsMesAnoMovimento",
        "rppsContaLRF",
        "rppsDescricaoContaLRF",
        "rppsPrevisaoInicial",
        "rppsPrevisaoAtualizada",
        "rppsReceitaRealateBim",
        "rppsReceitaRealateBimExecAnterior",
        "rppsDotacaoInicial",
        "rppsDotacaoAtualizada",
        "rppsDespEmpateBim",
        "rppsDespEmpateBimExercAnterior",
        "rppsDespLiqateBim",
        "rppsDespLiqateBimExercAnterior",
        "rppsRAPNaoProcessados",
        "rppsRAPNaoProcessadosExercAnterior",
        "rppsValorUnico",
        "rppsValorExercicio",
        "rppsValorExercicioAnterior",
    ];

    protected function processar()
    {
        $anexo = new AnexoIV($this->ano, $this->periodo->getCodigo());
        $this->linhasProcessadas = $anexo->getDados();
    }

    /**
     * @return []
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Receitas_Despesas_Previdenciarias_RPPS_AnexoIV.php');
        return $this->linhasTemplate;
    }

    /**
     * @param $linha []
     * @return []
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];

        if ($linha_ecidade >= 1 && $linha_ecidade <= 33 ||
            $linha_ecidade >= 60 && $linha_ecidade <= 91 ||
            $linha_ecidade >= 111 && $linha_ecidade <= 112) {
            return $this->criaLinhaReceitas($linha);
        } elseif ($linha_ecidade >= 38 && $linha_ecidade <= 50 || in_array($linha_ecidade, [94, 96]) ||
            $linha_ecidade >= 98 && $linha_ecidade <= 108 ||
            $linha_ecidade >= 113 && $linha_ecidade <= 116) {
            return $this->criaLinhaDespesas($linha);
        } elseif ($linha_ecidade >= 51 && $linha_ecidade <= 56 || $linha_ecidade >= 109 && $linha_ecidade <= 110) {
            return $this->criaLinhaValorUnico($linha);
        } elseif ($linha_ecidade >= 57 && $linha_ecidade <= 59) {
            return $this->criaLinhaExercicios($linha);
        }

        return [];
    }

    /**
     * @param $linha []
     * @return []
     */
    protected function criaLinhaTitulo($linha)
    {
        return [

            "rppsContaLRF" => $linha['conta_lrf'],
            "rppsDescricaoContaLRF" => $linha['descricao'],
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
            "rppsCodigoEntidade" => $this->codigoTCE,
            "rppsBimestre" => PeriodoDePara::bimestre($this->periodo),
            "rppsMesAnoMovimento" => $periodo->convertTo(DBDate::DATA_EN),
        ];
    }

    /**
     * @param $linha []
     * @return []
     */
    private function criaLinhaReceitas($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rppsContaLRF" => $linha['conta_lrf'],
            "rppsDescricaoContaLRF" => $linha['descricao'],
            "rppsPrevisaoInicial" => $this->formatarValor($linhaRelatorio->prev_ini),
            "rppsPrevisaoAtualizada" => $this->formatarValor($linhaRelatorio->prev_atual),
            "rppsReceitaRealateBim" => $this->formatarValor($linhaRelatorio->rec_atebim),
            "rppsReceitaRealateBimExecAnterior" => $this->formatarValor($linhaRelatorio->recbiexant),
        ];
    }

    /**
     * @param $linha []
     * @return []
     */
    private function criaLinhaDespesas(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rppsContaLRF" => $linha['conta_lrf'],
            "rppsDescricaoContaLRF" => $linha['descricao'],
            "rppsDotacaoInicial" => $this->formatarValor($linhaRelatorio->dot_ini),
            "rppsDotacaoAtualizada" => $this->formatarValor($linhaRelatorio->dot_atual),
            "rppsDespEmpateBim" => $this->formatarValor($linhaRelatorio->emp_atebim),
            "rppsDespEmpateBimExercAnterior" => $this->formatarValor($linhaRelatorio->emp_atebimexant),
            "rppsDespLiqateBim" => $this->formatarValor($linhaRelatorio->liq_atebim),
            "rppsDespLiqateBimExercAnterior" => $this->formatarValor($linhaRelatorio->liq_atebimexant),
            "rppsRAPNaoProcessados" => $this->formatarValor($linhaRelatorio->rp_nproc),
            "rppsRAPNaoProcessadosExercAnterior" => $this->formatarValor($linhaRelatorio->rp_nprocexant),
        ];
    }

    /**
     * @param $linha []
     */
    private function criaLinhaValorUnico(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rppsContaLRF" => $linha['conta_lrf'],
            "rppsDescricaoContaLRF" => $linha['descricao'],
            "rppsValorUnico" => $this->formatarValor($linhaRelatorio->valor),
        ];
    }

    /**
     * @param $linha []
     */
    private function criaLinhaExercicios(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rppsContaLRF" => $linha['conta_lrf'],
            "rppsDescricaoContaLRF" => $linha['descricao'],
            "rppsValorExercicio" => $this->formatarValor($linhaRelatorio->vlrexatual),
            "rppsValorExercicioAnterior" => $this->formatarValor($linhaRelatorio->vlrexanter),
        ];
    }
}
