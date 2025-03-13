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

/**
 * Classe para calculos de valores dos bens
 * Controla os calculos de depreciação e Reavaliacao dos bens.
 * @author DBseller
 * @package patrimonio
 * @subpackage depreciacao
 * @version $Revision: 1.15 $
 */
class CalculoBem
{
    /**
     * @var Bem
     */
    protected $oBem;

    /**
     * Sequencial do valor calculado
     * @var integer
     */
    protected $iSequencial;

    /**
     * Sequencial BensTipoDepreciacao
     * @var integer
     */
    protected $iTipoDepreciacao;

    /**
     * Valor
     * @var integer
     */
    protected $iHistoricoCalculo;

    /**
     * Sequencial bens
     * @var integer
     */
    protected $iBem;

    /**
     * Valor calculado
     * @var float
     */
    protected $nValorCalculado;

    /**
     * Valor Residual
     * @var float
     */
    protected $nValorResidual;

    /**
     * Valor Atual
     * @var float
     */
    protected $nValorAtual;

    /**
     * Valor anterior
     * @var float
     */
    protected $nValorAnterior;

    /**
     * Percentual Depreciado
     * @var float
     */
    protected $nPercentualDepreciado = 0;

    /**
     * valor residual anterior a depreciação do bem
     * @var float
     */
    protected $nValorResidualAnterior;

    /**
     * Método construtor da classe
     */
    public function __construct($iCodigoCalculo = null) {

        if (!empty($iCodigoCalculo)) {
            $oDaoBenshistoricoCalculoBem = new cl_benshistoricocalculobem();
            $sSqlDadosCalculo = $oDaoBenshistoricoCalculoBem->sql_query_file($iCodigoCalculo);
            $rsDadosCalculo = $oDaoBenshistoricoCalculoBem->sql_record($sSqlDadosCalculo);

            if ($oDaoBenshistoricoCalculoBem->numrows == 1) {
                $oDadosCalculo = db_utils::fieldsMemory($rsDadosCalculo, 0);
                $this->setBem(new Bem($oDadosCalculo->t58_bens));
                $this->setHistoricoCalculo($oDadosCalculo->t58_benshistoricocalculo);
                $this->setPercentualDepreciado($oDadosCalculo->t58_percentualdepreciado);
                $this->setSequencial($oDadosCalculo->t58_sequencial);
                $this->setTipoDepreciacao($oDadosCalculo->t58_benstipodepreciacao);
                $this->setValorAnterior($oDadosCalculo->t58_valoranterior);
                $this->setValorAtual($oDadosCalculo->t58_valoratual);
                $this->setValorCalculado($oDadosCalculo->t58_valorcalculado);
                $this->setValorResidual($oDadosCalculo->t58_valorresidual);
                $this->setValorResidualAnterior($oDadosCalculo->t58_valorresidualanterior);
                unset($oDadosCalculo);
            }

            unset($rsDadosCalculo);
            unset($oDaoBenshistoricoCalculoBem);
        }
    }

    /**
     * Retorna o codigo do historico
     * @return integer
     */
    public function getHistoricoCalculo()
    {
        return $this->iHistoricoCalculo;
    }

    /**
     * Define o codigo da planilha de calculo do bem
     * @param integer $iHistoricoCalculo
     */
    public function setHistoricoCalculo($iHistoricoCalculo)
    {
        $this->iHistoricoCalculo = $iHistoricoCalculo;
    }

    /**
     * Retorna o codigo gerado para o calculo
     * @return integer
     */
    public function getSequencial()
    {
        return $this->iSequencial;
    }

    /**
     * Define o codigo do calculo
     * @param integer $iSequencial
     */
    public function setSequencial($iSequencial)
    {
        if (!empty($this->iSequencial)) {
            return;
        }

        $this->iSequencial = $iSequencial;
    }

    /**
     * Retorna  o tipo de Depreciação
     * @return integer
     */
    public function getTipoDepreciacao()
    {
        return $this->iTipoDepreciacao;
    }

    /**
     * Define o tipo de Depreciação do calculo
     * @param integer $iTipoDepreciacao
     *
     */
    public function setTipoDepreciacao($iTipoDepreciacao)
    {
        $this->iTipoDepreciacao = $iTipoDepreciacao;
    }

    /**
     * Retorna o valor Depreciado/Reavaliado
     * @return float
     */
    public function getPercentualDepreciado()
    {
        return $this->nPercentualDepreciado;
    }

    /**
     * Define o percentual que foi depreciado/reavaliado
     * @param float $nPercentualDepreciado
     */
    public function setPercentualDepreciado($nPercentualDepreciado = 0)
    {
        $this->nPercentualDepreciado = $nPercentualDepreciado;
    }

    /**
     * Retorna o valor do anterior
     * @return float
     */
    public function getValorAnterior()
    {
        return $this->nValorAnterior;
    }

    /**
     * Define o valor anterior do calculo
     * @param float $nValorAnterior Valor anterior
     */
    public function setValorAnterior($nValorAnterior)
    {
        $this->nValorAnterior = $nValorAnterior;
    }

    /**
     * Retorna o valor atual do calculo
     * @return float
     */
    public function getValorAtual()
    {
        return $this->nValorAtual;
    }

    /**
     * Define o valor atual do calculo
     * @param float $nValorAtual
     */
    public function setValorAtual($nValorAtual)
    {
        $this->nValorAtual = $nValorAtual;
    }

    /**
     * Retorna o valor calculado
     * @return float
     */
    public function getValorCalculado()
    {
        return $this->nValorCalculado;
    }

    /**
     * Define o valor que foi calculado.
     * @param float $nValorCalculado
     */
    public function setValorCalculado($nValorCalculado)
    {
        $this->nValorCalculado = $nValorCalculado;
    }

    /**
     * retorna o valor Residual do bem com que foi realizado o calculo
     * @return float
     */
    public function getValorResidual()
    {
        return $this->nValorResidual;
    }

    /**
     * Define o valor Residual do bem com que foi realizado o calculo
     * @param float $nValorResidual
     */
    public function setValorResidual($nValorResidual)
    {
        $this->nValorResidual = $nValorResidual;
    }

    /**
     * Define o bem para realizado o calculo
     * @return Bem
     */
    public function getBem()
    {
        return $this->oBem;
    }

    /**
     * @param Bem $oBem
     */
    public function setBem(Bem $oBem)
    {
        $this->oBem = $oBem;
    }

    /**
     * retorna valor residual anterior
     * @return float
     */
    public function getValorResidualAnterior()
    {
        return $this->nValorResidualAnterior;
    }

    /**
     * Define o valor residual anterior
     * @param $nValorResidualAnterior
     */
    public function setValorResidualAnterior($nValorResidualAnterior)
    {
        $this->nValorResidualAnterior = $nValorResidualAnterior;
    }

    /**
     * Persiste os dados do cálculo do bem
     * @param integer $iPlanilha Código da planilha de cálculo
     * @throws Exception
     */
    public function salvar($iPlanilha = null)
    {
        if (!db_utils::inTransaction()) {
            throw new Exception(_M('patrimonial.patrimonio.CalculoBem.sem_transcao_banco_de_dados'));
        }

        $oDaoBenshistoricoCalculoBem = db_utils::getDao("benshistoricocalculobem");
        $oDaoBenshistoricoCalculoBem->t58_bens = $this->getBem()->getCodigoBem();
        $oDaoBenshistoricoCalculoBem->t58_benstipodepreciacao = $this->getBem()->getTipoDepreciacao()->getCodigo();
        $oDaoBenshistoricoCalculoBem->t58_percentualdepreciado = "{$this->getPercentualDepreciado()}";
        $oDaoBenshistoricoCalculoBem->t58_valoranterior        = $this->getValorAnterior();
        $oDaoBenshistoricoCalculoBem->t58_valoratual           = "{$this->getValorAtual()}";
        $oDaoBenshistoricoCalculoBem->t58_valorresidual        = "{$this->getValorResidual()}";
        $oDaoBenshistoricoCalculoBem->t58_valorcalculado       = "{$this->getValorCalculado()}";
        $oDaoBenshistoricoCalculoBem->t58_valorresidualanterior = $this->getValorResidualAnterior();

        if (empty($this->iSequencial)) {
            $oDaoBenshistoricoCalculoBem->t58_benshistoricocalculo = $iPlanilha;
            $oDaoBenshistoricoCalculoBem->incluir(null);
            $this->setHistoricoCalculo($iPlanilha);
        } else {
            $oDaoBenshistoricoCalculoBem->t58_benshistoricocalculo = $this->getHistoricoCalculo();
            $oDaoBenshistoricoCalculoBem->t58_sequencial = $this->getSequencial();
            $oDaoBenshistoricoCalculoBem->alterar($this->getSequencial());
        }

        if ($oDaoBenshistoricoCalculoBem->erro_status == '0') {
            $oStdMsgErro = new stdClass();
            $oStdMsgErro->sDescricao = $this->getBem()->getDescricao();
            $oStdMsgErro->sErro = $oDaoBenshistoricoCalculoBem->erro_msg;
            throw new Exception(_M('patrimonial.patrimonio.CalculoBem.erro_salvar_calculo', $oStdMsgErro));
        }

        /**
         * Alteramos o ultimo calculo do bem
         */
        $this->getBem()->setValorDepreciavel($this->getValorAtual());
        $this->getBem()->setValorResidual($this->getValorResidual());
        $this->getBem()->salvarDepreciacao();
    }

    /**
     * Realiza o calculo da Depreciação/Reequilibrio
     * @throws Exception
     */
    public function calcular()
    {
        /**
         * Total de ano depreciado com base na quantidade de meses identificada na base.
         */
        $iNumeroMeses = $this->getBem()->getQuantidadeMesesDepreciados();
        $iQuantidadeAnoDepreciado = DBNumber::truncate($iNumeroMeses / 12);

        /**
         * Executa a fórmula de cálculo cadastrada para o bem
         */
        $oFormulaCalculo = new FormulaCalculo($this->getBem()->getTipoDepreciacao()->getCodigo());
        $oFormulaCalculo->setVidaUtil($this->getBem()->getVidaUtil());
        $oFormulaCalculo->setValorAquisicao($this->getBem()->getValorUltimaReavaliacao());
        $oFormulaCalculo->setValorResidual($this->getBem()->getValorResidual());
        $oFormulaCalculo->setPercentualDepreciacao($this->getPercentualDepreciado());
        $oFormulaCalculo->setQuantidadeAnosCalculados($iQuantidadeAnoDepreciado);
        $nValorDepreciado = round($oFormulaCalculo->calcular(), 2);

        /**
         * O novo valor do bem depreciado, é o valor depreciavel do bem,
         * menos a Depreciação do mês.
         */
        $nPercentualDepreciado = $oFormulaCalculo->getPercentualDepreciado();

        /**
         * Redefinimos o valor Final do bem .
         */
        $nValorFinalBem = round(($this->getBem()->getValorDepreciavel() - $nValorDepreciado), 2);

        $valorResidual = round($this->getBem()->getValorResidual(), 2);
        $valorDepreciavel = round($this->getBem()->getValorDepreciavel(), 2);
        $valorBase = round($this->getBem()->getValorAquisicao(), 2);
        $valorUltimaReavaliacao = round($this->getBem()->getValorUltimaReavaliacao(), 2);

        /**
         * Verifica se o valor do bem ficou abaixo do valor residual.
         * Ex.:
         *     - Valor Residual: 1.00
         *     - Valor Atual: 1.04
         *     - Cota Depreciação: 1.58
         *
         * Neste caso, deve ser depreciado somente o que resta para que o valor atual iguale ao residual
         */
        if ($nValorFinalBem < $valorResidual) {
            $nValorDepreciado = $valorDepreciavel - $valorResidual;
            $nValorFinalBem = $valorResidual;
        }

        if (!empty($valorUltimaReavaliacao)) {
            $valorBase = $valorUltimaReavaliacao;
        }

        $valorAtual = $iNumeroMeses == 0 ? $valorBase - $nValorDepreciado : $nValorFinalBem;
        $valorAnterior = $iNumeroMeses == 0 ? $valorUltimaReavaliacao : $valorDepreciavel;

        $this->setValorAtual($valorAtual);
        $this->setPercentualDepreciado($nPercentualDepreciado);
        $this->setValorCalculado($nValorDepreciado);
        $this->setValorAnterior($valorAnterior);
        $this->setValorResidual($valorResidual);
        $this->setValorResidualAnterior($valorResidual);
    }

    /**
     * Atualiza o valor de um Bem na seguinte situação:
     * - unificação de mais de 1 bem
     * - quantidade maior que 1
     * - valor total é ímpar, e ao dividir por 2, por exemplo, o valor total entre os bens criados é de 1 centavo
     *   a mais ou a menos
     */
    public function atualizarCentavoValorBem()
    {
        $novoValorAquisicao = $this->oBem->getValorAquisicao() - 0.01;
        $percentual = round(($this->oBem->getValorResidual() * 100) / $this->oBem->getValorAquisicao());
        $novoValorResidual = round(($percentual / 100) * $novoValorAquisicao, 2);
        $novoValorDepreciavel = $novoValorAquisicao - $novoValorResidual;

        $this->oBem->setValorAquisicao($novoValorAquisicao);
        $this->oBem->setValorResidual($novoValorResidual);
        $this->oBem->setValorAtual(0);
        $this->oBem->setValorDepreciavel($novoValorDepreciavel);
    }
}
