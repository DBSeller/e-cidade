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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020;

use ECidade\Configuracao\Opcao\Opcao;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;
use stdClass;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020
 */
class AnexoXII extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{

    const CODIGO_RELATORIO = 217;

    /**
     * @var int
     */
    protected $percentualLeiOrganica = 0;

    protected $valorBaseCalculoLei = 15;

    protected $labelLeiOrganica = '0';

    /**
     * @var int
     */
    protected $percentualMinimoAsps = 15;
    protected $percentualMinimoAplicar = 15;

    /**
     * AnexoXII constructor.
     * @param $ano
     * @param null $codigorelatorio
     * @param null $codigoPeriodo
     */
    public function __construct($ano, $codigorelatorio = null, $codigoPeriodo = null)
    {
        parent::__construct($ano, self::CODIGO_RELATORIO, $codigoPeriodo);
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        if (empty($this->aLinhasConsistencia)) {
            $this->aLinhasConsistencia = $this->getLinhasRelatorio($trazerConfiguracaoPadrao);
            $this->executarBalancetesNecessarios();
            $this->processarValoresManuais();
            $linhasRp = [
                23, 24, 26, 27, 29, 30, 32, 33, 35, 36, 38, 39, 41, 42, 78, 79, 81, 82, 84, 85, 87, 88, 90, 91, 93, 94,
                96, 97
            ];
            if ($this->oPeriodo->getCodigo() != 11) {
                foreach ($linhasRp as $ordem) {
                    $this->aLinhasConsistencia[$ordem]->insc_rp_np = 0;
                }
            }
            $this->processaTotalizadores($this->aLinhasConsistencia);
        }
        $this->valorBaseCalculoLei = $this->percentualMinimoAsps;
        if (!empty($this->getPercentualLeiOrganica())) {
            $this->valorBaseCalculoLei = $this->getPercentualLeiOrganica();
        }
        $this->executarRestosPagarX([58]);
        $this->calcularDespesaMinima();
        return $this->aLinhasConsistencia;
    }

    /**
     * @return float
     * @throws \Exception
     */
    public function getPercentualLeiOrganica()
    {
        if (empty($this->percentualLeiOrganica)) {
            $valor = Opcao::get("limite_asps_lei_organica", $this->getAno());
            if (!empty($valor) && is_numeric($valor->getValor())) {
                $this->labelLeiOrganica = "{$valor}";
                $this->percentualLeiOrganica = $valor->getValor();
                $this->percentualMinimoAplicar = $this->percentualLeiOrganica;
            }
        }
        return $this->percentualLeiOrganica;
    }

    /**
     *Realiza os calculos base da despesa minina
     */
    protected function calcularDespesaMinima()
    {
        $valorTotalDaReceita = $this->aLinhasConsistencia[21]->recrealiza;
        $valorTotalReceitaAplicadoLei = 0;
        $valorTotalReceitaAplicado = 0;
        $valorTotalReceitaAplicadoLoa = 0;
        /**
         * Valor da base ou loa ou Lei Padrao
         */
        if ($this->valorBaseCalculoLei > 0) {
            $valorTotalReceitaAplicado = round($valorTotalDaReceita / $this->valorBaseCalculoLei, 2);
        }

        /**
         * valor para a loa
         */
        if ($this->getPercentualLeiOrganica() > 0) {
            $valorTotalReceitaAplicadoLoa = round($valorTotalDaReceita * ($this->getPercentualLeiOrganica() / 100), 2);
        }
        /**
         * Calculo para a lei padrao
         */
        if ($this->percentualMinimoAsps > 0) {
            $valorTotalReceitaAplicadoLei = round($valorTotalDaReceita * ($this->percentualMinimoAsps / 100), 2);
        }
        $this->aLinhasConsistencia[49]->valor = round($valorTotalReceitaAplicadoLei, 2);
        $this->aLinhasConsistencia[50]->valor = round($valorTotalReceitaAplicadoLoa, 2);
        $valorBaseCalculoAplicado = $this->aLinhasConsistencia[43]->despliq;
        if ($this->getPeriodo()->getCodigo() == 11) {
            $valorBaseCalculoAplicado = $this->aLinhasConsistencia[43]->despemp;
        }
        $diferenca = $valorBaseCalculoAplicado - $valorTotalReceitaAplicado;
        $this->aLinhasConsistencia[51]->valor = $diferenca;
        if ($diferenca < 0) {
            $this->aLinhasConsistencia[52]->valor = $diferenca;
        }
        $percentualAplicado = 0;
        if ($valorTotalDaReceita > 0) {
            $percentualAplicado = round(($valorBaseCalculoAplicado / $valorTotalDaReceita) * 100, 2);
        }
        $this->aLinhasConsistencia[53]->valor = $percentualAplicado;
        $this->ajustarValoresExecutacaoRestos($valorTotalReceitaAplicado, $valorBaseCalculoAplicado);
    }

    /**
     * Ajusta o valor do restos
     * @param $valorMinino
     * @param $valorAplicado
     */
    protected function ajustarValoresExecutacaoRestos($valorMinino, $valorAplicado)
    {
        $this->aLinhasConsistencia[58]->valor_minino_aplicado = $valorMinino;
        $this->aLinhasConsistencia[58]->valor_aplicado_asps_exercicio = $valorAplicado;
    }

    /**
     * Retorna o Label da Lei Organica
     * @return string
     */
    public function getLabelLeiOrganica()
    {
        return $this->labelLeiOrganica;
    }

    /**
     *
     * @param array $linhas
     * @param null $coluna
     */
    protected function executarRestosPagarX(array $linhas = array(), $coluna = null)
    {
        $anoLinhas = [
            58 => ' = 2020',
            59 => ' = 2019',
            60 => ' = 2018',
            61 => ' = 2017',
            62 => ' <= 2016',
        ];
        $oDaoRestosAPagar = new \cl_empresto();
        $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
        $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo(
            $this->iAnoUsu,
            $sWhereRestoPagar,
            $this->getDataInicial()->getDate(),
            $this->getDataFinal()->getDate()
        );

        db_query("drop table if exists w_anexoXII_restos");
        db_query("create temp table w_anexoXII_restos as " . $sSqlRestosaPagar);
        foreach ($linhas as $iLinha) {
            if (empty($coluna) && $coluna !== "0") {
                $coluna = array();
            } elseif (!is_array($coluna)) {
                $coluna = array($coluna);
            }

            $rsRestosPagar = db_query("select * from w_anexoXII_restos where e60_anousu " . $anoLinhas[$iLinha]);
            $oLinha = $this->aLinhasConsistencia[$iLinha];
            $aColunasProcessar = $this->getColunasPorLinha($oLinha, $coluna);
            \RelatoriosLegaisBase::calcularValorDaLinha(
                $rsRestosPagar,
                $oLinha,
                $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_RESTO
            );
        }
    }

    /**
     * Retorna os dados para calculo do anexo simplificado
     * @return stdClass|void
     * @throws \Exception
     */
    public function getDadosSimplificado()
    {
        $aDados = $this->getDados();
        $oDadosSimplificado = new stdClass();
        $valorBaseCalculoAplicado = $this->aLinhasConsistencia[43]->despliq;
        if ($this->getPeriodo()->getCodigo() == 11) {
            $valorBaseCalculoAplicado = $this->aLinhasConsistencia[43]->despemp;
        }
        $oDadosSimplificado->nTotalDespesasSaudeComImpostos = $valorBaseCalculoAplicado;
        $oDadosSimplificado->nPercentualDespesasSaudeComImpostos = $aDados[53]->valor;
        $oDadosSimplificado->nPercentualMinimoAplicar = $this->valorBaseCalculoLei;
        return $oDadosSimplificado;
    }
}
