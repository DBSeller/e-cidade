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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018;

use cl_assinatura;
use ECidade\Financeiro\Contabilidade\Calculo\Despesa;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\ProcessamentoRelatorioLegal;
use Exception;
use Instituicao;
use ParameterException;
use PDFDocument;
use stdClass;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII as ReceitaCorrenteFactory;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018
 */
class AnexoI extends ProcessamentoRelatorioLegal
{

    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 182;

    /**
     * Oficial, Retrato
     * @var integer
     */
    const MODELO_OFICIAL             = 1;

    /**
     * Detalhamento Mensal, Paisagem
     * @var integer
     */
    const MODELO_DETALHAMENTO_MENSAL = 2;

    /**
     * Modelo que vai ser impresso
     * @var int
     */
    protected $iModelo;

    /**
     * Data de Inicio do período de 12 meses do relatório
     * @var \DBDate
     */
    private $oDataInicio;

    /**
     * @var ReceitaCorrenteLiquida
     */
    private $oRCL;

    /**
     * Array usado no Modelo de Detalhamento Mensal
     * Contêm o Mês/Ano
     * @var array
     */
    private $aIntervaloMeses = array();

    /**
     * Variavel para das linhas do pdf
     * @var array
     */
    protected $aTamanhoCelulas = array(

        self::MODELO_DETALHAMENTO_MENSAL => array(
            'iLinha'      => 283,
            'iWDescricao' => 43,
            'iWMes'       => 17,
            'iWTotais'    => 19,
        ),
        self::MODELO_OFICIAL => array(
            'iLinha'      => 190,
            'iWDescricao' => 130,
            'iWTotais'    => 30,
        )
    );

    /**
     * Se esta selecionado para emitir todo exercício. (ano cheio de jan a dez)
     * @var boolean
     */
    protected $lDoExercicio = false;


    /**
     * Valores encontrados para a despesa separados por mês:
     * Ex: array(
     *  linha_1 => (01/2016 => <valor>, 02/2016 => <valor>, [...])
     * )
     * @var array
     */
    protected $aValoresDespesaPorLinhaMes = array();


    protected $linhaFinalQuadroDespesa = 16;
    protected $totalizadoresQuadroDespesa = array(1, 2, 6, 11, 16);

    /**
     * AnexoI constructor.
     * @param $iAno
     * @param $oPeriodo
     * @param $aInstituicoes
     * @param $iModelo
     * @throws ParameterException
     */
    public function __construct($iAno, $oPeriodo, $aInstituicoes, $iModelo = self::MODELO_DETALHAMENTO_MENSAL)
    {
        parent::__construct($iAno, $oPeriodo, static::CODIGO_RELATORIO, $aInstituicoes);
        $this->iModelo = $iModelo;
        $this->lDoExercicio = in_array($this->oPeriodo->getCodigo(), array(13, 16, 28));
        $this->calculaDataInicial();

        $aInstituicoesRCL = \InstituicaoRepository::getInstituicoes();
        //$this->oRCL       = new ReceitaCorrenteLiquida($iAno, $aInstituicoesRCL, 178);
    }


    /**
     * @return int
     */
    public function getModelo()
    {
        return $this->iModelo;
    }

    /**
     * @return \DBDate
     */
    public function getDataInicio()
    {
        return $this->oDataInicio;
    }

    /**
     * @return \DBDate
     * @throws ParameterException
     */
    private function calculaDataInicial()
    {
        if (!is_null($this->oDataInicio)) {
            return $this->oDataInicio;
        }

        // 2º SEMESTRE, 3º QUADRIMESTRE, DEZEMBRO
        if ($this->lDoExercicio) {
            $this->oDataInicio = new \DBDate("{$this->iAno}-01-01");
            return $this->oDataInicio;
        }

        $oDataFinal  = \Periodo::dataFinalPeriodo($this->oPeriodo->getCodigo(), $this->iAno);
        $aDataFinal  = explode('-', $oDataFinal->getDate());
        $mMesInicial = ((int) $aDataFinal[1]) + 1;
        $mMesInicial = str_pad($mMesInicial, 2, 0, STR_PAD_LEFT);

        $iAnoDataInicial   = $this->iAno - 1;
        $this->oDataInicio = new \DBDate("{$iAnoDataInicial}-{$mMesInicial}-01");

        return $this->oDataInicio;
    }

    /**
     * @return array
     * @throws ParameterException
     */
    protected function getMesesAbrangente()
    {
        if (empty($this->aIntervaloMeses)) {
            $aIntervaloMeses = \DBDate::getMesesNoIntervalo($this->oDataInicio, $this->oDataFinal, false) ;
            foreach ($aIntervaloMeses as $iAno => $aMeses) {
                foreach ($aMeses as $iMes) {
                    $this->aIntervaloMeses[$iMes] = \DBDate::getMesAbreviado($iMes) . '/' . $iAno;
                }
            }
        }
        return $this->aIntervaloMeses;
    }

    /**
     * Inicializa um array com as linhas e as competencias (mes/ano) presente no relatório
     */
    protected function inicializaValoresDespesaPorLinhaMes()
    {
        foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {
            list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
            for ($iLinha = 1; $iLinha <= 16; $iLinha ++) {
                $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] = 0;
            }
        }
    }


    /**
     * @return array
     * @throws Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $modelosImpressao = ['mdf', 'rondonia', 'in13'];
        if (in_array($this->getModeloImpressao(), $modelosImpressao)) {
            parent::getDados($trazerConfiguracaoPadrao);
            $this->inicializaValoresDespesaPorLinhaMes();
            $this->processarCalculoPorMeses();
            return $this->aLinhasConsistencia;
        }

        if (empty($this->aLinhasConsistencia)) {
            parent::getDados($trazerConfiguracaoPadrao);
            $this->inicializaValoresDespesaPorLinhaMes();
            $this->processarCalculoPorMeses();
        }

        if ($this->getModeloImpressao() == 'mdf') {
            unset($this->aLinhasConsistencia[24], $this->aLinhasConsistencia[25], $this->aLinhasConsistencia[26]);
        }

        $descricao  = '(-) Transferências obrigatórias da União relativas ';
        $descricao .= 'às emendas individuais (art 166-A, § 1º , da CF) (V)';
        $this->aLinhasConsistencia[18]->descricao = $descricao;

        $descricao  = 'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA CÁLCULO DOS LIMITES DA ';
        $descricao .= 'DESPESA COM PESSOAL (VII)= (IV-V-VI)';
        $this->aLinhasConsistencia[19]->descricao = $descricao;

        $descricao = 'DESPESA TOTAL COM PESSOAL - DTP (VIII) = (III a + III b)';
        $this->aLinhasConsistencia[20]->descricao = $descricao;

        $this->aLinhasConsistencia[21]->descricao = 'LIMITE MÁXIMO (IX) (incisos I, II e III, art. 20 da LRF)';

        $descricao = 'LIMITE PRUDENCIAL (X) = (0,95 x IX) (parágrafo único do art. 22 da LRF)';
        $this->aLinhasConsistencia[22]->descricao = $descricao;

        $descricao = 'LIMITE DE ALERTA (XI) = (0,90 x IX) (inciso II do §1º do art. 59 da LRF)';
        $this->aLinhasConsistencia[23]->descricao = $descricao;
        return $this->aLinhasConsistencia;
    }

    protected function getModeloImpressao()
    {
        $opcaoRelatorio = \ECidade\Configuracao\Opcao\Opcao::get(
            'modelo_anexo_1_rgf',
            $this->iAno
        );
        if (empty($opcaoRelatorio)) {
            return 'mdf';
        }
        return $opcaoRelatorio->getValor();
    }

    /**
     * @param \DBDate $oDataInicial
     * @param \DBDate $oDataFinal
     * @throws ParameterException
     */
    protected function processarDesdobramentosPorDatas(\DBDate $oDataInicial, \DBDate $oDataFinal)
    {
        foreach ($this->aLinhasProcessarDespesa as $iLinha) {
            $oStdLinha = $this->aLinhasConsistencia[$iLinha];
            foreach ($oStdLinha->parametros->contas as $oStdConta) {
                if ($oStdConta->nivel <= 7) {
                    continue;
                }

                $oEstrutural = new Estrutural($oStdConta->estrutural);
                $oDespesa    = new Despesa($this->aInstituicoes);
                $oDespesa->setDataInicial($oDataInicial);
                $oDespesa->setDataFinal($oDataFinal);
                $aDocumentos = array(
                    'not in' => array(
                        Documento::LIQUIDACAO_RP,
                        Documento::ESTORNO_LIQUIDACAO_RP,
                        Documento::LIQUIDACAO_RP_ESTOQUE_PATRIMONIO,
                        Documento::ESTORNO_LIQUIDACAO_RP_ESTOQUE_PATRIMONIO
                    )
                );
                $oValores = $oDespesa->getValorLiquidadoPorElementoDoOrcamento($oEstrutural, $aDocumentos);

                $nValorLiquidado = $oValores->getValorInclusaoMenosEstorno();
                if ($oStdConta->exclusao) {
                    $nValorLiquidado *= -1;
                }
                $this->aLinhasConsistencia[$oStdLinha->ordem]->{$oStdLinha->colunas[1]->o115_nomecoluna} =
                    ($oStdLinha->liquidado_ultimo_ano + $nValorLiquidado);
            }
        }
    }

    /**
     * Processa os valores do balancete dos últimos 12 meses a partir do período informado
     * @throws Exception
     */
    protected function processarCalculoPorMeses()
    {
        /**
         * limpa os valores calculados no metodo getDados()
         */
        foreach ($this->aLinhasConsistencia as $oStdLinha) {
            $oStdLinha->liquidado_ultimo_ano = 0;
            if (!$this->lDoExercicio) {
                $oStdLinha->rp_nao_processado = 0;
            }
        }

        if (count($this->aLinhasProcessarDespesa) > 0) {
            foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {
                list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
                $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
                $oDataInicialPeriodo = new \DBDate("01/{$iMes}/{$iAno}");
                $oDataFinalPeriodo = new \DBDate("{$iUltimoDiaMes}/{$iMes}/{$iAno}");

                $sWhereDespesa = " o58_instit in({$this->getInstituicoes()})";
                $rsBalanceteDespesa = db_dotacaosaldo(
                    8,
                    2,
                    2,
                    true,
                    $sWhereDespesa,
                    $iAno,
                    $oDataInicialPeriodo,
                    $oDataFinalPeriodo
                );

                foreach ($this->aLinhasProcessarDespesa as $iLinha) {
                    $oLinha = $this->aLinhasConsistencia[$iLinha];
                    $nValorAnterior = $oLinha->liquidado_ultimo_ano;
                    $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(1));
                    \RelatoriosLegaisBase::calcularValorDaLinha(
                        $rsBalanceteDespesa,
                        $oLinha,
                        $aColunasProcessar,
                        \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                    );

                    if (self::MODELO_DETALHAMENTO_MENSAL == $this->iModelo) {
                        $this->processarDesdobramentosPorDatas($oDataInicialPeriodo, $oDataFinalPeriodo);
                    }

                    $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] =
                        $this->aLinhasConsistencia[$iLinha]->liquidado_ultimo_ano - $nValorAnterior;
                    $this->limparEstruturaBalanceteDespesa();
                }
            }
        }


        /**
         * Calcula a coluna TOTAL (ÚLTIMOS 12 MESES) para cada linha do quadro de Despesas
         */
        foreach ($this->aLinhasProcessarDespesa as $linha) {
            $somaLiquidadoUltimoAnoLinha = 0;

            foreach ($this->getMesesAbrangente() as $mes => $competencia) {
                list($mesAbreviado, $ano) = explode('/', $competencia);
                $somaLiquidadoUltimoAnoLinha += $this->aValoresDespesaPorLinhaMes[$linha]["{$mes}/{$ano}"];
            }
            $this->aLinhasConsistencia[$linha]->liquidado_ultimo_ano = $somaLiquidadoUltimoAnoLinha;
        }

        if (!$this->lDoExercicio) {
            $this->calcularRestosAPagarDoExercicioAnterior();
        }
        if ($this->iAnoUsu < 2020) {
            $this->calculaValorManual();
        }
        $this->processaTotalizadores($this->aLinhasConsistencia);
    }

    /**
     * Calcula os Restos a Pagar do exercício anterior
     * Valor do RP = RP não processados - Anulação de RP não processado no execício atual
     * Ex.: Valor do RP =  (RP não processados 2016) - (Anulação de RP não processado 2017)
     * @throws ParameterException
     */
    protected function calcularRestosAPagarDoExercicioAnterior()
    {
        $aLinhasCalcular = array(1, 11);

        $oStdColunaProcessarPeriodoAnterior            = new stdClass();
        $oStdColunaProcessarPeriodoAnterior->nome      = "rp_nao_processado";
        $oStdColunaProcessarPeriodoAnterior->formula   = "#e91_vlremp-#e91_vlranu-#e91_vlrliq";
        $oStdColunaProcessarPeriodoAnterior->analisada = false;

        $aColunasProcessarAnterior = array($oStdColunaProcessarPeriodoAnterior);

        $oStdColunaProcessarPeriodoAtual            = new stdClass();
        $oStdColunaProcessarPeriodoAtual->nome      = "rp_nao_processado";
        $oStdColunaProcessarPeriodoAtual->formula   = "#vlranuliqnaoproc";
        $oStdColunaProcessarPeriodoAtual->analisada = false;

        $aColunasProcessarPeriodoAtual = array($oStdColunaProcessarPeriodoAtual);

        $iAnoCalculo                = $this->iAnoUsu;
        $sDataInicioPeriodoAnterior = $this->oDataInicio->getDate();
        $sDataFimPeriodoAnterior    = "{$this->oDataInicio->getAno()}-12-31";

        $sDataInicioPeriodoAtual    = "$this->iAnoUsu-01-01";
        $sDataFimPeriodoAtual       = $this->oPeriodo->dataFinalPeriodo($this->oPeriodo->getCodigo(), $this->iAnoUsu);


        $oDaoRestosAPagar         = new \cl_empresto();
        $sInstituicoes            = " e60_instit in({$this->getInstituicoes()})";
        $sSqlRestosaPagarAnterior = $oDaoRestosAPagar->sql_rp_novo(
            $iAnoCalculo,
            $sInstituicoes,
            $sDataInicioPeriodoAnterior,
            $sDataFimPeriodoAnterior
        );
        $sSqlRestosaPagarAtual    = $oDaoRestosAPagar->sql_rp_novo(
            $iAnoCalculo,
            $sInstituicoes,
            $sDataInicioPeriodoAtual,
            $sDataFimPeriodoAtual
        );

        $rsRestosPagarAnterior   = db_query($sSqlRestosaPagarAnterior);
        if (!$rsRestosPagarAnterior) {
            throw new Exception("Ocorreu um erro ao consultar os restos a pagar do ano anterior.");
        }

        $rsRestosPagarAtual      = db_query($sSqlRestosaPagarAtual);
        if (!$rsRestosPagarAtual) {
            throw new Exception("Ocorreu um erro ao consultar os restos a pagar do ano anterior.");
        }

        foreach ($this->aLinhasConsistencia as $oStdLinha) {
            if (in_array($oStdLinha->ordem, $aLinhasCalcular)) {
                continue;
            }

            // calcula o valor do RP do periodo anterior
            \RelatoriosLegaisBase::calcularValorDaLinha(
                $rsRestosPagarAnterior,
                $oStdLinha,
                $aColunasProcessarAnterior,
                \RelatoriosLegaisBase::TIPO_CALCULO_RESTO
            );

            // armazena o valor dos Restos a Pagar não processados
            $nValorRestosPagar = $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado;

            // zera a variável para calcular os Restos a Pagar ANULADOS não processados
            $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado = 0;
            // calcula o valor dos Restos a Pagar ANULADOS não processados
            \RelatoriosLegaisBase::calcularValorDaLinha(
                $rsRestosPagarAtual,
                $oStdLinha,
                $aColunasProcessarPeriodoAtual,
                \RelatoriosLegaisBase::TIPO_CALCULO_RESTO
            );

            $nValorAnulado = $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado;

            $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado = $nValorRestosPagar - $nValorAnulado;

            foreach ($oStdLinha->parametros->contas as $oStdConta) {
                if ($oStdConta->nivel <= 7) {
                    continue;
                }

                $oEstrutural = new Estrutural($oStdConta->estrutural);
                $oDespesa    = new Despesa($this->aInstituicoes);
                $oDespesa->setDataInicial($this->oDataInicio);
                $oDespesa->setDataFinal(new \DBDate("{$this->oDataInicio->getAno()}-12-31"));
                $oValorInscritoRP = $oDespesa->getValorInscritoEmRestosAPagarNaoProcessados(
                    $oEstrutural,
                    array('in' => array(1007))
                );

                $oDespesa->setDataInicial($this->oDataInicial);
                $oDespesa->setDataFinal($this->oDataFinal);
                $oValorAnuladoRP  = $oDespesa->getValorAnuladoPorElementoDoOrcamento(
                    $oEstrutural,
                    array('in' => array(32))
                );

                $nValor = $oValorInscritoRP->getValorInclusao() - $oValorAnuladoRP->getValorEstorno();
                if ($oStdConta->exclusao) {
                    $nValor *= -1;
                }
                $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado += $nValor;
            }
        }
    }

    /**
     * Calcula o valor que foi informado manualmente da:
     *  - liquidação: sempre
     *  - restos a pagar: somente quando não é do exercício
     *
     * Calcula o valor da liquidação que foi informado manualmente
     */
    protected function calculaValorManual()
    {
        foreach ($this->aLinhasProcessarDespesa as $iLinha) {
            $aLinhasManuais = $this->aLinhasConsistencia[$iLinha]->oLinhaRelatorio->getValoresColunas(
                null,
                null,
                $this->getInstituicoes(),
                $this->iAnoUsu
            );
            foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {
                list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
                foreach ($aLinhasManuais as $oLinhaManual) {
                    if ($oLinhaManual->colunas[0]->o117_valor == $sCompetencia) {
                        $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] +=
                            $oLinhaManual->colunas[1]->o117_valor;
                        // Atualiza o totalizador da coluna liquidado
                        $this->aLinhasConsistencia[$iLinha]->liquidado_ultimo_ano +=
                            $oLinhaManual->colunas[1]->o117_valor;

                        if (!$this->lDoExercicio) {
                            $this->aLinhasConsistencia[$iLinha]->rp_nao_processado +=
                                $oLinhaManual->colunas[2]->o117_valor;
                        }
                    }
                }
            }
        }
    }


    /**
     * Processa os dados para emissão do relatório em modelo Detalhamento Mensal
     * @throws Exception
     */
    protected function processarDetalhamentoMensal()
    {

        $this->getDados();

        $aLinhasTotalizadorasSoma = array(
            2  => array(3, 4, 5),
            6  => array(7, 8, 9),
            1  => array(2, 6, 10),
            11 => array(12, 13, 14, 15)
        );

        $aLinhasTotalizadorasSub = array(
            16 => array(1, 11)
        );

        foreach ($aLinhasTotalizadorasSoma as $iLinhaTotalizadora => $aLinhasSomar) {
            foreach ($this->aValoresDespesaPorLinhaMes as $iLinha => $aValoreMesAno) {
                foreach ($aValoreMesAno as $sMesAno => $sValor) {
                    if (in_array($iLinha, $aLinhasSomar)) {
                        $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] += $sValor;
                    }
                }
            }
        }

        foreach ($aLinhasTotalizadorasSub as $iLinhaTotalizadora => $aLinhasSubtrair) {
            foreach ($this->aValoresDespesaPorLinhaMes as $iLinha => $aValoreMesAno) {
                foreach ($aValoreMesAno as $sMesAno => $sValor) {
                    if (in_array($iLinha, $aLinhasSubtrair)) {
                        if ($aLinhasSubtrair[0] == $iLinha) {
                            $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] += $sValor;
                        } else {
                            $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] -= $sValor;
                        }
                    }
                }
            }
        }



        $this->aLinhasConsistencia[16]->liquidado_ultimo_ano =
            $this->aLinhasConsistencia[1]->liquidado_ultimo_ano - $this->aLinhasConsistencia[11]->liquidado_ultimo_ano;
        $this->aLinhasConsistencia[16]->rp_nao_processado    =
            $this->aLinhasConsistencia[1]->rp_nao_processado - $this->aLinhasConsistencia[11]->rp_nao_processado;
        $this->processarFormasDasLinhas(array(20));
        $this->calculaReceitaCorrenteLiquida();
    }

    /**
     * @return int
     */
    protected function getLinhasParaSoma()
    {
        $somaLinha = 0;
        $modeloImpressao = $this->getModeloImpressao();
        if ($modeloImpressao == 'in13') {
            $somaLinha = 2;
        }
        if ($modeloImpressao == 'porto_velho') {
            $somaLinha = 1;
        }
        return $somaLinha;
    }

    protected function organizarReceitaCorrenteLiquida()
    {
        $somaLinha = $this->getLinhasParaSoma();

        $novaOrdemLinhaComRCL = [];
        $novaOrdem = 1;
        $ultimaPosicao = count($this->aLinhasConsistencia);
        $modeloImpressao = $this->getModeloImpressao();
        if ($modeloImpressao == 'mdf') {
            $ultimaPosicao = 27;
        }
        foreach ($this->aLinhasConsistencia as $indice => $stdLinha) {
            if ($indice == $ultimaPosicao) {
                continue;
            }

            if ($indice == (19+$somaLinha)) {
                $this->aLinhasConsistencia[$ultimaPosicao]->ordem = $novaOrdem;
                $novaOrdemLinhaComRCL[$novaOrdem] = $this->aLinhasConsistencia[$ultimaPosicao];
                $novaOrdem++;
            }
            $stdLinha->ordem = $novaOrdem;
            $novaOrdemLinhaComRCL[$novaOrdem] = $stdLinha;
            $novaOrdem++;
        }
        $this->aLinhasConsistencia = $novaOrdemLinhaComRCL;
    }

    /**
     * @return float|int
     */
    protected function getLimiteMaximo()
    {
        $oTipoInstituicao = $this->getTipoInstituicao();
        $iLimiteMaximo = 0;

        if ($oTipoInstituicao->lTemPrefeitura || $oTipoInstituicao->lTipoRPPS) {
            $iLimiteMaximo = 54;
        }

        if ($oTipoInstituicao->lTemCamara) {
            $iLimiteMaximo = 6;

            $rppsCamara = ($oTipoInstituicao->lTipoRPPS && $oTipoInstituicao->lTemCamara);
            if (($oTipoInstituicao->lTemPrefeitura && $oTipoInstituicao->lTemCamara) || $rppsCamara) {
                $iLimiteMaximo = 60;
            }
        }

        if ($oTipoInstituicao->lTemTribunalContas) {
            $iLimiteMaximo = 1.04;
        }

        if ($oTipoInstituicao->lTemMinisterio) {
            $iLimiteMaximo = 2;
        }

        if ($oTipoInstituicao->lTemTribunalJustica) {
            $iLimiteMaximo = 6;
        }
        return $iLimiteMaximo;
    }
    /**
     * Calcula os valores do quadro Apuração do Cumprimento do Limite Legal
     * @throws Exception
     */
    public function calculaReceitaCorrenteLiquida()
    {
        $this->organizarReceitaCorrenteLiquida();


        $iLimiteMaximo = $this->getLimiteMaximo();
        $rcl = ReceitaCorrenteFactory::getInstance($this->iAno, $this->getPeriodo()->getCodigo());
        $rcl->setInstituicoes($this->getInstituicoes());
        $stdDadosRCL = $rcl->getDadosSimplificado();


        $modelo = $this->getModeloImpressao();
        if ($modelo == 'in13') {
            $this->calculaRCLIn13($iLimiteMaximo, $stdDadosRCL);
            return;
        }

        $this->aLinhasConsistencia[17]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[18]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $nValorRCL = $this->aLinhasConsistencia[17]->valor;

        // 11  RECEITA CORRENTE LÍQUIDA - RCL (IV)
        $this->aLinhasConsistencia[17]->percentual = ' - ';

        // 12  (-) Transferências obrigatórias da União relativas às emendas individuais (V) (§ 13, art. 166 da CF)
        $this->aLinhasConsistencia[18]->percentual = 0;
        if ($nValorRCL) {
            $this->aLinhasConsistencia[18]->percentual = ($this->aLinhasConsistencia[18]->valor / $nValorRCL) * 100;
        }
        $this->aLinhasConsistencia[19]->percentual = 0;
        if ($nValorRCL) {
            $this->aLinhasConsistencia[19]->percentual = ($this->aLinhasConsistencia[19]->valor / $nValorRCL) * 100;
        }

        // 13  = RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)
        $valor = $this->aLinhasConsistencia[17]->valor - $this->aLinhasConsistencia[18]->valor;
        $this->aLinhasConsistencia[20]->valor = $valor;
        $this->aLinhasConsistencia[20]->percentual = ' - ';

        // 14  DESPESA TOTAL COM PESSOAL - DTP (VII) = (III a + III b)
        if ($this->aLinhasConsistencia[17]->valor) {
            $percentual = ($this->aLinhasConsistencia[21]->valor / $this->aLinhasConsistencia[17]->valor) * 100;
            $this->aLinhasConsistencia[21]->percentual = $percentual;
        } else {
            $this->aLinhasConsistencia[21]->percentual = 0;
        }

        /**
         * Valores das linhas 15,16 e 17 devem ser calculados em cima
         * da linha 13 = RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)
         */
        $nValorRCLCalcular = $this->aLinhasConsistencia[20]->valor;

        /**
         * Calcula os percentual para as colunas 16 e 17
         */
        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        // 15  LIMITE MÁXIMO (VIII) (incisos I, II e III, art. 20 da LRF)
        $this->aLinhasConsistencia[22]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[22]->percentual = $iLimiteMaximo;
        // 16  LIMITE PRUDENCIAL (IX) = (0,95 x VIII) (parágrafo único do art. 22 da LRF)
        $this->aLinhasConsistencia[23]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[23]->percentual = $nLimitePrudencial;
        // 17  LIMITE DE ALERTA (X) = (0,90 x VIII) (inciso II do §1º do art. 59 da LRF)
        $this->aLinhasConsistencia[24]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[24]->percentual = $nLimiteMaximoAlerta;
    }

    protected function calculaRCLIn13($iLimiteMaximo, $stdDadosRCL)
    {

        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[21]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $valorRCL = $this->aLinhasConsistencia[19]->valor;

        $this->aLinhasConsistencia[19]->percentual = ' - ';
        $this->aLinhasConsistencia[20]->percentual = ($this->aLinhasConsistencia[20]->valor / $valorRCL) * 100;
        $this->aLinhasConsistencia[21]->percentual = ($this->aLinhasConsistencia[21]->valor / $valorRCL) * 100;

        $this->aLinhasConsistencia[22]->valor =
            ($this->aLinhasConsistencia[19]->valor -
            $this->aLinhasConsistencia[20]->valor -
            $this->aLinhasConsistencia[21]->valor);
        $this->aLinhasConsistencia[22]->percentual = ' - ';


        // DESPESA TOTAL COM PESSOAL - DTP (VII) = (III a + III b)
        $this->aLinhasConsistencia[23]->percentual = 0;
        $this->aLinhasConsistencia[23]->valor =
            ($this->aLinhasConsistencia[18]->liquidado_ultimo_ano + $this->aLinhasConsistencia[18]->rp_nao_processado);
        if ($this->aLinhasConsistencia[23]->valor) {
            $percentual = ($this->aLinhasConsistencia[23]->valor / $valorRCL) * 100;
            $this->aLinhasConsistencia[23]->percentual = $percentual;
        }

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);
        $nValorRCLCalcular = $this->aLinhasConsistencia[22]->valor;
        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        // LIMITE MÁXIMO (VIII) (incisos I, II e III, art. 20 da LRF)
        $this->aLinhasConsistencia[24]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[24]->percentual = $iLimiteMaximo;
        // LIMITE PRUDENCIAL (IX) = (0,95 x VIII) (parágrafo único do art. 22 da LRF)
        $this->aLinhasConsistencia[25]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[25]->percentual = $nLimitePrudencial;
        //  LIMITE DE ALERTA (X) = (0,90 x VIII) (inciso II do §1º do art. 59 da LRF)
        $this->aLinhasConsistencia[26]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[26]->percentual = $nLimiteMaximoAlerta;
    }

    /**
     * Calcula a RCL para as linhas ordenadas para porto velho
     */
    protected function calculaRCLPortoVelho($iLimiteMaximo, $stdDadosRCL)
    {
        $this->aLinhasConsistencia[18]->valor = $stdDadosRCL->valor_rcl_mdf;
        $this->aLinhasConsistencia[19]->valor = $stdDadosRCL->valor_rcl_transferencia_individual;
        $this->aLinhasConsistencia[20]->valor = $stdDadosRCL->valor_rcl_transferencia_bancada;

        $valorRCL = $this->aLinhasConsistencia[18]->valor;

        $this->aLinhasConsistencia[18]->percentual = ' - ';
        $this->aLinhasConsistencia[19]->percentual = ($this->aLinhasConsistencia[19]->valor / $valorRCL) * 100;
        $this->aLinhasConsistencia[20]->percentual = ($this->aLinhasConsistencia[20]->valor / $valorRCL) * 100;

        $this->aLinhasConsistencia[21]->valor =
            ($this->aLinhasConsistencia[18]->valor -
                $this->aLinhasConsistencia[19]->valor -
                $this->aLinhasConsistencia[20]->valor);
        $this->aLinhasConsistencia[21]->percentual = ' - ';


        // DESPESA TOTAL COM PESSOAL - DTP (VII) = (III a + III b)
        $this->aLinhasConsistencia[22]->percentual = 0;
        $this->aLinhasConsistencia[22]->valor =
            ($this->aLinhasConsistencia[17]->liquidado_ultimo_ano + $this->aLinhasConsistencia[17]->rp_nao_processado);
        if ($this->aLinhasConsistencia[22]->valor) {
            $percentual = ($this->aLinhasConsistencia[22]->valor / $valorRCL) * 100;
            $this->aLinhasConsistencia[22]->percentual = $percentual;
        }

        $nLimitePrudencial = round(($iLimiteMaximo * 0.95), 2);
        $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);
        $nValorRCLCalcular = $this->aLinhasConsistencia[21]->valor;
        $nValorLimiteMaximo = ($nValorRCLCalcular * $iLimiteMaximo) / 100;
        $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial) / 100;
        $nValorLimiteAlerta = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

        // LIMITE MÁXIMO (VIII) (incisos I, II e III, art. 20 da LRF)
        $this->aLinhasConsistencia[23]->valor = $nValorLimiteMaximo;
        $this->aLinhasConsistencia[23]->percentual = $iLimiteMaximo;
        // LIMITE PRUDENCIAL (IX) = (0,95 x VIII) (parágrafo único do art. 22 da LRF)
        $this->aLinhasConsistencia[24]->valor = $nValorLimitePrudencial;
        $this->aLinhasConsistencia[24]->percentual = $nLimitePrudencial;
        //  LIMITE DE ALERTA (X) = (0,90 x VIII) (inciso II do §1º do art. 59 da LRF)
        $this->aLinhasConsistencia[25]->valor = $nValorLimiteAlerta;
        $this->aLinhasConsistencia[25]->percentual = $nLimiteMaximoAlerta;
    }

    /**
     * Retorna um array com as linhas processadas para impressão
     * @return Linha[]
     * @throws Exception
     */
    public function getDadosProcessados()
    {
        return $this->getDadosDetalhamentoMensal();
    }


    /**
     * Imprime cabeçalho da:  DESPESA COM PESSOAL
     * @param  PDFDocument $oPdf
     */
    public function cabecalhoQuadroUmOficial(PDFDocument $oPdf)
    {
        $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];

        $oPdf->SetFont("Arial", "", 6);
        $oPdf->Cell(180, 4, 'RGF - ANEXO 1 (LRF, art. 55, inciso I, alínea "a")');
        $oPdf->Cell(10, 4, 'R$ 1,00', 0, 1);

        $iWDescricao = $aConfiguracoes['iWDescricao'];
        $iWTotais    = $aConfiguracoes['iWTotais'];
        $iTotais     = $iWTotais * 2;

        $oPdf->SetFont("Arial", "B", 7);
        $oPdf->Cell($iWDescricao, 4, '', 'TR', 0, '', 1);
        $oPdf->Cell($iTotais, 4, 'DESPESAS EXECUTADAS', 'TL', 1, 'C', 1);

        $oPdf->SetFont("Arial", "B", 5);
        $oPdf->Cell($iWDescricao, 4, '', 'R', 0, '', 1);
        $oPdf->Cell($iTotais, 4, '(Últimos 12 Meses)', 'LB', 1, 'C', 1);

        $oPdf->SetFont("Arial", "B", 7);
        $oPdf->Cell($iWDescricao, 4, 'DESPESA COM PESSOAL', 'R', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, 'LIQUIDADAS', 'TLR', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, 'INSCRITAS EM', 'TL', 1, 'C', 1);

        $oPdf->Cell($iWDescricao, 4, '', 'R', 0, '', 1);
        $oPdf->Cell($iWTotais, 4, '', 'LR', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, 'RESTOS A PAGAR', 'L', 1, 'C', 1);

        $oPdf->Cell($iWDescricao, 4, '', 'R', 0, '', 1);
        $oPdf->Cell($iWTotais, 4, '', 'LR', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, 'NÃO PROCESSADOS¹', 'L', 1, 'C', 1);

        $oPdf->Cell($iWDescricao, 4, '', 'BR', 0, '', 1);
        $oPdf->Cell($iWTotais, 4, '(a)', 'BLR', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, '(b)', 'BL', 1, 'C', 1);

        $oPdf->SetFont("Arial", "", 7);
    }

    /**
     * Imprime cabeçalho da: APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL
     * @param  PDFDocument $oPdf
     */
    public function cabecalhoQuadroDois(PDFDocument $oPdf)
    {
        $oPdf->ln();
        $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];
        $iWDescricao    = $aConfiguracoes['iWDescricao'];
        $iWTotais       = $aConfiguracoes['iWTotais'];

        if ($this->iModelo == self::MODELO_DETALHAMENTO_MENSAL) {
            $oPdf->setBold(true);
            $oPdf->Cell($oPdf->getAvailWidth(), 4, 'Continua 1/2', '0', '1', 'R');
            $oPdf->setBold(false);
            if ($this->getModeloImpressao() == 'mdf') {
                $oPdf->addPage();
            }
            $iWDescricao = 206;
            $iWTotais    = 38.5;
        }

        $oPdf->setBold(true);
        $oPdf->Cell($oPdf->getAvailWidth(), 4, 'Continuação', '0', '1', 'R');
        $oPdf->setBold(false);
        $oPdf->SetFont("Arial", "B", 7);
        $oPdf->Cell($iWDescricao, 4, 'APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL', 'TBR', 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, 'VALOR', 1, 0, 'C', 1);
        $oPdf->Cell($iWTotais, 4, '% SOBRE RCL', 'TBL', 1, 'C', 1);
        $oPdf->SetFont("Arial", "", 7);
    }

    /**
     * Adiciona uma linha no modelo oficial
     * @param  string  $sDescricao
     * @param  float  $mValor1
     * @param  float  $mValor2
     * @param  array  $aBordas
     * @param  integer $iFill
     */
    protected function adicionaLinhaModeloOficial($sDescricao, $mValor1, $mValor2, $aBordas, $iFill = 0)
    {
        $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];
        $iWDescricao    = $aConfiguracoes['iWDescricao'];
        $iWTotais       = $aConfiguracoes['iWTotais'];

        if ($this->iModelo == self::MODELO_DETALHAMENTO_MENSAL) {
            $iWDescricao = 206;
            $iWTotais    = 38.5;
        }

        $mValor1 = $this->formataValor($mValor1);
        if ($mValor2 !== ' - ') {
            $mValor2 = $this->formataValor($mValor2);
        }

        $oLinha = new Linha();
        $oLinha->addColuna($iWDescricao, $sDescricao, $aBordas[0], 0, 'L', $iFill);
        $oLinha->addColuna($iWTotais, $mValor1, $aBordas[1], 0, 'R', $iFill);
        $oLinha->addColuna($iWTotais, $mValor2, $aBordas[2], 1, 'R', $iFill);
        $this->aLinhasProcessadas[] = $oLinha;
    }

    /**
     * Imprime o cabeçalho do quadro 1 no Modelo Detalhamento Mensal
     * @param PDFDocument $oPdf
     * @throws ParameterException
     */
    public function cabecalhoQuadroUmDetalhado(PDFDocument $oPdf)
    {
        $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_DETALHAMENTO_MENSAL];
        $oPdf->SetFont("Arial", "", 6);
        $oPdf->Cell(271, 4, 'RGF - ANEXO 1 (LRF, art. 55, inciso I, alínea "a")');
        $oPdf->Cell(12, 4, 'R$ 1,00', 0, 1);

        $aMeses = $this->getMesesAbrangente();

        $iLinha           = $aConfiguracoes['iLinha'];
        $iWDescricao      = $aConfiguracoes['iWDescricao'];
        $iWTotais         = $aConfiguracoes['iWTotais'];
        $iWLinhaCabecalho = $iLinha - $iWDescricao;

        $sTotalUltimosMeses = "TOTAL (ÚLTIMOS 12 MESES)\n\n(a)";
        $sTotalRestosPagar  = "INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS\n\n(b)";
        $oPdf->SetFont("Arial", "", 6);
        $iLinhas            = $oPdf->getMultiCellHeight($aConfiguracoes['iWTotais'], 4, $sTotalRestosPagar);
        $iAlturaCelula      = ($iLinhas)+4;
        $iAlturaCelulaMes   = ($iLinhas)-4;

        $iEixoX = $iWDescricao + 7; // 7 = left margim

        $oPdf->SetFont("Arial", "B", 7);
        $oPdf->Cell($iWDescricao, $iAlturaCelula, "DESPESA COM PESSOAL", "TBR", 0, 'C');
        $oPdf->Cell($iWLinhaCabecalho, 4, "DESPESAS EXECUTADAS (Últimos 12 meses)", "TBL", 1, 'C');
        $oPdf->setX($iEixoX);
        $iEixoY = $oPdf->getY();
        $oPdf->Cell(($iWLinhaCabecalho - $aConfiguracoes['iWTotais']), 4, "LIQUIDADAS", "0", 1, 'C');
        $oPdf->SetFont("Arial", "", 6);

        $oPdf->setX($iEixoX);
        foreach ($aMeses as $sMesAno) {
            $oPdf->Cell($aConfiguracoes['iWMes'], $iAlturaCelulaMes, $sMesAno, 1, 0, 'C');
            $iEixoX += $aConfiguracoes['iWMes'];
        }
        $oPdf->MultiCell($aConfiguracoes['iWTotais'], 4, $sTotalUltimosMeses, 1, 'C');

        $oPdf->setXY($iEixoX + $aConfiguracoes['iWTotais'], $iEixoY);
        $oPdf->MultiCell($aConfiguracoes['iWTotais'], 4, $sTotalRestosPagar, 'TBL', 'C');

        $oPdf->SetFont("Arial", "", 5);
    }

    /**
     * Monta as linhas que devem ser impressas no relatório
     * @return Linha[]
     * @throws Exception
     */
    protected function getDadosDetalhamentoMensal()
    {

        $this->processarDetalhamentoMensal();

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadroUmDetalhado");
        $this->aLinhasProcessadas[] = $oLinha;

        foreach ($this->aLinhasConsistencia as $oLinhaRelatorio) {
            if ($oLinhaRelatorio->ordem <= 16) {
                $this->adicionaLinhaModeloDetalhado($oLinhaRelatorio);
            }

            if ($oLinhaRelatorio->ordem == 17) {
                $oLinha = new Linha();
                $oLinha->informaMetodo("cabecalhoQuadroDois");
                $this->aLinhasProcessadas[] = $oLinha;
            }

            if ($oLinhaRelatorio->ordem >= 17) {
                $iFill   = 0;
                if ($oLinhaRelatorio->ordem == 20) {
                    $iFill = 1;
                }
                $this->adicionaLinhaModeloOficial(
                    $oLinhaRelatorio->descricao,
                    $oLinhaRelatorio->valor,
                    $oLinhaRelatorio->percentual,
                    array('TBR', 1, 'TBL'),
                    $iFill
                );
            }
        }

        $oLinha = new Linha();
        $oLinha->informaMetodo("notaExplicativaPdf");
        $this->aLinhasProcessadas[] = $oLinha;

        return $this->aLinhasProcessadas;
    }

    /**
     * @param $oLinhaRelatorio
     */
    protected function adicionaLinhaModeloDetalhado($oLinhaRelatorio)
    {

        $sNivel     = str_repeat(' ', $oLinhaRelatorio->nivel * 2);
        $sDescricao = "{$sNivel} {$oLinhaRelatorio->descricao}";

        $aBordas = array('R', 'LR', 'L');
        $lBold   = false;

        if ($oLinhaRelatorio->ordem == $this->linhaFinalQuadroDespesa) {
            $aBordas = array('TBR', '1', 'TBL');
        }

        if (in_array($oLinhaRelatorio->ordem, $this->totalizadoresQuadroDespesa)) {
            $lBold = true;
        }

        $aConfiguracoes = $this->aTamanhoCelulas[self::MODELO_DETALHAMENTO_MENSAL];
        $iWDescricao    = $aConfiguracoes['iWDescricao'];
        $iWMes          = $aConfiguracoes['iWMes'];
        $iWTotais       = $aConfiguracoes['iWTotais'];

        $nLiquidado       = $this->formataValor($oLinhaRelatorio->liquidado_ultimo_ano);
        $nRPNaoProcessado = $this->formataValor($oLinhaRelatorio->rp_nao_processado);

        $oLinha = new Linha();
        $oLinha->multicell(true)->bold($lBold)->alturaLinha(4);
        $oLinha->addColuna($iWDescricao, $sDescricao, $aBordas[0], 0, 'L', 0, 4);

        $aCompetencia = $this->aValoresDespesaPorLinhaMes[$oLinhaRelatorio->ordem];
        foreach ($aCompetencia as $nValor) {
            $oLinha->addColuna($iWMes, db_formatar($nValor, 'f'), $aBordas[1], 0, 'R', 0, 4);
        }
        $oLinha->addColuna($iWTotais, $nLiquidado, $aBordas[1], 0, 'R', 0, 4);
        $oLinha->addColuna($iWTotais, $nRPNaoProcessado, $aBordas[2], 1, 'R', 0, 4);
        $this->aLinhasProcessadas[] = $oLinha;
    }

    /**
     * Finaliza terceito quadro e Imprime a nota explicativa
     * @paran \PDFDocument $oPdf
     */
    public function notaExplicativaPdf(PDFDocument $oPdf)
    {
        $oPdf->line($oPdf->getX(), $oPdf->getY(), 200, $oPdf->getY());
        $oPdf->ln(1);
        $this->notaExplicativa($oPdf, array($oPdf, 'addPage'), 20);

        $oPdf->ln($oPdf->getAvailHeight() - 20);
        $oDaoAssinatura = new cl_assinatura();
        assinaturas($oPdf, $oDaoAssinatura, 'GF');
    }

    /**
     * Retorna os tipos de instituiçoes cadastrados no sistema
     * @return stdClass
     */
    public function getTipoInstituicao()
    {
        $oTiposInstituicao                      = new stdClass();
        $oTiposInstituicao->lTemPrefeitura      = false;
        $oTiposInstituicao->lTemCamara          = false;
        $oTiposInstituicao->lTipoRPPS           = false;
        $oTiposInstituicao->lTemMinisterio      = false;
        $oTiposInstituicao->lTemTribunalJustica = false;
        $oTiposInstituicao->lTemTribunalContas  = false;
        $oTiposInstituicao->iCodigoCliente      = null;

        foreach ($this->aInstituicoes as $oInstituicao) {
            $oTiposInstituicao->iCodigoCliente = $oInstituicao->getCodigoCliente();

            switch ($oInstituicao->getTipo()) {
                case Instituicao::TIPO_PREFEITURA:
                    $oTiposInstituicao->lTemPrefeitura = true;
                    break;

                case Instituicao::TIPO_CAMARA:
                    $oTiposInstituicao->lTemCamara = true;
                    break;

                case Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA:
                case Instituicao::TIPO_AUTARQUIA_EXCETO_RPPS:
                case Instituicao::TIPO_AUTARQUIA_RPPS:
                    $oTiposInstituicao->lTipoRPPS = true;
                    break;

                case Instituicao::TIPO_MINISTERIO_PUBLICO_ESTADUAL:
                    $oTiposInstituicao->lTemMinisterio = true;
                    break;

                case Instituicao::TIPO_TRIBUNAL_DE_JUSTICA:
                    $oTiposInstituicao->lTemTribunalJustica = true;
                    break;

                case Instituicao::TIPO_TRIBUNAL_DE_CONTAS_ESTADO:
                    $oTiposInstituicao->lTemTribunalContas = true;
                    break;
            }
        }

        return $oTiposInstituicao;
    }


    /**
     * Dados preparados para serem emitidos no Anexo VI - Simplificado
     * @return stdClass
     */
    public function getDadosSimplificado()
    {
        $this->getDadosProcessados();

        $oStdAnexo = (object)array(
            'receita_corrente_liquida' => 0,
            'receita_corrente_liquida_ajustada' => 0,
            'total_despesa_pessoal'          => 0,
            'percentual_despesa_pessoal'     => 0,
            'total_limite_maximo'            => 0,
            'percentual_limite_maximo'       => 0,
            'total_limite_prudencial'        => 0,
            'percentual_limite_prudencial'   => 0,
            'total_limite_alerta'            => 0,
            'percentual_limite_alerta'       => 0,
        );


        $linha = [
            17 => 17,
            19 => 20,
            20 => 21,
            21 => 22,
            22 => 23,
            23 => 24,
        ];

        $modelo = $this->getModeloImpressao();
        if ($modelo == 'in13') {
            $linha = [
                17 => 19,
                19 => 22,
                20 => 23,
                21 => 24,
                22 => 25,
                23 => 26,
            ];
        }

        if ($modelo == 'porto_velho') {
            $linha = [
                17 => 18,
                19 => 21,
                20 => 22,
                21 => 23,
                22 => 24,
                23 => 25,
            ];
        }

        if (!empty($this->aLinhasConsistencia[$linha[17]])) {
            $oStdAnexo->receita_corrente_liquida = $this->aLinhasConsistencia[$linha[17]]->valor;
        }

        if (!empty($this->aLinhasConsistencia[$linha[19]])) {
            $oStdAnexo->receita_corrente_liquida_ajustada = $this->aLinhasConsistencia[$linha[19]]->valor;
        }
        if (!empty($this->aLinhasConsistencia[$linha[20]])) {
            $oStdAnexo->total_despesa_pessoal      = round($this->aLinhasConsistencia[$linha[20]]->valor, 2);
            $oStdAnexo->percentual_despesa_pessoal = round($this->aLinhasConsistencia[$linha[20]]->percentual, 2);
        }

        if (!empty($this->aLinhasConsistencia[$linha[21]])) {
            $oStdAnexo->total_limite_maximo      = round($this->aLinhasConsistencia[$linha[21]]->valor, 2);
            $oStdAnexo->percentual_limite_maximo = round($this->aLinhasConsistencia[$linha[21]]->percentual, 2);
        }

        if (!empty($this->aLinhasConsistencia[$linha[22]])) {
            $oStdAnexo->total_limite_prudencial      = round($this->aLinhasConsistencia[$linha[22]]->valor, 2);
            $oStdAnexo->percentual_limite_prudencial = round($this->aLinhasConsistencia[$linha[22]]->percentual, 2);
        }

        if (!empty($this->aLinhasConsistencia[$linha[23]])) {
            $oStdAnexo->total_limite_alerta       = round($this->aLinhasConsistencia[$linha[23]]->valor, 2);
            $oStdAnexo->percentual_limite_alerta  = round($this->aLinhasConsistencia[$linha[23]]->percentual, 2);
        }

        return $oStdAnexo;
    }

    /*
     * valida se vai retornar -0,00 e formata para 0,00
     * @return \string
     */
    protected function formataValor($sValor)
    {
        $sValor = round($sValor, 2);
        $sValor = db_formatar($sValor, 'f');
        return $sValor;
    }
}
