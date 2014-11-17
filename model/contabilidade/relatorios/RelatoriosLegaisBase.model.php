<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 * classe para controle dos valores dos anexos legais da RGF/LRF
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 *
 */
class RelatoriosLegaisBase {


  /**
   * Linhas que serão processadas os balancetes de receita
   * @var array
   */
  protected $aLinhasProcessarReceita = array();

  /**
   * Linhas que serão processadas os balancetes de despesa
   * @var array
   */
  protected $aLinhasProcessarDespesa = array();

  /**
   * Linhas que serão processadas os balancetes de verificação
   * @var array
   */
  protected $aLinhasProcessarVerificacao = array();

  /**
   * Linhas que serão processadas com as movimentações dos restos a pagar
   * @var array
   */
  protected $aLinhasProcessarRestosPagar = array();

  /**
   * Linhas para processar na consistência
   * @var array
   */
  protected $aLinhasConsistencia = array();

  /**
   * instacia da classe RelatorioContabil
   *
   * @var relatorioContabil
   */
  protected $oRelatorioLegal;

  /**
   * Exericio do relatorio
   *
   * @var integer
   */
  protected $iAnoUsu;

  /**
   * Codigo do relatorio
   *
   * @var integer
   */
  protected $iCodigoRelatorio;

  /**
   * Linhas do Relatório
   *
   * @var integer
   *
   */
  protected $aDados = array();

  /**
   * lista de Instituições
   *
   * @var string
   */
  protected $sListaInstit;
  /**
   * Codigo do periodo de emissao
   *
   * @var integer
   *
   */
  protected $iCodigoPeriodo;

  /**
   * Data inicial do relatório
   *
   * @var DBDate
   */
  protected $oDataInicial;

  /**
   * Data final do relatório
   *
   * @var DBDate
   */
  protected $oDataFinal;

  /**
   * Campos para cálculo do Balancete de Receita
   * @var array
   */
  static $aCamposReceita = array('saldo_inicial', 'saldo_prevadic_acum', 'saldo_inicial_prevadic', 'saldo_anterior',
                                 'saldo_arrecadado', 'saldo_a_arrecadar', 'saldo_arrecadado_acumulado',
                                 'saldo_prev_anterior');

  /**
   * Campos para cálculo do Balancete de Despesa
   * @var array
   */
  static $aCamposDespesa = array('dot_ini', 'saldo_anterior', 'empenhado', 'anulado', 'liquidado', 'pago',
                                 'suplementado', 'reduzido', 'atual', 'reservado', 'atual_menos_reservado',
                                 'atual_a_pagar_liquidado', 'empenhado_acumulado', 'anulado_acumulado',
                                 'atual_a_pagar','liquidado_acumulado', 'pago_acumulado', 'suplementado_acumulado',
                                 'reduzido_acumulado', 'proj', 'ativ', 'oper', 'ordinario', 'vinculado', 'suplemen',
                                 'suplemen_acumulado', 'especial', 'especial_acumulado');

  static $aCamposRestoPagar = array('e91_vlremp',
                                    'e91_vlranu',
                                    'e91_vlrliq',
                                    'e91_vlrpag',
                                    'vlranu',
                                    'vlrliq',
                                    'vlrpag',
                                    'vlrpagnproc',
                                    'vlranuliq',
                                    'vlranuliqnaoproc');
  /**
   * Campos para consulta do Balancete de Verificação
   * @var array
   */
  static $aCamposVerificacao = array(
    'saldo_anterior',
    'saldo_anterior_debito',
    'saldo_anterior_credito',
    'saldo_final'
  );


  /**
   * Tipo de cálculo do Balancete de Receita
   */
  const TIPO_CALCULO_RECEITA = 1;

  /**
   * Tipo de cálculo do Balancete de Despesa
   */
  const TIPO_CALCULO_DESPESA = 2;

  /**
   * Tipo de cálculo do Balancete de Verificação
   */
  const TIPO_CALCULO_VERIFICACAO = 3;

  /**
   * Calculos de Restos a pagar
   */
  const TIPO_CALCULO_RESTO = 4;

  /**
   *
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    $this->iCodigoRelatorio = $iCodigoRelatorio;
    $this->iAnoUsu          = $iAnoUsu;
    $this->iCodigoPeriodo   = $iCodigoPeriodo;
    $this->oRelatorioLegal  = new relatorioContabil($iCodigoRelatorio, false);

    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPeriodo    = db_utils::fieldsMemory($rsPeriodo, 0);

    if ($oDadosPeriodo->o114_sequencial < 17) {

      $aPeriodo       = data_periodo($this->iAnoUsu, $oDadosPeriodo->o114_sigla);
      $sDataExercicio = $aPeriodo[1];
    } else {

      $iUltimoDiaMes  = cal_days_in_month(CAL_GREGORIAN, $oDadosPeriodo->o114_mesfinal, $this->iAnoUsu);
      $sDataExercicio = "{$this->iAnoUsu}-{$oDadosPeriodo->o114_mesfinal}-{$iUltimoDiaMes}";
    }
    $this->setDataInicial(new DBDate("{$iAnoUsu}-01-01"));
    $this->setDataFinal(new DBDate($sDataExercicio));
  }

  /**
   * retorna os dados do relatorio.
   *
   */
  public function getDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->executarBalancetesNecessarios();
    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);
    return $this->aLinhasConsistencia;
  }

  /**
   * retorna os dados necessários para o relatorio simplidicado
   *
   */
  public function getDadosSimplificado() {

  }

  /**
   * define as instituicoes que serao usadas no relatorio
   *
   * @param integer $sInstituicoes lista das instituicoes, seperadas por virgula
   */
  public function setInstituicoes($sInstituicoes) {
    $this->sListaInstit = $sInstituicoes;
  }

  /**
   * retorna as instituicoes selecionadas para o relatorio
   *
   * @return string
   */
  public function getInstituicoes() {
    return $this->sListaInstit;
  }

  /**
   * Processa as formulas do relatorio
   *
   *
   * @param array $aLinhas linhas dos relatorios
   */
  public function processaTotalizadores ($aLinhas)  {

    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {

        foreach ($oLinha->colunas as $iColuna => $oColuna) {

          if (trim($oColuna->o116_formula) != "") {

            $sFormulaOriginal = ($oColuna->o116_formula);
            $sFormula         = $this->oRelatorioLegal->parseFormula('aLinhas', $sFormulaOriginal, $iColuna, $aLinhas);
            $evaluate         = "\$oLinha->{$oColuna->o115_nomecoluna} = {$sFormula};";
            ob_start();
            eval($evaluate);
            $sRetorno = ob_get_contents();
            ob_clean();
            if (strpos(strtolower($sRetorno), "parse error") > 0) {

              $sMsg =  "Linha {$iLinha}, Coluna {$oColuna->o115_nomecoluna} com erro no cadastro da formula<br>{$oColuna->o116_formula} <Br>{$sRetorno}";
              throw new Exception($sMsg);

            }
          }
        }
      }
    }
  }

  /**
   * Retorna os periodos cadastras para o relatorio
   *
   * @return array();
   */
  public  function getPeriodos() {

    return $this->oRelatorioLegal->getPeriodos();
  }

  /**
   * Monta a nota explicativa
   *
   * @param FPDF $oPdf instancia do PDf
   * @param integer $iPeriodo Codigo do periodo
   * @param integer $iTam Tamanho da celula
   * @return void
   */
  public function getNotaExplicativa($oPdf, $iPeriodo,$iTam = 190) {
    $this->oRelatorioLegal->getNotaExplicativa($oPdf, $iPeriodo,$iTam = 190);
  }

  /**
   * Seta a data inicial do relatório
   *
   * @param DBDate $oDataInicial instância da data inicial do relatório
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * Seta a data final do relatório
   *
   * @param DBDate $oDataFinal instância da data final do relatório
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * Data inicial de emissão do relatório
   * @return DBDate Data inicial da emissão do relatório
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * Data final de emissão do relatório
   * @return DBDate Data final da emissão do relatório
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * Retorna as linhas configuradas para o relatório
   */
  public function getLinhasRelatorio() {

    $aLinhasRetorno   = array();
    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    foreach ($aLinhasRelatorio as $oLinha) {

      $oLinha->setPeriodo($this->iCodigoPeriodo);

      $oParametros                    = $oLinha->getParametros($this->iAnoUsu, $this->getInstituicoes()) ;
      $oColunas                       = $oLinha->getCols($this->iCodigoPeriodo);
      $oLinhaRetorno                  = new stdClass();
      $oLinhaRetorno->ordem           = $oLinha->getOrdem();
      $oLinhaRetorno->totalizar       = $oLinha->isTotalizador();
      $oLinhaRetorno->descricao       = $oLinha->getDescricaoLinha();
      $oLinhaRetorno->colunas         = $oColunas;
      $oLinhaRetorno->contas          = array();
      $oLinhaRetorno->desdobrar       = false;
      $oLinhaRetorno->nivel           = $oLinha->getNivel();
      $oLinhaRetorno->parametros      = $oParametros;
      $oLinhaRetorno->oLinhaRelatorio = $oLinha;
      $oLinhaRetorno->origem          = $oLinha->getOrigemDados();
      if ($oParametros->desdobrarlinha && $oLinha->desdobraLinha()) {
        $oLinhaRetorno->desdobrar = true;
      }

      /**
       * Criamos as colunas
       */
      foreach ($oLinhaRetorno->colunas as $oColuna) {

        $oLinhaRetorno->{$oColuna->o115_nomecoluna} = 0;
      }
      $aLinhasRetorno[$oLinha->getOrdem()] = $oLinhaRetorno;
    }
    return $aLinhasRetorno;
  }

  /**
   * Realiza o Calculo do valor para a linha informada
   *
   * @param resource $Recordset    resource com os dados do balancete do tipo informado
   * @param stdClass $oLinha       stdClass com os dados a ser Analisado
   * @param array    $aColunasCalcular
   * @param integer  $iTipoCalculo tipo do calculo que deve ser realizado
   * @internal param string $sFormulaCalculo Formula de calculo da linha
   * @return float
   */
  protected static function calcularValorDaLinha($Recordset, stdClass $oLinha, array $aColunasCalcular, $iTipoCalculo) {

    $aListaColunas        = array();
    $sNomeColunaDescricao = '';
    switch ($iTipoCalculo) {

      case RelatoriosLegaisBase::TIPO_CALCULO_RECEITA:

        $sNomeColunaDescricao = "o57_descr";
        $aListaColunas        = RelatoriosLegaisBase::$aCamposReceita;
        $sColunaEstrutural    = 'estrutural';
        break;

      case RelatoriosLegaisBase::TIPO_CALCULO_DESPESA:

        $sNomeColunaDescricao = "o56_descr";
        $aListaColunas        = RelatoriosLegaisBase::$aCamposDespesa;
        $sColunaEstrutural    = 'o58_elemento';
        break;

      case RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO:

        $sNomeColunaDescricao = "c60_descr";
        $aListaColunas        = RelatoriosLegaisBase::$aCamposVerificacao;
        $sColunaEstrutural    = 'estrutural';
        break;

      case RelatoriosLegaisBase::TIPO_CALCULO_RESTO:

        $sNomeColunaDescricao = "o56_descr";
        $aListaColunas        = RelatoriosLegaisBase::$aCamposRestoPagar;
        $sColunaEstrutural    = 'o56_elemento';
        break;
    }

    $nValorLinha  = 0;
    $iTotalLinhas = pg_num_rows($Recordset);
    for ($iLinha = 0; $iLinha < $iTotalLinhas; $iLinha++) {

      $oDados         = new stdClass();
      $oDadosResource = db_utils::fieldsMemory($Recordset, $iLinha);
      foreach ($oLinha->parametros->contas as $oConta) {

        $oVerificacao = $oLinha->oLinhaRelatorio->match($oConta,
                                                        $oLinha->parametros->orcamento,
                                                        $oDadosResource,
                                                        $iTipoCalculo
                                                      );

        $oValoresParaCalculo = clone $oDadosResource;
        if ($oVerificacao->match) {

          if ($oVerificacao->exclusao) {

            foreach ($aListaColunas as $sColuna) {
              $oValoresParaCalculo->{$sColuna} *= -1;
            }
          }

          if ($oLinha->desdobrar) {

            if (!isset($oLinha->contas[$oConta->estrutural])) {

              $oContaDesdobrada                    = new stdClass();
              $oContaDesdobrada->descricao         = $oValoresParaCalculo->{$sNomeColunaDescricao};
              $oLinha->contas[$oConta->estrutural] = $oContaDesdobrada;
            }
          }
          $oLinhaCalculo    = clone $oLinha;
          $oDados->resource = $oValoresParaCalculo;
          foreach ($aColunasCalcular as $oColuna) {

            $oDados->coluna     = $oColuna;
            $nValorConta        = RelatoriosLegaisBase::resolverFormula($oColuna->formula, $oDados, $oLinhaCalculo, $oColuna);
            if ($oLinha->desdobrar) {

              $oContaDesdobrada                    = $oLinha->contas[$oConta->estrutural];
              if (!isset($oContaDesdobrada->{$oColuna->nome})) {
                $oContaDesdobrada->{$oColuna->nome} = 0;
              }
              $oContaDesdobrada->{$oColuna->nome} += $nValorConta;
            }

            if (isset($oColuna->agrupar)) {
              RelatoriosLegaisBase::agrupar($oLinha, $oColuna, $oValoresParaCalculo, $nValorConta);
            }
            $oLinha->{$oColuna->nome} += $nValorConta;
          }
        }
      }
    }
    return $oLinha;
  }


  /**
   * Realiza do agrupamentop dos valores atravez de um tipo
   * @param $oLinha
   * @param $oColuna
   * @param $oResource
   * @param $nValor
   */
  protected static function agrupar($oLinha, $oColuna, $oResource, $nValor) {

    if (!isset($oLinha->{$oColuna->agrupar->nome})) {
      $oLinha->{$oColuna->agrupar->nome} = array();
    }

    if (!isset($oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}])) {

      $oAgrupar                   = new stdClass();
      $oAgrupar->nome             = $oResource->{$oColuna->agrupar->descricao};
      $oAgrupar->{$oColuna->nome} = 0;

      $oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}] = $oAgrupar;
    }

    $oAgrupar = $oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}];

    $oAgrupar->{$oColuna->nome} += $nValor;
  }

  /**
   * REalizar o parse da formula
   * @param string $sFormula Formula matematica
   * @param stdClass $oDados objeto com os valores
   * @param $oLinha
   * @param $oColuna
   * @return int
   */
  protected static function resolverFormula($sFormula, $oDados, $oLinha, $oColuna) {

    $nValor = 0;
    if (trim($sFormula) != '' ) {

      $sFormula = str_replace('#', '$oDados->resource->', $sFormula);
      eval("\$nValor = {$sFormula};");
    }


    return $nValor;
  }

  /**
   * Realiza o processamento das linhas com valores Digitados Manuais
   */
  protected function processarValoresManuais() {

    foreach ($this->aLinhasConsistencia as $oLinha) {

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null,
                                                                           $this->getInstituicoes(),
                                                                           $this->iAnoUsu
                                                                          );
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }
    }
  }

  /**
   * Retorna a instancia do relatorioContabil
   * @return relatorioContabil
   */
  public function getRelatorioContabil() {
    return $this->oRelatorioLegal;
  }

/**
   * Verifica quais os tipos de calculos devem ser executados para a consistência
   */
  protected function processarTiposDeCalculo() {

    foreach($this->aLinhasConsistencia as $iLinhas => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      switch ($oLinha->origem) {

        case linhaRelatorioContabil::ORIGEM_RECEITA:

          $this->aLinhasProcessarReceita[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_DESPESA:

          $this->aLinhasProcessarDespesa[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_RESTOS_PAGAR:
          $this->aLinhasProcessarRestosPagar[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_VERIFICACAO:

          $this->aLinhasProcessarVerificacao[] = $iLinhas;
          break;
      }
    }
  }


  /**
   * Executa oo calculo dos balancetes necessarios
   */
  protected function executarBalancetesNecessarios() {

    $this->processarTiposDeCalculo();

    if (count($this->aLinhasProcessarReceita) > 0) {
      $this->executarBalanceteDaReceita();
    }

    if (count($this->aLinhasProcessarDespesa) > 0) {
      $this->executarBalanceteDespesa();
    }

    if (count($this->aLinhasProcessarVerificacao) > 0) {
      $this->executarBalanceteVerificacao();
    }

    if (count($this->aLinhasProcessarRestosPagar) > 0 ) {
      $this->executarRestosPagar();
    }
  }


  /**
   * Executa o Balancete da Receita
   */
  protected function executarBalanceteDaReceita() {

    $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita, $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate()
                                          );

    foreach ($this->aLinhasProcessarReceita as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                );
    }
  }

  /**
   * Executa o Balancete de Despesa
   */
  protected function executarBalanceteDespesa() {

    $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate()
                                         );

    foreach ($this->aLinhasProcessarDespesa as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                );

    }

  }

  /**
   * Executa o Balancete de Verificação
   */
  protected function executarBalanceteVerificacao() {

    $sWhereVerificacao      = " c61_instit in({$this->getInstituicoes()})";
    $rsBalanceteVerificacao =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                          $this->getDataInicial()->getDate(),
                                                          $this->getDataFinal()->getDate(),
                                                          false,
                                                          $sWhereVerificacao,
                                                          '',
                                                          'true',
                                                          'false'
                                                         );

    foreach ($this->aLinhasProcessarVerificacao as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );
    }
  }

  /**
   * Executa o Resto a Pagar
   */
  protected function executarRestosPagar() {

    $oDaoRestosAPagar = new cl_empresto();
    $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo($this->iAnoUsu,
                                                       $sWhereRestoPagar,
                                                       $this->getDataInicial()->getDate(),
                                                       $this->getDataFinal()->getDate()
                                                      );

    $rsRestosPagar    = db_query($sSqlRestosaPagar);
    foreach ($this->aLinhasProcessarRestosPagar as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
      RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 RelatoriosLegaisBase::TIPO_CALCULO_RESTO
                                                );
    }
  }

  /**
   * Processas as colunas que serão usadas para o calculo
   *
   * @param stdClass $oLinha Instancia da linha
   * @param null     $iColuna
   * @return array retorna um array com as linhas
   */
  protected function processarColunasDaLinha(stdClass $oLinha, $iColuna = null) {

    $aColunas          = $oLinha->oLinhaRelatorio->getCols($this->iCodigoPeriodo);
    $aColunasProcessar = array();
    foreach ($aColunas as $iOrdemColuna => $oColunaRelatorio) {

      if (!is_null($iColuna) && $iColuna !== $iOrdemColuna) {
        continue;
      }
      $oLinha->{$oColunaRelatorio->o115_nomecoluna} = 0;

      $oColuna             = new stdClass();
      $oColuna->nome       = $oColunaRelatorio->o115_nomecoluna;
      $oColuna->formula    = $oColunaRelatorio->o116_formula;
      $oColuna->analisada  = false;
      $aColunasProcessar[] = $oColuna;
    }
    return $aColunasProcessar;
  }

  /**
   * Procurar formulas de colunas
   * @param $sFormula
   * @param $oColuna
   * @param $oLinha
   * @return mixed
   */
  protected function procurarFormulaColuna($sFormula, $oColuna, $oLinha) {

    if ($oColuna->analisada) {
      return $sFormula;
    }

    $aPalavras         = str_word_count($sFormula, 2, '1234567890');
    $sFormulaOriginal = $sFormula;
    foreach ($aPalavras as $iInicio => $sPalavra) {

      $sLetraAnterior = substr($sFormulaOriginal, $iInicio - 1, 1);
      if ($sLetraAnterior == '@') {

        foreach ($oLinha->colunas as $oColunaLinha) {
          if (trim($sPalavra) == trim($oColunaLinha->o115_nomecoluna)) {
            $sFormula = str_replace("@{$sPalavra} ", $oColunaLinha->o116_formula." ", $sFormula);
          }
        }
      }
    }

    $oColuna->formula   = $sFormula;
    $oColuna->analisada = true;
    return $oColuna->formula;
  }

  /**
   * função para buscar os lançamentos de acordo com o documento informado
   * retorna o campo c70_valor somado
   * @param EventoContabil $oEventoContabil
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @throws DBException
   * @return float
   */
  public static function getValorLancamentoPorDocumentoPeriodo(EventoContabil $oEventoContabil, DBDate $oDataInicial, DBDate $oDataFinal)  {

    $iDocumento    = $oEventoContabil->getCodigoDocumento();
    $iInstituicao  = $oEventoContabil->getInstituicao();

    $dtInicial     = $oDataInicial->getDate('Y-m-d');
    $dtFinal       = $oDataFinal->getDate('Y-m-d');
    $oDaoConlancam = new cl_conlancam();
    $nValorTotal   = 0;

    $sWhereValores  = "     c71_coddoc = {$iDocumento}   ";
    $sWhereValores .= " and c02_instit = {$iInstituicao} ";
    $sWhereValores .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";

    $sCampos       = " coalesce(sum(c70_valor), 0) as valor_total ";

    $sSqlValores   = $oDaoConlancam->sql_query_ValorLancamentoPorDocumentoPeriodo(null, $sCampos, null, $sWhereValores);
    $rsValorTotal  = $oDaoConlancam->sql_record($sSqlValores);

    /**
     * Tratamos se nao deu erro na query
     */
    if ($oDaoConlancam->erro_status == "0") {
      throw new DBException("Erro ao buscar valor total: \n" .$oDaoConlancam->erro_msg );
    }

    /**
     * Se achou registro para os filtros reatribuimos o valor total
     */
    if ($oDaoConlancam->numrows > 0) {
      $nValorTotal = db_utils::fieldsMemory($rsValorTotal, 0)->valor_total;
    }

    return $nValorTotal;
  }

}
