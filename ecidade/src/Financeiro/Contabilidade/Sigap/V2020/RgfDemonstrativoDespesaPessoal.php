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

use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoI;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

/**
 * Class RgfDemonstrativoDespesaPessoal
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfDemonstrativoDespesaPessoal extends ArquivoSigapFiscal
{

    const TAG = 'RGFDespesaComPessoalDetalhada';
    /**
     * @var string[]
     */
    private $linhasTemplate;

    protected $template = [
        'dcpCodigoEntidade',
        'dcpQuadrimestre',
        'dcpSemestre',
        'dcpMesAnoMovimento',
        'dcpContaLRF',
        'dcpDescricaoContaLRF',
        'dcpDespLiqMR11',
        'dcpDespLiqMR10',
        'dcpDespLiqMR9',
        'dcpDespLiqMR8',
        'dcpDespLiqMR7',
        'dcpDespLiqMR6',
        'dcpDespLiqMR5',
        'dcpDespLiqMR4',
        'dcpDespLiqMR3',
        'dcpDespLiqMR2',
        'dcpDespLiqMR1',
        'dcpDespLiqMR',
        'dcpDespLiquidadas',
        'dcpDespInscRAPNProcessadas',
        'dcpValorUnico',
        'dcpValorUnicoPerc',
    ];

    private $codigosQuadrimestres = [
        7 => 14,
        9 => 15,
        11 => 16
    ];
    /**
     * @var array
     */
    private $linhasProcessadas = [];

    protected function processar()
    {
        $this->getLinhasProcessadas();
        $this->getLinhasTemplate();
    }

    /**
     * @return array
     */
    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RGF_Demonstrativo_Despesa_Pessoal_Detalhada.php');
        return $this->linhasTemplate;
    }

    private function getLinhasProcessadas()
    {
        $this->quadrimestre = PeriodoDePara::quadrimestre($this->periodo);


        $instituicoes = array_map(function ($instituicao) {
            return \InstituicaoRepository::getInstituicaoByCodigo($instituicao);
        }, $this->codigoInstituicoes);
        $this->periodo = new \Periodo($this->codigosQuadrimestres[$this->periodo->getCodigo()]);
        $oAnexo = AnexoI::getInstance($this->ano, $this->periodo, $instituicoes, 2);

        $this->linhasProcessadas = $oAnexo->getDados();
    }


    protected function criaLinhaCalculo($linha)
    {
        $linhaRelatorio = $linha['linha_ecidade'];
        if ($linhaRelatorio >= 1 && $linhaRelatorio <= 19) {
            return $this->gerarLinhasMensais($linhaRelatorio);
        }
        return $this->gerarLinhaPercentuais($linhaRelatorio);
    }

    /**
     * Calcula as linhas que tenham valores mensais.
     * @param $linhaRelatorio
     * @return array
     */
    protected function gerarLinhasMensais($linha)
    {

        $linhaRelatorio = $this->linhasProcessadas[$linha];
        if (empty($linhaRelatorio)) {
            $a = 1;
        }
        $linha = [
            'dcpDespLiqMR11' => 0,
            'dcpDespLiqMR10' => 0,
            'dcpDespLiqMR9' => 0,
            'dcpDespLiqMR8' => 0,
            'dcpDespLiqMR7' => 0,
            'dcpDespLiqMR6' => 0,
            'dcpDespLiqMR5' => 0,
            'dcpDespLiqMR4' => 0,
            'dcpDespLiqMR3' => 0,
            'dcpDespLiqMR2' => 0,
            'dcpDespLiqMR1' => 0,
            'dcpDespLiqMR' => $linhaRelatorio->meses[0]->valor,
            'dcpDespLiquidadas' => $linhaRelatorio->liquidado_ultimo_ano,
            'dcpDespInscRAPNProcessadas' => $linhaRelatorio->rp_nao_processado,

        ];
        foreach ($linhaRelatorio->meses as $mes => $dados) {
            if ($mes === 0) {
                $linha['dcpDespLiqMR'] = $dados->valor;
                continue;
            }
            $linha["dcpDespLiqMR{$mes}"] = $dados->valor;
        }
        return $linha;
    }

    protected function gerarLinhaPercentuais($linhaRelatorio)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linhaRelatorio];
        return [
            'dcpValorUnico' => $linhaRelatorio->valor,
            'dcpValorUnicoPerc' => $linhaRelatorio->percentual
        ];
    }

    protected function criaLinhaTitulo($linha)
    {
        return [
            'dcpContaLRF' => $linha['conta_lrf'],
            'dcpDescricaoContaLRF' => $linha['descricao'],
        ];
    }

    protected function criaEstruturaCabecalho()
    {
        $periodo = $this->periodo->getDataFinal($this->ano);
        return [
            'dcpCodigoEntidade' => $this->codigoTCE,
            'dcpQuadrimestre' => PeriodoDePara::quadrimestre($this->periodo),
            'dcpSemestre' => 0,
            'dcpMesAnoMovimento' => $periodo->getDate()
        ];
    }
}
