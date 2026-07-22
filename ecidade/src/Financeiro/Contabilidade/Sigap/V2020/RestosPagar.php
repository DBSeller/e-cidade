<?php


namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\LinhaAnexoVII;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use InstituicaoRepository;

/**
 * Class RestosPagar
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RestosPagar extends ArquivoSigapFiscal
{
    const SINTETICA = "S";
    const ANALITICA = "A";
    const CALCULADA = "C";

    /**
     * @var LinhaAnexoVII[]
     */
    protected $linhasProcessadas;

    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREORestosAPagar';

    /**
     * @var string[]
     */
    protected $template = [
        "rapCodigoEntidade",
        "rapBimestre",
        "rapMesAnoMovimento",
        "rapContaLRF",
        "rapDescricaoContaLRF",
        "rapTipoContaLRF",
        "rappInscExercAnteriores",
        "rappInscFinalExercAnterior",
        "rappPagos",
        "rappCancelados",
        "rappSaldo",
        "ranpInscExercAnteriores",
        "ranpInscFinalExercAnterior",
        "ranpLiquidados",
        "ranpPagos",
        "ranpCancelados",
        "ranpSaldo",
        "ranpSaldoTotal",
    ];

    protected function processar()
    {
        $layout = new AnexoVII();
        $layout->setPeriodo($this->periodo);
        $layout->setAno($this->ano);
        $layout->adicionarInstituicao(InstituicaoRepository::getInstituicaoByCodigo($this->codigoInstituicoes[0]));
        $layout->processarFiscal();

        $this->linhasProcessadas = $layout->getDados();
    }

    /**
     * @return array|mixed
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Demonstrativo_Restos_Pagar_Poder_Orgao_AnexoVII.php');
        return $this->linhasTemplate;
    }

    /**
     * @param $linha[]
     * @return []
     */
    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];
        switch ($linha_ecidade) {
            case 1:
                return $this->criaLinha($linha, self::SINTETICA);
            case 2:
                return $this->criaLinha($linha, self::ANALITICA);
            case 3:
                return $this->criaLinha($linha, self::CALCULADA);
            case "1.1":
                return $this->criaLinhaPoder($linha, self::ANALITICA, LinhaAnexoVII::PODER_EXECUTIVO);
            case "1.2":
                return $this->criaLinhaPoder($linha, self::ANALITICA, LinhaAnexoVII::PODER_LEGISLATIVO);
            case "1.3":
                return $this->criaLinhaPoder($linha, self::ANALITICA, LinhaAnexoVII::PODER_JUDICIARIO);
            case "1.4":
                return $this->criaLinhaPoder($linha, self::ANALITICA, LinhaAnexoVII::MINISTERIO_PUBLICO);
            case "1.5":
                return $this->criaLinhaPoder($linha, self::ANALITICA, null);
            case "1.6":
                return $this->criaLinhaPoder($linha, self::ANALITICA, null);
        }
    }

    /**
     * @param $linha[]
     * @return []
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            "rapContaLRF" => $linha['conta_lrf'],
            "rapDescricaoContaLRF" => $linha['descricao'],
            "rapTipoContaLRF" => "N",
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
            "rapCodigoEntidade" => $this->codigoTCE,
            "rapBimestre" => PeriodoDePara::bimestre($this->periodo),
            "rapMesAnoMovimento" => $periodo->convertTo(DBDate::DATA_EN),
        ];
    }

    /**
     * @param $linha[]
     * @param $tipoLinha
     * @return []
     */
    private function criaLinha(array $linha, $tipoLinha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "rapContaLRF" => $linha['conta_lrf'],
            "rapDescricaoContaLRF" => $linha['descricao'],
            "rapTipoContaLRF" => $tipoLinha,
            "rappInscExercAnteriores" => $this->formatarValor(
                $linhaRelatorio->getValorProcessadoEmExerciciosAnteriores()
            ),
            "rappInscFinalExercAnterior" => $this->formatarValor(
                $linhaRelatorio->getValorProcessadoNoExercicioAnterior()
            ),
            "rappPagos" => $this->formatarValor($linhaRelatorio->getValorPagoProcessado()),
            "rappCancelados" => $this->formatarValor($linhaRelatorio->getValorCanceladoProcessado()),
            "rappSaldo" => $this->formatarValor($linhaRelatorio->getSaldoProcessado()),
            "ranpInscExercAnteriores" => $this->formatarValor(
                $linhaRelatorio->getValorNaoProcessadoEmExerciciosAnteriores()
            ),
            "ranpInscFinalExercAnterior" => $this->formatarValor(
                $linhaRelatorio->getValorNaoProcessadoNoExercicioAnterior()
            ),
            "ranpLiquidados" => $this->formatarValor($linhaRelatorio->getValorLiquidadoNaoProcessado()),
            "ranpPagos" => $this->formatarValor($linhaRelatorio->getValorPagoNaoProcessado()),
            "ranpCancelados" => $this->formatarValor($linhaRelatorio->getValorCanceladoNaoProcessado()),
            "ranpSaldo" => $this->formatarValor($linhaRelatorio->getSaldoNaoProcessado()),
            "ranpSaldoTotal" => $this->formatarValor($linhaRelatorio->getSaldoTotal()),
        ];
    }

    /**
     * @param $linha
     * @param $tipoLinha
     * @param $tipoPoder
     * @return []
     */
    private function criaLinhaPoder($linha, $tipoLinha, $tipoPoder)
    {
        $linhaRelatorio = $this->linhasProcessadas[1];

        $poder = null;
        foreach ($linhaRelatorio->getLinhas() as $linhaPoder) {
            if (!is_null($tipoPoder) && $linhaPoder->getTipo() == $tipoPoder) {
                $poder = $linhaPoder;
            }
        }

        if (is_null($poder)) {
            return [
                "rapContaLRF" => $linha['conta_lrf'],
                "rapDescricaoContaLRF" => $linha['descricao'],
                "rapTipoContaLRF" => $tipoLinha,
            ];
        }

        return [
            "rapContaLRF" => $linha['conta_lrf'],
            "rapDescricaoContaLRF" => $linha['descricao'],
            "rapTipoContaLRF" => $tipoLinha,
            "rappInscExercAnteriores" => $this->formatarValor(
                $linhaRelatorio->getValorProcessadoEmExerciciosAnteriores()
            ),
            "rappInscFinalExercAnterior" => $this->formatarValor(
                $linhaRelatorio->getValorProcessadoNoExercicioAnterior()
            ),
            "rappPagos" => $this->formatarValor($linhaRelatorio->getValorPagoProcessado()),
            "rappCancelados" => $this->formatarValor($linhaRelatorio->getValorCanceladoProcessado()),
            "rappSaldo" => $this->formatarValor($linhaRelatorio->getSaldoProcessado()),
            "ranpInscExercAnteriores" => $this->formatarValor(
                $linhaRelatorio->getValorNaoProcessadoEmExerciciosAnteriores()
            ),
            "ranpInscFinalExercAnterior" => $this->formatarValor(
                $linhaRelatorio->getValorNaoProcessadoNoExercicioAnterior()
            ),
            "ranpLiquidados" => $this->formatarValor($linhaRelatorio->getValorLiquidadoNaoProcessado()),
            "ranpPagos" => $this->formatarValor($linhaRelatorio->getValorPagoNaoProcessado()),
            "ranpCancelados" => $this->formatarValor($linhaRelatorio->getValorCanceladoNaoProcessado()),
            "ranpSaldo" => $this->formatarValor($linhaRelatorio->getSaldoNaoProcessado()),
            "ranpSaldoTotal" => $this->formatarValor($linhaRelatorio->getSaldoTotal()),
        ];
    }
}
