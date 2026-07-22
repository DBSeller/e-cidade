<?php

namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoVI;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

/**
 * Class RgfDemonstrativoSimplificado
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfDemonstrativoSimplificado extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RGFDemonstrativoSimplificado';

    /**
     * @var []
     */
    protected $linhasProcessadas;

    /**
     * @var string[]
     */
    protected $template = [
        "rgfCodigoEntidade",
        "rgfQuadrimestre",
        "rgfSemestre",
        "rgfMesAnoMovimento",
        "rgfContaLRF",
        "rgfValorUnico",
        "rgfValorUnicoPerc",
        "rgfValorRAPNPExercicio",
        "rgfValorLiquido",
    ];

    /**
     * @var int
     */
    private $quadrimestre;

    protected function processar()
    {
        $instituicoes = array_map(function ($codigo) {
            return \InstituicaoRepository::getInstituicaoByCodigo($codigo);
        }, $this->codigoInstituicoes);

        $this->quadrimestre = PeriodoDePara::quadrimestre($this->periodo);
        $deParaQuadrimestre = [
            1 => 14,
            2 => 15,
            3 => 16,
        ];
        $periodo = new \Periodo($deParaQuadrimestre[$this->quadrimestre]);

        $parametrosAnexoVI = new \stdClass();
        $parametrosAnexoVI->rcl = 'true';
        $parametrosAnexoVI->pessoal = 'true';
        $parametrosAnexoVI->divida = 'true';
        $parametrosAnexoVI->garantias = 'true';
        $parametrosAnexoVI->operacoes = 'true';
        $parametrosAnexoVI->restosapagar = $periodo == 16 ? 'true' : 'false';

        $anexo = new AnexoVI();

        $anexo->definirInstituicoes($instituicoes);
        $anexo->definirAno($this->ano);
        $anexo->definirPeriodo($periodo);
        $anexo->definirParametros($parametrosAnexoVI);
        $anexo->processar();

        $this->linhasProcessadas = $anexo->getLinhasSigapFiscal();

        $relatorio = new \RelatoriosLegaisBase($this->ano, $anexo::CODIGO_RELATORIO, $this->periodo->getCodigo());
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RGF_Demonstrativo_Simplificado.php');
        return $this->linhasTemplate;
    }

    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];

        if ($linha_ecidade >= 1 && $linha_ecidade <= 3) {
            return $this->criaLinhaUnica($linha);
        } elseif ($linha_ecidade >= 4 && $linha_ecidade <= 15) {
            return $this->criaLinhaValorPercentual($linha);
        } elseif ($linha_ecidade == 16) {
            return $this->criaLinhaRAP($linha);
        }

        return [];
    }

    protected function criaLinhaTitulo($linha)
    {
        return [
            "rgfContaLRF" => $linha['conta_lrf'],
        ];
    }

    /**
     * @return array
     * @throws \ParameterException
     */
    protected function criaEstruturaCabecalho()
    {
        $periodo = $this->periodo->getDataFinal($this->ano);

        return [
            "rgfCodigoEntidade" => $this->codigoTCE,
            "rgfQuadrimestre" => PeriodoDePara::quadrimestre($this->periodo),
            "rgfSemestre" => 0,
            "rgfMesAnoMovimento" => $periodo->convertTo(DBDate::DATA_EN),
        ];
    }

    /**
     * @param $linha[]
     * @return []
     */
    private function criaLinhaRAP(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rgfContaLRF" => $linha['conta_lrf'],
            "rgfValorRAPNPExercicio" => $this->formatarValor($linhaRelatorio['rp_nao_processado']),
            "rgfValorLiquido" => $this->formatarValor($linhaRelatorio['disponibilidade_caixa_liquida']),
        ];
    }

    /**
     * @param $linha[]
     * @return []
     */
    private function criaLinhaUnica(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rgfContaLRF" => $linha['conta_lrf'],
            "rgfValorUnico" => $this->formatarValor($linhaRelatorio['valor']),
        ];
    }

    /**
     * @param $linha[]
     * @return []
     */
    private function criaLinhaValorPercentual(array $linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rgfContaLRF" => $linha['conta_lrf'],
            "rgfValorUnico" => $this->formatarValor($linhaRelatorio['valor']),
            "rgfValorUnicoPerc" => $this->formatarValor($linhaRelatorio['percentual']),
        ];
    }
}
