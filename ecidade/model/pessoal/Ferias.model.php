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
 * Model para cadastro de ferias (rhferias)
 * @author  Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @author  Rafael Lopes     <rafael.lopes@dbseller.com.br>
 * @author  Anderson Amaral  <anderson@dbseller.com.br>
 * @require db_utils
 * @version $
 */
class Ferias {

  /**
   * Código sequencial das ferias
   * @var integer $iCodigoFerias
   */
  private $iCodigoFerias;

  /**
   * numero da matricula do servido
   * @var integer $iMatricula
   */
  private $iMatricula;

  /**
   * Data do inicio do periodo aquisitivo
   * @var date
   */
  private $dPeriodoAquisitivoInicial;

  /**
   * Data do fim do periodo aquisitivo
   * @var date
   */
  private $dPeriodoAquisitivoFinal;
  
  /**
   * Data do inicio do periodo aquisitivo
   * @var date
   */
  private $dPeriodoEspecificoInicial;

  /**
   * Data do fim do periodo aquisitivo
   * @var date
   */
  private $dPeriodoEspecificoFinal;  

  /**
   * Total de dias que o servidor terá direito
   * @var integer
   */
  private $iDiasDireito;

  /**
   * ano da competencia da folha
   * @var integer
   */
  private $iAnoFolha;
   
  /**
   * mes da competencia da folha
   * @var integer
   */
  private $iMesFolha;

  /**
   * total de faltas durante o perioso aquisitivo
   * @var integer
   */
  private $iFaltasPeriodoAquisitivo;
   
  /**
   * array com os dados dos periodos de ferias
   * @var array
   */
  private $aPeriodoGozo = array();
   
  /**
   * DAO da tabela rhferias
   * @var object
   */
  public $oDaoFerias;
  
  function __construct($iCodigoFerias = null) {
     
    $this->oDaoFerias = db_utils::getDao('rhferias');
     
    $this->iAnoFolha = db_anofolha();
    $this->iMesFolha = db_mesfolha();
     
    if (isset($iCodigoFerias)) {
       
      $sSqlFerias = $this->oDaoFerias->sql_query($iCodigoFerias);
      $rsFerias   = $this->oDaoFerias->sql_record($sSqlFerias);
       
      if ($this->oDaoFerias->numrows > 0) {
         
        $oDadosFerias = db_utils::fieldsMemory($rsFerias, 0, true);
        $this->setCodigoFerias             ($iCodigoFerias)                               ;
        $this->setMatricula                ($oDadosFerias->rh109_regist)                  ;
        $this->setPeriodoAquisitivoInicial ($oDadosFerias->rh109_periodoaquisitivoinicial);
        $this->setPeriodoAquisitivoFinal   ($oDadosFerias->rh109_periodoaquisitivofinal)  ;
        $this->setPeriodoEspecificoInicial ($oDadosFerias->rh109_periodoespecificoinicial);
        $this->setPeriodoEspecificoFinal   ($oDadosFerias->rh109_periodoespecificofinal)  ;        
        $this->setDiasDireito              ($oDadosFerias->rh109_dias)                    ;
        $this->setFaltasPeriodoAquisitivo  ($oDadosFerias->rh109_faltasperiodoaquisitivo) ;
        $this->aPeriodoGozo = $this->getPeriodoGozo();
      }
       
    }
     
  }
   
   
  /**
   * Retorna o codigo sequencial das ferias
   * @return integer
   */
  public function getCodigoFerias() {
    return $this->iCodigoFerias;
  }

  /**
   * Define o codigo sequencial das ferias
   * @param integer $iCodigoFerias
   */
  public function setCodigoFerias($iCodigoFerias) {
    $this->iCodigoFerias = $iCodigoFerias;
  }

  /**
   * Retorna a matricula do servidor
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define a matricula do servidor
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Retorna data inicial do periodo aquisitivo
   * @return date
   */
  public function getPeriodoAquisitivoInicial() {
    return $this->dPeriodoAquisitivoInicial;
  }

  /**
   * Define data inicial do periodo aquisitivo
   * @param date $dPeriodoAquisitivoInicial
   */
  public function setPeriodoAquisitivoInicial($dPeriodoAquisitivoInicial) {
    $this->dPeriodoAquisitivoInicial = $dPeriodoAquisitivoInicial;
  }
  
  /**
   * Retorna data inicial do periodo especifico
   * @return date
   */
  public function getPeriodoEspecificoInicial() {
    return $this->dPeriodoEspecificoInicial;
  }

  /**
   * Define data inicial do periodo especifico
   * @param date $dPeriodoEspecificoInicial
   */
  public function setPeriodoEspecificoInicial($dPeriodoEspecificoInicial) {
    $this->dPeriodoEspecificoInicial = $dPeriodoEspecificoInicial;
  }  

  /**
   * Retorna data do periodo aquisitivo final
   * @return date
   */
  public function getPeriodoAquisitivoFinal() {
    return $this->dPeriodoAquisitivoFinal;
  }

  /**
   * Define data do periodo aquisitivo final
   * @param date $dPeriodoAquisitivoFinal
   */
  public function setPeriodoAquisitivoFinal($dPeriodoAquisitivoFinal) {
    $this->dPeriodoAquisitivoFinal = $dPeriodoAquisitivoFinal;
  }
  
  /**
   * Retorna data do periodo especifico final
   * @return date
   */
  public function getPeriodoEspecificoFinal() {
    return $this->dPeriodoEspecificoFinal;
  }

  /**
   * Define data do periodo especifico final
   * @param date $dPeriodoEspecificoFinal
   */
  public function setPeriodoEspecificoFinal($dPeriodoEspecificoFinal) {
    $this->dPeriodoEspecificoFinal = $dPeriodoEspecificoFinal;
  }  

  /**
   * Retorna total de dias de direito de ferias
   * @return integer
   */
  public function getDiasDireito() {
    return $this->iDiasDireito;
  }

  /**
   * Define total de dias de direito de ferias
   * @param integer $iDiasDireito
   */
  public function setDiasDireito($iDiasDireito) {
    $this->iDiasDireito = $iDiasDireito;
  }

  /**
   * Retorna ano competencia da folha
   * @return integer
   */
  public function getAnoFolha() {
    return $this->iAnoFolha;
  }

  /**
   * Define o ano competencia da folha
   * @param integer $iAnousu
   */
  public function setAnoFolha($iAno) {
     
    if ($iAno == "") {
      $iAno = db_anofolha();
    }
    $this->iAnoFolha = $iAno;

  }

  /**
   * Retorna o mes de competencia da folha
   * @return integer
   */
  public function getMesFolha() {
    return $this->iMesFolha;
  }

  /**
   * Define o mes de competencia da folha
   * @param integer $iMesusu
   */
  public function setMesFolha($iMes) {
    if ($iMes == "") {
      $iMes = db_mesfolha();
    }
    $this->iMesFolha = $iMes;
  }

  /**
   * Retorna total de faltas durante o periodo aquisitivo
   * @return integer
   */
  public function getFaltasPeriodoAquisitivo() {
    return $this->iFaltasPeriodoAquisitivo;
  }

  /**
   * Define o total de faltas durante o periodo aquisitivo
   * @param integer $iFaltasPeriodoAquisitivo
   */
  public function setFaltasPeriodoAquisitivo($iFaltasPeriodoAquisitivo) {
    $this->iFaltasPeriodoAquisitivo = $iFaltasPeriodoAquisitivo;
  }
   
  /**
   * Retorna array de peridos de gozo das ferias
   * @return integer
   */
  public function getPeriodoGozo($iCodigoFerias=null, $iAno=null, $iMes=null) {
     
    if (!empty($iCodigoFerias) || isset($this->iCodigoFerias) && count($this->aPeriodoGozo) == 0) {
       
      $aPeriodos    = array();
       
      if (empty($iCodigoFerias)) {
        $iCodigoFerias = $this->getCodigoFerias();
      }
      $sWhere = " rh110_rhferias = {$iCodigoFerias}";
       
      if (!empty($iAno)) {
        $sWhere .= " and rh110_anopagamento = {$iAno}";
      }
       
      if (!empty($iMes)) {
        $sWhere .= " and rh110_mespagamento = {$iMes}";
      }
       
      $oDaoPeriodos = db_utils::getDao("rhferiasperiodo");
      $sSqlPeriodos = $oDaoPeriodos->sql_query(null, "*", null, $sWhere);
      $rsPeriodos   = $oDaoPeriodos->sql_record($sSqlPeriodos);
      if ($oDaoPeriodos->numrows > 0) {
         
        $aDadosPeriodos = db_utils::getCollectionByRecord($rsPeriodos);
        foreach ($aDadosPeriodos as $oPeriodos) {
           
          $aPeriodos[] = new FeriasPeriodo($oPeriodos->rh110_sequencial);
        }
         
      }

      return $aPeriodos;
       
    }
     
    return $this->aPeriodoGozo;
     
  }
   
  /**
   * Define o array de objetos de peridos de gozo das ferias
   * Cada posição do vetor é um objeto tipo FeriasPeriodo contendo todos os métodos da classe FeriasPeriodo
   * @param array $aPeriodoGozo
   */
  public function addPeriodoGozo(FeriasPeriodo $oPeriodoGozo) {
     
    $this->aPeriodoGozo[] = $oPeriodoGozo;
    return true;
     
  }
   
  /**
   * Salvar férias
   * @throws Exception
   * @return string
   */
  public function salvarFerias() {
     
    $this->oDaoFerias->rh109_regist                    = $this->getMatricula()                ;
    $this->oDaoFerias->rh109_periodoaquisitivoinicial  = $this->getPeriodoAquisitivoInicial() ;
    $this->oDaoFerias->rh109_periodoaquisitivofinal    = $this->getPeriodoAquisitivoFinal()   ;
    $this->oDaoFerias->rh109_dias                      = $this->getDiasDireito()              ;
    $this->oDaoFerias->rh109_faltasperiodoaquisitivo   = $this->getFaltasPeriodoAquisitivo()  ;

    $this->oDaoFerias->incluir(null);

    if ($this->oDaoFerias->erro_status == "0") {
      throw new Exception("ERRO [0] - ".$this->oDaoFerias->erro_msg);
    }

    $this->setCodigoFerias($this->oDaoFerias->rh109_sequencial);

    return true;
  }

  /**
   * Savar periodos de gozo
   * @throws BusinessException
   * @return boolean
   */
  function salvarPeriodos() {

    if (!$this->getCodigoFerias()) {
      throw new BusinessException("Código férias não definido");
    }

    foreach ( $this->aPeriodoGozo as $oPeriodos ) {

      $oPeriodos->setCodigoFerias($this->getCodigoFerias());
      $oPeriodos->salvar();
    }

    return true;
  }

  /**
   * metodo que verifica se o usuario possui rescisão
   * @return bool
   */
  function verificaRescisao() {

    $oDaoPessoal = db_utils::getDao('rhpessoal');
    $sSqlPessoal = $oDaoPessoal->sql_query_rescisao($this->getMatricula(), 'rh05_seqpes');
    $rsPessoal   = $oDaoPessoal->sql_record($sSqlPessoal);

    // Possui rescisão
    if ($oDaoPessoal->numrows > 0 && db_utils::fieldsMemory($rsPessoal, 0)->rh05_seqpes != '') {
      return true;
    }

    return false;
  }
   
  /**
   * Verifica se existe férias para o periodo da folha
   * @return boolean
   */
  function verificaFeriasCompetencia() {

    $sSqlExisteCompetencia = $this->oDaoFerias->sql_query(null, "rh109_sequencial");
     
    $rsExisteCompetencia   = $this->oDaoFerias->sql_record($sSqlExisteCompetencia);
    if ($this->oDaoFerias->numrows > 0) {

      for ($iInd = 0; $iInd < $this->oDaoFerias->numrows; $iInd++) {
        $oDadosRHFerias = db_utils::fieldsMemory($rsExisteCompetencia, 0);
         
        $oRhFeriasCompetencia[] = new Ferias($oDadosRHFerias->rh109_sequencial);

      }
       
      return $oRhFeriasCompetencia;
      	
    }
     
    return false;
  }
   
  function verificaFeriasCompetenciaSelecao($iCodigoSelecao) {
     
    if ($this->getAnoFolha() == "") {
      throw new ParameterException("1. Não informado o ano da competência da folha");
    }
    if ($this->getMesFolha() == "") {
      throw new ParameterException("2. Não informado o mes da competência da folha");
    }
     
    $oDaoSelecao = db_utils::getDao("selecao");
    $sSqlSelecao = $oDaoSelecao->sql_query_file($iCodigoSelecao, db_getsession('DB_instit'), "r44_where");
    $rsSelecao   = $oDaoSelecao->sql_record($sSqlSelecao);
    if ($oDaoSelecao->numrows == 0 ) {
      throw new DBException("Seleção informada não encontrada no banco de dados");
    }
    $oDadosSelecao = db_utils::fieldsMemory($rsSelecao, 0);
     
    $sCampos = " distinct rh109_sequencial, rh109_regist";
    $sSqlDadosFeriasSelecao = $this->oDaoFerias->sql_query_ferias_selecao($this->getAnoFolha(),
                                                                          $this->getMesFolha(),
                                                                          $sCampos,
                                                                          $oDadosSelecao->r44_where);
    $rsDadosFeriasSelecao = $this->oDaoFerias->sql_record($sSqlDadosFeriasSelecao);
    $oDadosFerias = db_utils::getCollectionByRecord($rsDadosFeriasSelecao);
     
    return $oDadosFerias;
     
  }
   
  /**
   * Verifica se existe férias para o periodo da folha
   * @return boolean
   */
  function verificaFeriasMatricula($iMatricula = null, $iPeriodoInicial = null, $iPeriodoFinal = null) {
     
    if ($this->getMatricula() == "" && empty($iMatricula)) {
      throw new ParameterException("Não informada a Matricula do Servidor");
    }
    
    if (empty($iMatricula)) {
      $sWhere = " rh109_regist = {$this->getMatricula()}";
    } else {
      $sWhere = " rh109_regist = {$iMatricula}";
    }
    
    if (!empty($iPeriodoInicial)) {
      $sWhere .= " and rh109_periodoaquisitivoinicial >= '{$iPeriodoInicial}'";   
    }
    
    if (!empty($iPeriodoFinal)) {
      $sWhere .= " and rh109_periodoaquisitivofinal <= '{$iPeriodoFinal}'";
    }
    
    $sSqlExisteCompetencia = $this->oDaoFerias->sql_query(null, "rh109_sequencial", "rh109_periodoaquisitivoinicial", $sWhere);
    $rsExisteCompetencia   = $this->oDaoFerias->sql_record($sSqlExisteCompetencia);
    
    $aFerias = array();
     
    if ($this->oDaoFerias->numrows > 0) {
      
      for($i = 0; $i < $this->oDaoFerias->numrows; $i++) {
        
        $aFerias[] = new Ferias(db_utils::fieldsMemory($rsExisteCompetencia, $i)->rh109_sequencial);
        
      }
      
      return $aFerias;
    }
     
    return false;
  }
  
  function verificaSaldoDias($iMatricula=null, $dPeriodoInicial=null, $dPeriodoFinal=null) {
    
    $iSaldoDiasDireito = 30;
    $oDadosFerias = $this->verificaFeriasMatricula($iMatricula, $dPeriodoInicial, $dPeriodoFinal);
    if ($oDadosFerias != false) {
      
      foreach ($oDadosFerias as $iInd => $oDados) {
        
        $iSaldoDiasDireitoFerias = $oDados->calculaDiasDireito($oDados->iCodigoFerias);
        if ( $iSaldoDiasDireitoFerias < $oDados->iDiasDireito ) {
          return $iSaldoDiasDireitoFerias; 
        } 
        
      }
      
    }
    
    return $iSaldoDiasDireito;
    
  }
  
  function verificaDireitoFerias($dPeriodoInicial, $dPeriodoFinal) {
    
    if (empty($this->iMatricula)) {
      throw new ParameterException('Matrícula não declarada para execução.');
    }

    $sWhere  = "rh109_regist 									  = {$this->getMatricula()}	 and ";
    $sWhere .= "rh109_dias 									    = 0												 and ";    
    $sWhere .= " ( ('{$dPeriodoInicial}' > rh109_periodoaquisitivoinicial  and ";
    $sWhere .= "    '{$dPeriodoInicial}' < rh109_periodoaquisitivofinal  )  or ";
    $sWhere .= "   ( '$dPeriodoFinal'    > rh109_periodoaquisitivoinicial  and "; 
    $sWhere .= "     '$dPeriodoFinal'    < rh109_periodoaquisitivofinal  ) )   ";
    $sSqlRhFerias = $this->oDaoFerias->sql_query(null,
                                                 "*",
                                                 null,
                                                 $sWhere);
    $rsRhFerias   = $this->oDaoFerias->sql_record($sSqlRhFerias);
    if ($this->oDaoFerias->numrows > 0) {
      return false;
    } else {
      return true;
    } 
    
  }

  /**
   * Retorna parametros da cfpess
   * @return boolean
   */
  function getParametros() {

    $oDaoCfpess     = db_utils::getDao('cfpess');
    $sSqlParametros = $oDaoCfpess->sql_query_file($this->getAnoFolha(),
    $this->getMesFolha(),
    db_getsession('DB_instit'),
                                                  "r11_propae, r11_propac");
    $rsParametros   = $oDaoCfpess->sql_record($sSqlParametros);

    if ($oDaoCfpess->numrows > 0) {

      $oParametros = db_utils::fieldsMemory($rsParametros, 0);

      return $oParametros;
    }

    throw BusinessException("Parametros não definidos");

  }

  /**
   * Verifica deve descontar os dias de falta do periodo de direito a gozo
   * @return boolean
   */
  function usarFaltas() {

    $oDaoRhpessoalmov = db_utils::getDao('rhpessoalmov');
    $sSqlRegime       = $oDaoRhpessoalmov->sql_query_parametros($this->getMatricula(), $this->getAnoFolha(), $this->getMesFolha());
    $rsRegime         = $oDaoRhpessoalmov->sql_record($sSqlRegime);

    if ($oDaoRhpessoalmov->numrows > 0) {

      $oRegime     = db_utils::fieldsMemory($rsRegime, 0);

      /* Estatutario */
      if ($oRegime->rh30_regime !== '2' && $this->getParametros()->r11_propae == 'f') {
        return false;
      }

      /* Celetista */
      if ($oRegime->rh30_regime == '2'  && $this->getParametros()->r11_propac == 'f') {
        return false;
      }

      return true;
    }

    throw BusinessException("Regime não definido");
  }

  /**
   * metodo para retornar os dias que o servidor gozou no periodo
   * @return integer
   */
  function getDiasGozados($iCodigoFerias=null) {
     
    $aPeriodosGozados = $this->getPeriodoGozo($iCodigoFerias);
    $iDiasGozados     = 0;
    foreach ($aPeriodosGozados as $oPeriodoGozado) {
      $iDiasGozados += $oPeriodoGozado->iDiasGozo;
    }
    return $iDiasGozados;
  }

  /**
   * metodo que retornaa o total de dias abonados nos periodos cadastrados
   * @return integer
   */
  function getDiasAbonados ($iCodigoFerias=null) {

    $aDiasAbonados = $this->getPeriodoGozo($iCodigoFerias);
    $iDiasAbonados = 0;

    foreach($aDiasAbonados as $oPeriodoAbonados) {
      $iDiasAbonados += $oPeriodoAbonados->iDiasAbono;
    }
    return $iDiasAbonados;
  }

  /**
   * Retorna os dias de desconto por faltas
   *
   * Até 5 dias - 30
   * Até 14 dias - 24
   * Até 23 dias - 18
   * Até 32 dias - 12
   * maior que 32 - 0
   *
   * @return integer
   */
  function getDiasDesconto() {

    $iFaltas       = $this->getFaltasPeriodoAquisitivo();
    $iDiasDesconto = 0;

    if ($iFaltas > 5 && $iFaltas <= 14) {
      $iDiasDesconto = 6;
    }

    if ($iFaltas > 14 && $iFaltas <= 23) {
      $iDiasDesconto = 12;
    }

    if ($iFaltas > 23 && $iFaltas <= 32) {
      $iDiasDesconto = 18;
    }

    if ($iFaltas > 32) {
      $iDiasDesconto = 30;
    }

    return $iDiasDesconto;
  }

  /**
   * Retorna o total de diras de direito a gozar
   * @return integer
   */
  function calculaDiasDireito($iCodigoFerias=null) {

    $iDiasDireito  = 30;
    $iDiasGozados  = $this->getDiasGozados($iCodigoFerias);
    $iDiasAbonados = $this->getDiasAbonados($iCodigoFerias);
    $iDiasDesconto = $this->getDiasDesconto($iCodigoFerias);

    $iDiasDireito  = $iDiasDireito - ($iDiasGozados + $iDiasAbonados);

    if ($this->usarFaltas()) {
      $iDiasDireito -= $this->getDiasDesconto();
    }

    return $iDiasDireito;
  }
   
   
  function excluirFerias() {
     
    $sMsgRetorno  = "Usuário, alguns procedimentos não podem ser feitos automaticamente pelo sistema. \n";
    $sMsgRetorno .= "Portanto, proceda da seguinte maneira:\n\n";
    $sMsgRetorno .= "- Reinicialize o ponto de salário para este funcionário.";
    foreach ($this->aPeriodoGozo as $oPeriodo) {
      
      if ( ($oPeriodo->iAnoPagamento >= $this->getAnoFolha() ) && ($oPeriodo->iMesPagamento >= $this->getMesFolha()) ) {
        $oFeriasPeriodo = new FeriasPeriodo($oPeriodo->iCodigoPeriodo);
        $oFeriasPeriodo->excluir();
      }

    }
     
    if ( count($this->getPeriodoGozo($this->getCodigoFerias())) == 0 ) {
       
      $this->oDaoFerias->excluir($this->getCodigoFerias());
      if ($this->oDaoFerias->erro_status == "0") {
        throw new DBException("Erro [1] - Erro excluindo férias. Msg: {$this->oDaoFerias->erro_msg}");
      }
       
    }

    $sMsgRetorno .= $this->excluirFeriasDependencias();
     
    return $sMsgRetorno;
     
  }
   
  private function excluirFeriasDependencias() {
    
    $sMsgRetorno = "";
     
    $oDaoFGTSfer  = db_utils::getDao("fgtsfer");
    $oDaoPontoFe  = db_utils::getDao("pontofe");
    $oDaoGerffer  = db_utils::getDao("gerffer");
    $oDaoPontoCom = db_utils::getDao("pontocom");
    $oDaoGerfCom  = db_utils::getDao("gerfcom");
    $oDaoPontoFs  = db_utils::getDao("pontofs");
    $oDaoGerfSal  = db_utils::getDao("gerfsal");
    $oDaoPreviden = db_utils::getDao("previden");
    $oDaoPensao   = db_utils::getDao("pensao");
    $oDaoPessoal  = db_utils::getDao("pessoal");
    $oDaoCfPess   = db_utils::getDao("cfpess");
     
    $sWhere  = " r40_regist = {$this->getMatricula()}";
    $sWhere .= " and r40_proc = {$this->getAnoFolha()}/{$this->getMesFolha()}";
    $rsExclusaoFGTS = $oDaoFGTSfer->excluir(null, $sWhere);
    if ($oDaoFGTSfer->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do FGTS das Férias (fgtsfer). Msg:".$oDaoFGTSfer->erro_msg);
    }
     
    $rsExclusaoPontoFe = $oDaoPontoFe->excluir($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula());
    if ($oDaoPontoFe->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Ponto de Férias (PontoFe). Msg:".$oDaoPontoFe->erro_msg);
    }
     
    $rsExclusaoGerffer = $oDaoGerffer->excluir($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula());
    if ($oDaoGerffer->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Calculo de Férias (Gerffer). Msg:".$oDaoGerffer->erro_msg);
    }

    $rsExclusaoPontoCom = $oDaoPontoCom->excluir($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula());
    if ($oDaoPontoCom->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Ponto de Complementar (PontoCom). Msg:".$oDaoPontoCom->erro_msg);
    }
       
    $rsExclusaoGerfCom = $oDaoGerfCom->excluir($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula());
    if ($oDaoGerfCom->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Calculo Complementar (GerfCom). Msg:".$oDaoGerfCom->erro_msg);
    }
       
    $sCampos  = "coalesce(nullif (trim(r11_ferias),''),'0') as r11_ferias,";
    $sCampos .= "coalesce(nullif (trim(r11_fer13o),''),'0') as r11_fer13o ";
    $rsDadosCfPess = $oDaoCfPess->sql_record($oDaoCfPess->sql_query_file($this->getAnoFolha(), $this->getMesFolha(), db_getsession("DB_instit"), $sCampos));
    $oDadosCfPess  = db_utils::fieldsMemory($rsDadosCfPess,0);
       
    $sWhere  = " r10_regist = {$this->getMatricula()} ";
    $sWhere .= " and r10_anousu = {$this->getAnoFolha()} ";
    $sWhere .= " and r10_mesusu = {$this->getMesFolha()} ";
    $sWhere .= " and r10_instit = ".db_getsession("DB_instit");
    $sWhere .= " and r10_rubric in ( '{$oDadosCfPess->r11_ferias}', '{$oDadosCfPess->r11_fer13o}' ) ";
    $rsExclusaoPontoFs = $oDaoPontoFs->excluir(null,null,null,null,$sWhere);
    if ($oDaoPontoFs->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Ponto de Salário (PontoFs). Msg:".$oDaoPontoFs->erro_msg);
    }
     
    $rsExclusaoGerfSal = $oDaoGerfSal->excluir($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula());
    if ($oDaoGerfSal->erro_status == "0") {
      throw new DBException("Erro Excluindo dados do Calculo de Salário (GerfSal). Msg:".$oDaoGerfSal->erro_msg);
    }

    $sMsgRetorno .= "\n- Lance e recalcule sua folha complementar.";
    $sMsgRetorno .= "\n- Recalcule sua folha de salário.";

    $rsDadosPessoal = $oDaoPessoal->sql_record($oDaoPessoal->sql_query_file($this->getAnoFolha(), $this->getMesFolha(), $this->getMatricula()));
    if ($oDaoPessoal->numrows > 0 ) {
       
      $oDadosPessoal = db_utils::fieldsMemory($rsDadosPessoal,0);
       
      $sWhere = "";
      $sWhere .= " and r60_instit = ".db_getsession("DB_instit");
      $sWhere .= " and r60_mesusu = {$this->getMesFolha()}";
      $sWhere .= " and r60_anousu = {$this->getAnoFolha()}";
      $sWhere .= " and r60_numcgm = {$oDadosPessoal->r01_numcgm}";
      $sWhere .= " and r60_tbprev = {$oDadosPessoal->r01_tbprev}";
      $sWhere .= " and r60_rubric = 'R977'";
      $sWhere .= " and r60_regist = {$this->getMatricula()}";
      $rsPrevidencia = $oDaoPreviden->sql_query($oDaoPreviden->sql_query_file(null,null,null,null,null,"*",null,$sWhere));
      if ($oDaoPreviden->numrows > 0) {

        $rsExclusaoPreviden = $oDaoPreviden->excluir(null,null,null,null,null,$sWhere);
        if ($oDaoPreviden->erro_status == "0") {
          throw new DBException("Erro Excluindo dados da tabela da Previdencia (Previden). Msg:".$oDaoPrevidenl->erro_msg);
        }

        $sMsgRetorno .= "\n\OBS.: Este funcionário tem mais de um vínculo no cgm.";
        $sMsgRetorno .= "\nRecalcule todas as matrículas do seguinte cgm: ".$pessoal[0]["r01_numcgm"];

      }

    }
     
    $sSqlPensao  = "select distinct(r52_regist+r52_numcgm),                                                 ";
    $sSqlPensao .= "                pensao.*,                                                               ";
    $sSqlPensao .= "                rh01_regist as r01_regist,                                              ";
    $sSqlPensao .= "                trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac                            ";
    $sSqlPensao .= "  from pensao                                                                           ";
    $sSqlPensao .= "       inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu  ";
    $sSqlPensao .= "                              and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu  ";
    $sSqlPensao .= "                              and pensao.r52_regist         = rhpessoalmov.rh02_regist  ";
    $sSqlPensao .= "       left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu  ";
    $sSqlPensao .= "                              and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu  ";
    $sSqlPensao .= "                              and pontofe.r29_regist        = rhpessoalmov.rh02_regist  ";
    $sSqlPensao .= "       inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist  ";
    $sSqlPensao .= "       inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota    ";
    $sSqlPensao .= "                              and rhlota.r70_instit         = rhpessoalmov.rh02_instit  ";
    $sSqlPensao .= "       inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm     ";
    $sSqlPensao .= "       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes  ";
    $sSqlPensao .= " where rh05_recis is null                                                               ";
    $sSqlPensao .= "   and r52_anousu = {$this->getAnoFolha()}                                                ";
    $sSqlPensao .= "   and r52_mesusu = {$this->getMesFolha()}                                                ";
    $sSqlPensao .= "   and r52_regist = {$this->getMatricula()}                                             ";
    $sSqlPensao .= " order by r52_regist                                                                    ";
    $rsDadosPensao = $oDaoPensao->sql_record($sSqlPensao);
    for ($iInd = 0; $iInd < $oDaoPensao->numrows; $iInd++) {
       
      $oPensao = db_utils::fieldsMemory($rsDadosPensao, $iInd);
      $oDaoPensao->r52_regist = $oPensao->r01_regist;
      $oDaoPensao->r52_numcgm = $oPensao->r01_numcgm;
      $oDaoPensao->r52_anousu = $oPensao->r52_anousu;
      $oDaoPensao->r52_mesusu = $oPensao->r52_mesusu;
      $oDaoPensao->r52_anousu = $oPensao->r52_anousu;
      $oDaoPensao->r52_valor  = "0";
      $oDaoPensao->r52_valfer = "0";
      $oDaoPensao->alterar($oPensao->r52_anousu, $oPensao->r52_mesusu, $oPensao->r01_regist, $oPensao->r01_numcgm);
      if ($oDaoPensao->erro_status == "0") {
        throw new DBException("Erro alterando registros da Pensao. Msg: {$oDaoPensao->erro_msg}");
      }

    }
     
    return $sMsgRetorno;

  }
  
  /**
   * Seta as data de início e fim do proximo periodo aquisitivo de um funcionário
   */
  public function geraPeriodoAquisitivo() {
    
   $this->setPeriodoAquisitivoInicial($this->geraDataInicialPeriodoAquisitivo());
   $this->setPeriodoAquisitivoFinal  ($this->geraDataFinalPeriodoAquisitivo($this->getPeriodoAquisitivoInicial(),
                                                                            $this->getMesesPeriodoAquisitivo()));
  }
  
  /**
   * Retorna o numeros de meses do periodo aquisitivo de férias do funcionário,
   * ou seja, a partir de quantos meses de trabalho o funcionário tem direito a férias
   * @return integer
   */
  public function getMesesPeriodoAquisitivo() {
    
    if (empty($this->iMatricula)) {
      DBException('Erro ao retornar período aquisitivo: Matrícula não informada.');
    }
    
    if (empty($this->iAnoFolha)) {
      DBException('Erro ao retornar período aquisitivo: Ano da folha não informado.');
    }
    
    if (empty($this->iMesFolha)) {
      DBException('Erro ao retornar período aquisitivo: Mês da folha não informado.');
    }
    
    
    $oDaoRhpessoalmov = db_utils::getDao('rhpessoalmov');
    
    $sSqlRhpessoalmov = $oDaoRhpessoalmov->sql_query_parametros($this->getMatricula(), 
                                                                $this->getAnoFolha(), 
                                                                $this->getMesFolha());
    
    $rsRhpessoalmov   = $oDaoRhpessoalmov->sql_record($sSqlRhpessoalmov);
    
    $iMesesPeriodoAquisitivo = 12;
    if ($rsRhpessoalmov && $oDaoRhpessoalmov->numrows > 0) {
      $iTipoPeriodoAquisitivo = db_utils::fieldsMemory($rsRhpessoalmov,0)->rh30_periodoaquisitivo;
      if ($iTipoPeriodoAquisitivo == 1) {
        $iMesesPeriodoAquisitivo = 12;
      } else {
        $iMesesPeriodoAquisitivo = 6;
      }
    }

    return $iMesesPeriodoAquisitivo;
    
  }
  
  /**
   * Gera data de início do período aquisitivo
   * Caso o funcionario não tenha nenhuma férias registrada, é contado apartir da data de admissão do funcionário
   * @return Date
   */
  public function geraDataInicialPeriodoAquisitivo () {
    
    if (empty($this->iMatricula)) {
      throw new ParameterException('Erro ao buscar data de início do período aquisitivo: Matrícula não informada.');
    }
    
    $oDaoRhferias = db_utils::getDao('rhferias');
    
    $sCampos  = "rh109_periodoaquisitivoinicial,                              ";
    $sCampos .= "rh109_periodoaquisitivofinal,                                ";
    $sCampos .= "case                                                         ";
    $sCampos .= "  when rh109_dias > (select sum(rh110_dias)                  ";
    $sCampos .= "                       from rhferiasperiodo                  ";
    $sCampos .= "                      where rh110_rhferias = rhferias.rh109_sequencial)"; 
    $sCampos .= "  then 'f'  																									";																										
    $sCampos .= "  else 't'                                                   "; 
    $sCampos .= "end as novo_periodo                                          ";
    
    $sSqlRhferias = $oDaoRhferias->sql_query_file(null, 
                                                  $sCampos, 
                                                  "rh109_periodoaquisitivofinal desc		 ",
                                                  "rh109_regist = {$this->getMatricula()}");
    
    
    
    $rsRhferias   = $oDaoRhferias->sql_record($sSqlRhferias);
    
    $dDataContagemPeriodoAquisitivo = null;
    
    if ($rsRhferias && $oDaoRhferias->numrows > 0) {
      
      $oRhFerias = db_utils::fieldsMemory($rsRhferias, 0);
      
      if ($oRhFerias->novo_periodo == 't') { 
        $dDataContagemPeriodoAquisitivo = $oRhFerias->rh109_periodoaquisitivofinal;
      } else {
        $dDataContagemPeriodoAquisitivo = $oRhFerias->rh109_periodoaquisitivoinicial;
      }
      
    } else {

      $oDaoRhpessoal = db_utils::getDao('rhpessoal');
      $sSqlRhpessoal = $oDaoRhpessoal->sql_query_file($this->getMatricula());
      $rsRhpessoal   = $oDaoRhpessoal->sql_record($sSqlRhpessoal);
      
      if ($rsRhpessoal && $oDaoRhpessoal->numrows > 0) {
        $dDataContagemPeriodoAquisitivo = db_utils::fieldsMemory($rsRhpessoal, 0)->rh01_admiss;
      }
      
    }
    
    return $dDataContagemPeriodoAquisitivo;
    
  }
  
  /**
   * Gera a data final do próximo período aquisitivo
   * @param date $dDataInicioPeriodoAquisitivo Data inicial do próximo período aquisitivo
   * @param integer $iMesesDireitoPeriodoAquisitivo Meses de direito do período aquisitivo
   */  
  public function geraDataFinalPeriodoAquisitivo($dDataInicioPeriodoAquisitivo, $iMesesDireitoPeriodoAquisitivo) {
    

    $aDataInicioPeriodoAquisitivo = explode('-', $dDataInicioPeriodoAquisitivo);
    
    $iAnoInicioPeriodoAquisitivo  = $aDataInicioPeriodoAquisitivo[0];
    $iMesInicioPeriodoAquisitivo  = $aDataInicioPeriodoAquisitivo[1];
    $iDiaInicioPeriodoAquisitivo  = $aDataInicioPeriodoAquisitivo[2];
    
    $iDataFinalPeriodoAquisitivo  = mktime(0, 
                                           0, 
                                           0, 
                                           $iMesInicioPeriodoAquisitivo + $iMesesDireitoPeriodoAquisitivo, 
                                           $iDiaInicioPeriodoAquisitivo - 1,
                                           $iAnoInicioPeriodoAquisitivo);
    
    return date('Y-m-d', $iDataFinalPeriodoAquisitivo);
    
  }

  /**
   * getCompetenciasPeriodoAquisitivo 
   * 
   * @access public
   * @return void
   */
  function getCompetenciasPeriodoAquisitivo() {

    require_once(modification('libs/db_libpessoal.php'));
    
    $oDataInicial = new DBDate($this->dPeriodoAquisitivoInicial);
    $oDataFinal   = new DBDate($this->dPeriodoAquisitivoFinal);

    if ( !empty( $this->dPeriodoEspecificoInicial ) && !empty( $this->dPeriodoEspecificoFinal ) ) {
      
      $oDataInicial = new DBDate($this->dPeriodoEspecificoInicial);
      $oDataFinal   = new DBDate($this->dPeriodoEspecificoFinal);
    }

    return retornaCompetenciasByPeriodo( $oDataInicial, $oDataFinal );
  }
   
}