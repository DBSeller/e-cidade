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

use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use Exception;
use \Periodo;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoIII;

/**
 * Class RgfGarantiasContraGarantias
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfGarantiasContraGarantias extends ArquivoSigapFiscal
{
    const TAG = 'RGFGarantiasEContragarantias';

    protected $template = [
        'dgcCodigoEntidade',
        'dgcQuadrimestre',
        'dgcSemestre',
        'dgcMesAnoMovimento',
        'dgcContaLRF',
        'dgcDescricaoContaLRF',
        'dgcSaldoExercAnterior',
        'dgcSaldoExerc1',
        'dgcSaldoExerc2',
        'dgcSaldoExerc3',
        'dgcMedCorretivas',
    ];

    private $linhasTemplate = [];
    private $linhasProcessadas = [];

    /**
     * @var int[]
     */
    private $codigosQuadrimestres = [
        7 => 14,
        9 => 15,
        11 => 16
    ];

    protected function processar()
    {
        $this->getLinhasProcessadas();
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $arquivo = 'linhas_RGF_Demonstrativo_Garantias_Contragarantiasde_Valores.php';
        $this->linhasTemplate = require($path . DS . $arquivo);
        return $this->linhasTemplate;
    }

    /**
     * @throws Exception
     */
    public function getLinhasProcessadas()
    {
        if (!array_key_exists($this->periodo->getCodigo(), $this->codigosQuadrimestres)) {
            throw new Exception(sprintf(
                "Código do período informado não é válido para emissão do xml.\n%s",
                static::TAG
            ));
        }

        $this->periodo = new Periodo($this->codigosQuadrimestres[$this->periodo->getCodigo()]);
        $anexo = AnexoIII::getInstance($this->ano, $this->periodo);
        $this->linhasProcessadas = $anexo->getDados();
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaCalculo($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        $quadrimestre1 = empty($linhaRelatorio->ate_1_quadrimestre) ? 0 : $linhaRelatorio->ate_1_quadrimestre;
        $quadrimestre2 = empty($linhaRelatorio->ate_2_quadrimestre) ? 0 : $linhaRelatorio->ate_2_quadrimestre;
        $quadrimestre3 = empty($linhaRelatorio->ate_3_quadrimestre) ? 0 : $linhaRelatorio->ate_3_quadrimestre;

        return [
            'dgcContaLRF' => $linha['conta_lrf'],
            'dgcDescricaoContaLRF' => $linha['descricao'],
            'dgcSaldoExercAnterior' => $this->formatarValor($linhaRelatorio->saldo_exercicio_anterior),
            'dgcSaldoExerc1' => $this->formatarValor($quadrimestre1),
            'dgcSaldoExerc2' => $this->formatarValor($quadrimestre2),
            'dgcSaldoExerc3' => $this->formatarValor($quadrimestre3),
            'dgcMedCorretivas' => '',
        ];
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            'dgcContaLRF' => $linha['conta_lrf'],
            'dgcDescricaoContaLRF' => $linha['descricao'],
        ];
    }

    /**
     * @return array
     */
    protected function criaEstruturaCabecalho()
    {
        return [
            'dgcCodigoEntidade' => $this->codigoTCE,
            'dgcQuadrimestre' => PeriodoDePara::quadrimestre($this->periodo),
            'dgcSemestre' => 0,
            'dgcMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
        ];
    }
}
