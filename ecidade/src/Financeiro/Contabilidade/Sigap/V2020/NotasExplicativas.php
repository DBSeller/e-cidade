<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use ECidade\Financeiro\Contabilidade\Sigap\ArquivosSigapFiscalInterface;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use Exception;
use Periodo;
use RelatoriosLegaisBase;

/**
 * Class NotasExplicativas
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class NotasExplicativas implements ArquivosSigapFiscalInterface
{
    const TAG = 'NotasExplicativas';

    /**
     * @var Periodo
     */
    private $periodo;
    /**
     * @var array
     */
    private $codigoInstituicoes;
    /**
     * @var integer
     */
    private $codigoTCE;

    /**
     * Array com as notas explicativas dos relatórios enviados. indexado pelo código do demonstrativo
     * @var array
     */
    private $notas = [];
    /**
     * @var int
     */
    private $ano;

    /**
     * @var string[]
     */
    protected $template = [
        'nteCodigoEntidade',
        'nteMesAnoMovimento',
        'ntePeriodo',
        'nteDemonstrativo',
        'nteNotaExplicativa',
    ];

    private $codigosRelatorios = [
        '01' => 190, // RREOBalancoOrcamentario.xml
        '02' => 96, // RREOBalancoFuncao.xml
        '03' => 178, // RREORecCorrenteLiquida.xml
        '04' => 196, // RREODespesaReceitaRPPS.xml
        '05' => 216, // RREOResultadoPrimarioENominal.xml
        '06' => 97, // RREORestosAPagar.xml
        '07' => 195, // RREOReceitasEDespesasMDE.xml
        '08' => 202, // RREOOperacDeCreditoDespCapital.xml
        '09' => 188, // RREOProjecaoAtuarial.xml
        '10' => 201, // RREOAlienacaoAtivoAplicRecursos.xml
        '11' => 217, // RREOReceitasEDespesasSaude.xml
        '12' => 218, // RREOParcPublicPrivada.xml
        '13' => 181, // RREODemonstrativoSimplificado.xml
        '15' => 182, // RGFDespesaComPessoalDetalhada.xml
        '16' => 183, // RGFDividaConsolidada.xml
        '17' => 184, // RGFGarantiasEContragarantias.xml
        '18' => 185, // RGFOperacoesDeCredito.xml
        '19' => 187, // RGFDisponibilidadeDeCaixaERAP.xml
        '20' => 197, // RGFDemonstrativoSimplificado.xml
    ];


    /**
     * NotasExplicativas constructor.
     * @param Periodo $periodo
     * @param array $codigoInstituicoes
     * @param integer $ano
     * @param integer $codigoTCE
     */
    public function __construct(Periodo $periodo, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
        $this->ano = $ano;
    }

    public function processar()
    {
        foreach ($this->codigosRelatorios as $codigoSigap => $codigosRelatorio) {
            $this->buscaNota($codigoSigap, $codigosRelatorio);
        }
    }

    /**
     * @return string
     */
    public function emitirXML()
    {
        $this->processar();
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);
        foreach ($this->notas as $demonstrativo => $nota) {
            $elementoLinha = $xml->createElement('Elem' . static::TAG);

            $colunas = $this->criaEstruturaColunas($demonstrativo, $nota);
            foreach ($this->template as $tag) {
                $elementoColuna = $xml->createElement($tag, $colunas[$tag]);
                $elementoLinha->appendChild($elementoColuna);
            }

            $principalNode->appendChild($elementoLinha);
        }
        $xml->appendChild($principalNode);

        $filePath = 'tmp' . DS . static::TAG . '_' . $this->codigoTCE . '.xml';
        file_put_contents($filePath, $xml->saveXML());

        return $filePath;
    }

    /**
     * @param $demonstrativo
     * @param $nota
     * @return array
     */
    protected function criaEstruturaColunas($demonstrativo, $nota)
    {
        $periodo = str_pad($this->getCodigoPeriodoSigap($demonstrativo), 2, '0', STR_PAD_LEFT);
        return [
            'nteCodigoEntidade' => $this->codigoTCE,
            'nteMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'ntePeriodo' => $periodo,
            'nteDemonstrativo' => $demonstrativo,
            'nteNotaExplicativa' => utf8_encode($nota),
        ];
    }

    /**
     * @param $demonstrativo
     * @return int
     */
    protected function getCodigoPeriodoSigap($demonstrativo)
    {
        if (in_array($demonstrativo, ['01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13])) {
            return PeriodoDePara::bimestre($this->periodo);
        }

        if (in_array($demonstrativo, [15, 16, 17, 18, 19, 20])) {
            return PeriodoDePara::quadrimestre($this->periodo);
        }
    }

    /**
     * @param string $codigoSigap
     * @param integer $codigosRelatorio
     * @return bool
     * @throws Exception
     */
    private function buscaNota($codigoSigap, $codigosRelatorio)
    {
        $periodo = $this->periodo;
        if (!$this->validaImpressao($codigoSigap)) {
            return false;
        }

        if (in_array($codigoSigap, ['14', '15', '16', '17', '18'])) {
            $periodo = $this->getQuadrimestreEquivalente();
        }

        if (in_array($codigoSigap, ['08', '09', '10', '19'])) {
            $periodo = $this->getSemestralEquivalente();
        }

        $relatorio = new RelatoriosLegaisBase($this->ano, $codigosRelatorio, $periodo->getCodigo());
        $nota = $relatorio->getTextoNotaExplicativa();
        if (!empty($nota)) {
            $this->notas[$codigoSigap] = $nota;
        }
    }

    /**
     * @return Periodo
     * @throws Exception
     */
    private function getQuadrimestreEquivalente()
    {
        $dePara = [
            7 => 14,
            9 => 15,
            11 => 16
        ];
        return new Periodo($dePara[$this->periodo->getCodigo()]);
    }

    /**
     * @return Periodo
     * @throws Exception
     */
    private function getSemestralEquivalente()
    {
        $dePara = [
            8 => 12,
            11 => 13
        ];
        return new Periodo($dePara[$this->periodo->getCodigo()]);
    }

    /**
     * @param $codigoSigap
     * @return bool
     */
    private function validaImpressao($codigoSigap)
    {
        if (in_array($codigoSigap, ['01', '02', '03', '04', '05', '06', '07', 11, 12, 13])) {
            return true;
        }

        $periodo = $this->periodo->getCodigo();
        // periodo equivalente aos 1º, 2º e 3º quadrimestre
        if (in_array($codigoSigap, ['14', '15', '16', '17', '18']) && in_array($periodo, [7, 9, 11])) {
            return true;
        }

        // periodo equivalente ao 2º semestre
        if (in_array($codigoSigap, ['08', '09', '10', '19']) && $periodo == 11) {
            return true;
        }

        // periodo equivalente ao 1º e 2º semestre
        if ($codigoSigap == '20' && in_array($periodo, [8, 11])) {
            return true;
        }

        return false;
    }
}
