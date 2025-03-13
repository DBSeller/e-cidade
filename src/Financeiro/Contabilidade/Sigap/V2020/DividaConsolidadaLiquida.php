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

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoII;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use Periodo;

class DividaConsolidadaLiquida extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RGFDividaConsolidada';

    /**
     * @var string[]
     */
    protected $template = [
        "dclCodigoEntidade",
        "dclQuadrimestre",
        "dclSemestre",
        "dclMesAnoMovimento",
        "dclContaLRF",
        "dclDescricaoContaLRF",
        "dclSaldoExercAnterior",
        "dclSaldoExerc1",
        "dclSaldoExerc2",
        "dclSaldoExerc3",
        "dclValorUnico",
        "dclValorUnicoPerc"
    ];

    /**
     * @var array
     */
    protected $linhasProcessadas = [];

    /**
     * @var integer
     */
    protected $quadrimestre;

    protected function processar()
    {
        $this->getLinhasProcessadas();
    }

    public function getLinhasProcessadas()
    {
        $this->quadrimestre = PeriodoDePara::quadrimestre($this->periodo);

        $periodoQuadrimestral = [
            7 => 14,
            9 => 15,
            11 => 16
        ];

        $this->periodo = new Periodo($periodoQuadrimestral[$this->periodo->getCodigo()]);

        $layout = new AnexoII($this->ano, $this->periodo);
        $layout->processar();
        $this->linhasProcessadas = $layout->getLinhas();
    }

    /**
     * @return array|mixed
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RGF_Demonstrativo_Divida_Consolidada_Liquida_AnexoII.php');
        return $this->linhasTemplate;
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaCalculo($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];
        $saldo_exercicio_anterior = $linhaRelatorio->saldo_exercicio_anterior;
        $primeiro_periodo = $linhaRelatorio->primeiro_periodo;
        $segundo_periodo = $linhaRelatorio->segundo_periodo;
        $terceiro_periodo = $linhaRelatorio->terceiro_periodo;

        return [
            "dclContaLRF" => $linha['conta_lrf'],
            "dclDescricaoContaLRF" => $linha['descricao'],
            "dclSaldoExercAnterior" => $this->formatarValor($saldo_exercicio_anterior),
            "dclSaldoExerc1" => $this->formatarValor($primeiro_periodo),
            "dclSaldoExerc2" => $this->formatarValor($segundo_periodo),
            "dclSaldoExerc3" => $this->formatarValor($terceiro_periodo),
        ];
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            "dclContaLRF" => $linha['conta_lrf'],
            "dclDescricaoContaLRF" => $linha['descricao']
        ];
    }

    /**
     * @return array
     */
    protected function criaEstruturaCabecalho()
    {
        return [
            "dclCodigoEntidade" => $this->codigoTCE,
            "dclQuadrimestre" => $this->quadrimestre,
            "dclSemestre" => 0,
            "dclMesAnoMovimento" => $this->periodo->getDataFinal($this->ano)->convertTo(DBDate::DATA_EN),
        ];
    }
}
