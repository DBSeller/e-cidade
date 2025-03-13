<?php

namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoIV;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use Periodo;
use RelatoriosLegaisBase;

/**
 * Class RgfOperacoesDeCredito
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfOperacoesDeCredito extends ArquivoSigapFiscal
{
    /**
     * @var []
     */
    protected $linhasProcessadas;

    /**
     * @var int
     */
    private $quadrimestre;

    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RGFOperacoesDeCredito';

    /**
     * Referência do relatório no xml de notas explicativas.
     */
    const CODIGO_NOTA_EXPLICATIVA = '18';

    /**
     * @var string[]
     */
    protected $template = [
        "opcCodigoEntidade",
        "opcQuadrimestre",
        "opcSemestre",
        "opcMesAnoMovimento",
        "opcContaLRF",
        "opcDescricaoContaLRF",
        "opcValorRealQuadrimestre",
        "opcValorRealateQuadrimestre",
        "opcValorRealSemestre",
        "opcValorRealateSemestre",
        "opcValorUnico",
        "opcValorUnicoPerc",
    ];

    protected function processar()
    {
        $this->quadrimestre = PeriodoDePara::quadrimestre($this->periodo);
        $dePara = [
            7 => 14,
            9 => 15,
            11 => 16
        ];
        $periodo = new Periodo($dePara[$this->periodo->getCodigo()]);
        $layout = new AnexoIV($this->ano, $periodo);
        $layout->processar();
        $this->linhasProcessadas = $layout->getLinhas();
    }

    /**
     * @return array|mixed
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RGF_Demonstrativo_Operacoes_Credito.php');
        return $this->linhasTemplate;
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];
        if ($linha_ecidade >= 1 && $linha_ecidade <= 17) {
            return $this->criaLinhaValorRealizado($linha);
        } elseif (in_array($linha_ecidade, [18, "18.1", "18.2"])) {
            return $this->criaLinhaValorUnico($linha);
        } elseif ($linha_ecidade >= 19 && $linha_ecidade <= 24) {
            return $this->criaLinhaValorUnico($linha);
        } elseif ($linha_ecidade >= 25 && $linha_ecidade <= 29) {
            return $this->criaLinhaValorRealizado($linha);
        }
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            "opcContaLRF" => $linha['conta_lrf'],
            "opcDescricaoContaLRF" => $linha['descricao'],
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
            "opcCodigoEntidade" => $this->codigoTCE,
            "opcQuadrimestre" => $this->quadrimestre,
            "opcSemestre" => 0,
            "opcMesAnoMovimento" => $periodo->convertTo(DBDate::DATA_EN),
        ];
    }

    /**
     * @param $linha
     * @return array
     */
    private function criaLinhaValorRealizado($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "opcContaLRF" => $linha['conta_lrf'],
            "opcDescricaoContaLRF" => $linha['descricao'],
            "opcValorRealQuadrimestre" => $this->formatarValor($linhaRelatorio->noperiodo),
            "opcValorRealateQuadrimestre" => $this->formatarValor($linhaRelatorio->ateperiodo),
        ];
    }

    /**
     * @param $linha
     * @return array
     */
    private function criaLinhaValorUnico($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "opcContaLRF" => $linha['conta_lrf'],
            "opcDescricaoContaLRF" => $linha['descricao'],
            "opcValorUnico" => $this->formatarValor($linhaRelatorio->valor),
            "opcValorUnicoPerc" => $this->formatarValor($linhaRelatorio->percentual),
        ];
    }
}
