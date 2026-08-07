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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

use cl_empresto;
use DBDate;
use Exception;
use RelatoriosLegaisBase;

/**
 * Class FluxoCaixa2020
 * Classe base para o fuxo de caixa.
 */
abstract class FluxoCaixa2020 extends RelatoriosLegaisBase implements FluxoCaixa
{
    /**
     * @var bool
     */
    protected $exibirExercicioAnterior;

    /**
     * @var DBDate
     */
    protected $dataInicialAnterior;
    /**
     * @var DBDate
     */
    protected $dataFinalAnterior;

    public function __construct($iAnoUsu, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, static::CODIGO_RELATORIO, $iCodigoPeriodo);

        /*
         * Monta o período anterior
         */
        $this->dataInicialAnterior = clone $this->getDataInicial();
        $this->dataInicialAnterior->modificarIntervalo('-1 year');

        $this->dataFinalAnterior = clone $this->getDataFinal();
        $this->dataFinalAnterior->modificarIntervalo('-1 year');
    }

    public function getNota($oPdf)
    {
        $oPdf->ln();
        $this->notaExplicativa($oPdf, [$oPdf, 'addPage'], 190);
    }

    public function getAssinatura($oPdf)
    {
        if ($oPdf->getAvailHeight() < 45) {
            $oPdf->addPage();
        }
        $oAssinatura = new \cl_assinatura();
        $oPdf->ln(18);
        assinaturas($oPdf, $oAssinatura, 'BG', false, false);
    }

    /**
     * @param bool $lExibirExercicioAnterior
     */
    public function setExibirExercicioAnterior($lExibirExercicioAnterior)
    {
        $this->exibirExercicioAnterior = $lExibirExercicioAnterior;
    }

    protected function getResourceReceita($where, $ano, DBDate $dataInicial, DBDate $dataFinal)
    {
        db_query('DROP TABLE IF EXISTS work_receita');

        return ReceitaSaldo(
            11,
            1,
            3,
            true,
            $where,
            $ano,
            $dataInicial->getDate(),
            $dataFinal->getDate()
        );
    }

    /**
     * @param $where
     * @param $ano
     *
     * @return bool|false|resource|string
     */
    protected function getResourceDespesa($where, $ano, DBDate $dataInicial, DBDate $dataFinal)
    {
        db_query('drop table if exists work_dotacao;');

        return db_dotacaosaldo(
            8,
            2,
            2,
            true,
            $where,
            $ano,
            $dataInicial->getDate(),
            $dataFinal->getDate()
        );
    }

    public function getResourceVerificacao($where, $ano, DBDate $dataInicial, DBDate $dataFinal)
    {
        db_query('DROP TABLE IF EXISTS work_pl');
        db_query('DROP TABLE IF EXISTS work_pl_estrut');
        db_query('DROP TABLE IF EXISTS work_pl_estrut');
        db_query('DROP TABLE IF EXISTS work_pl_estrutmae');

        return db_planocontassaldo_matriz(
            $ano,
            $dataInicial->getDate(),
            $dataFinal->getDate(),
            false,
            $where,
            '',
            'true',
            'false'
        );
    }

    /**
     * @param $rs
     * @param $tipo
     */
    protected function calculaLinhaColuna($rs, \stdClass $linha, array $coluna, $tipo)
    {
        $linha = RelatoriosLegaisBase::calcularValorDaLinha($rs, $linha, $coluna, $tipo);
        $this->aLinhasConsistencia[$linha->ordem];
    }

    /**
     * Calcula apenas as linhas da receita.
     */
    protected function calcularLinhasReceita()
    {
        $where = "o70_instit in ({$this->getInstituicoes()})";
        $rs = $this->getResourceReceita($where, $this->iAnoUsu, $this->getDataInicial(), $this->getDataFinal());

        $rsAnterior = null;

        if ($this->exibirExercicioAnterior) {
            $ano = $this->iAnoUsu - 1;
            $rsAnterior = $this->getResourceReceita($where, $ano, $this->dataInicialAnterior, $this->dataFinalAnterior);
        }

        $tipoCalculo = RelatoriosLegaisBase::TIPO_CALCULO_RECEITA;
        $this->executaCalculoLinhas($this->aLinhasProcessarReceita, $rs, $rsAnterior, $tipoCalculo);
    }

    protected function calcularLinhasDespesa()
    {
        $where = " o58_instit in({$this->getInstituicoes()})";
        $rs = $this->getResourceDespesa($where, $this->iAnoUsu, $this->getDataInicial(), $this->getDataFinal());

        $rsAnterior = null;
        if ($this->exibirExercicioAnterior) {
            $ano = $this->iAnoUsu - 1;
            $rsAnterior = $this->getResourceDespesa($where, $ano, $this->dataInicialAnterior, $this->dataFinalAnterior);
        }

        $tipoCalculo = RelatoriosLegaisBase::TIPO_CALCULO_DESPESA;
        $this->executaCalculoLinhas($this->aLinhasProcessarDespesa, $rs, $rsAnterior, $tipoCalculo);
    }

    public function calcularLinhasVerificacao()
    {
        $where = " c61_instit in({$this->getInstituicoes()})";
        $rs = $this->getResourceVerificacao($where, $this->iAnoUsu, $this->getDataInicial(), $this->getDataFinal());

        $rsAnterior = null;
        if ($this->exibirExercicioAnterior) {
            $ano = $this->iAnoUsu - 1;
            $rsAnterior = $this->getResourceVerificacao(
                $where,
                $ano,
                $this->dataInicialAnterior,
                $this->dataFinalAnterior
            );
        }

        $tipoCalculo = RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO;
        $this->executaCalculoLinhas($this->aLinhasProcessarVerificacao, $rs, $rsAnterior, $tipoCalculo);
    }

    /**
     * Calcula as linhas informadas de acordo com o tipo de calculo informado.
     *
     * @param array $linhas
     * @param $resource
     * @param $resourceAnterior
     * @param int $tipoCalculo um dos tipos de calculo
     */
    protected function executaCalculoLinhas($linhas, $resource, $resourceAnterior, $tipoCalculo)
    {
        foreach ($linhas as $codigoLinha) {
            $linha = $this->aLinhasConsistencia[$codigoLinha];

            $coluna = $this->getColunasPorLinha($linha, [0]);
            $this->calculaLinhaColuna($resource, $linha, $coluna, $tipoCalculo);

            $linha->vlrexanter = 0;
            if ($this->exibirExercicioAnterior) {
                $coluna = $this->getColunasPorLinha($linha, [1]);
                $this->calculaLinhaColuna($resourceAnterior, $linha, $coluna, $tipoCalculo);
            }
        }
    }

    protected function calculaDespesasDoExercicioAnterior(array $linhas)
    {
        $dao = new cl_empresto();
        $where = " e60_instit in({$this->getInstituicoes()})";

        $sSqlRestosaPagar = $dao->sql_rp_novo(
            $this->iAnoUsu,
            $where,
            $this->getDataInicial()->getDate(),
            $this->getDataFinal()->getDate()
        );

        $rsRestosPagar = db_query($sSqlRestosaPagar);

        $rsRestosPagarAnterior = null;
        if ($this->exibirExercicioAnterior) {
            $sSqlRestosaPagar = $dao->sql_rp_novo(
                $this->iAnoUsu - 1,
                $where,
                $this->dataInicialAnterior->getDate(),
                $this->dataFinalAnterior->getDate()
            );
            $rsRestosPagarAnterior = db_query($sSqlRestosaPagar);
        }

        $tipoCalculo = RelatoriosLegaisBase::TIPO_CALCULO_RESTO;

        foreach ($linhas as $codigoLinha) {
            $linha = $this->aLinhasConsistencia[$codigoLinha];
            $coluna = $this->getColunasPorLinha($linha, [0]);
            $coluna[0]->formula = '#vlrpag+#vlrpagnproc';

            $this->calculaLinhaColuna($rsRestosPagar, $linha, $coluna, $tipoCalculo);

            if ($this->exibirExercicioAnterior) {
                $coluna = $this->getColunasPorLinha($linha, [1]);
                $coluna[0]->formula = '#vlrpag+#vlrpagnproc';
                $this->calculaLinhaColuna($rsRestosPagarAnterior, $linha, $coluna, $tipoCalculo);
            }
        }
    }

    /**
     * Busca as linhas do relatório e processa os valores retornando as linhas do relatório com os valores calculados.
     *
     * @return array
     *
     * @throws Exception
     */
    public function getDados()
    {
        $this->aLinhasConsistencia = $this->getLinhasRelatorio();
        $this->organizaLinhasPorTipoDeCalculo();

        $this->calcularLinhasReceita();
        $this->calcularLinhasDespesa();
        $this->calcularLinhasVerificacao();

        $linhasCalculo = $this->linhasQuePrecisamCalcularDespesasExercicioAnterior();

        $this->calculaDespesasDoExercicioAnterior($linhasCalculo);
        $this->processarValoresManuais();

        $this->processaTotalizadores($this->aLinhasConsistencia);

        return $this->aLinhasConsistencia;
    }

    /**
     * As linhas retornadas por esse metodo tem que somar não apenas os valores do bancete ao qual são vínculadas
     * Mas também todos as despesas pagar no(s) exercício(s) anterior(es).
     *
     * @return int[]
     */
    abstract protected function linhasQuePrecisamCalcularDespesasExercicioAnterior();

    /**
     * @return string
     */
    public function getNomePeriodo()
    {
        $sNomePeriodo = '';
        $aPeriodos = $this->getPeriodos();
        foreach ($aPeriodos as $oPeriodo) {
            if ($oPeriodo->o114_sequencial == $this->iCodigoPeriodo) {
                $sNomePeriodo = $oPeriodo->o114_descricao;
                break;
            }
        }

        return $sNomePeriodo;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->iAnoUsu;
    }

    /**
     * @return bool
     */
    public function isExibirExercicioAnterior()
    {
        return $this->exibirExercicioAnterior;
    }
}
