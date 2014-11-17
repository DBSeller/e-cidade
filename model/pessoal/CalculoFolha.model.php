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

require_once('libs/db_app.utils.php');
require_once('model/pessoal/RubricaRepository.model.php');
require_once('model/pessoal/EventoFinanceiroFolha.model.php');
    
/**
 * Classse de Definicao do calculo da Folha
 * 
 * @abstract 
 * @package  Pessoal
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
abstract class CalculoFolha {

  const CALCULO_SALARIO         = "gerfsal";
  const CALCULO_ADIANTAMENTO    = "gerfadi";
  const CALCULO_FERIAS          = "gerffer";
  const CALCULO_COMPLEMENTAR    = "gerfcom";
  const CALCULO_13o             = "gerfs13";
  const CALCULO_RESCISAO        = "gerfres";
  const CALCULO_PONTO_FIXO      = "gerffx";
  const CALCULO_PROVISAO_FERIAS = "gerfprovfer";
  const CALCULO_PROVISAO_13o    = "gerfprovs13";
  const MENSAGENS               = "recursoshumanos.pessoal.CalculoFolha.";

  /**
   * Tabela do calculo 
   * 
   * @var string
   * @access protected
   */
  protected $sTabela;

  /**
   * Sigla da tabela 
   * 
   * @var string
   * @access protected
   */
  protected $sSigla;

  /**
   * Servidor proprietário do calculo
   * 
   * @var Servidor
   * @access private
   */
  private $oServidor;

  /**
   * Construtor da Classe 
   * 
   * @param  Servidor $oServidor 
   * @access public
   * @return void
   */
  public function __construct ( Servidor $oServidor ) {
    $this->oServidor = $oServidor;
  }

  /**
   * Retorna instancia do Servidor 
   * @return Servidor
   */
  public function getServidor () { 
    return $this->oServidor;
  }
  
  /**
   * Retorna Ano da competencia
   */
  public function getAnoCompetencia () {
  	return $this->oServidor->getAnoCompetencia();
  }
  
  /**
   * Retorna Mes da competencia
   */
  public function getMesCompetencia () {
  	return $this->oServidor->getMesCompetencia();
  }

  /**
   * Função para gerar calculo para o mes selecionado
   */
  abstract public function gerar ();

  /**
   * @deprecated
   * @see calculoFolha::getEventosFinanceiros()
   * 
   * @param mixed $iSemestre - Utilizado para Complementar
   * @param mixed $sRubrica 
   * @access public
   * @return void
   */
  public function getMovimentacoes( $iSemestre = null, $sRubrica = null) {
     
    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "    {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= "and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if (!empty($iSemestre)) {
      $sWhere        .= "and {$this->sSigla}_semest = {$iSemestre} ";
    }                                                                           
                                                                                
    if (!empty($sRubrica)) {                                                     
      $sWhere .= "and {$this->sSigla}_rubric = '{$sRubrica}' ";
    }
    
    $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                null, 
                                                null, 
                                                null, 
                                                " {$this->sSigla}_rubric as codigo_rubrica, 
                                                  {$this->sSigla}_valor  as valor_rubrica, 
                                                  {$this->sSigla}_pd     as provento_desconto, 
                                                  {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                null, 
                                                $sWhere);

    if ( $this->sTabela == 'gerfres' ) {

      $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                  null, 
                                                  null, 
                                                  null, 
                                                  null, 
                                                  " {$this->sSigla}_rubric as codigo_rubrica, 
                                                    {$this->sSigla}_valor  as valor_rubrica, 
                                                    {$this->sSigla}_pd     as provento_desconto, 
                                                    {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                  null, 
                                                  $sWhere);

    }

    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException(_M(self::MENSAGENS . "erro_buscar_movimentacoes"));
    }

    $aMovimentacoes  =  array();

    foreach ( db_utils::getCollectionbyRecord($rsMovimentacoes) as  $oMovimentacao ) {
  
      $oRetorno = new stdClass();
      $oRetorno->oRubrica          = new Rubrica($oMovimentacao->codigo_rubrica); 
      $oRetorno->nQuantidade       = $oMovimentacao->quantidade_rubrica;
      $oRetorno->nValor            = $oMovimentacao->valor_rubrica;
      $oRetorno->iProventoDesconto = $oMovimentacao->provento_desconto;
      $aMovimentacoes[]            = $oRetorno;
    }

    return $aMovimentacoes;
  }

  /**
   * Retorna Array com os eventos financeiros do servidor
   * 
   * @param integer $iSemestre 
   * @param mixed   $mRubrica 
   * @access public
   * @return Array
   */
  public function getEventosFinanceiros( $iSemestre = null, $mRubrica = null) {
     

    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "     {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= " and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if ( $iSemestre != "" ) {
      $sWhere .= " and {$this->sSigla}_semest = {$iSemestre} ";
    }                                                                           

    if ( !empty($mRubrica) ) {   

      $sWhere .= " and {$this->sSigla}_rubric "; 
      
      if ( is_array($mRubrica) ) {

        $aRubricas = array();

        foreach ( $mRubrica as $sRubrica ) {
          $aRubricas[] = "'$sRubrica'";
        }

        $sWhere .= " in (" . implode(", ", $aRubricas) . ")";
      } else {
        $sWhere .= " = '{$mRubrica}' ";
      }                                                
    }

    switch ( $this->sTabela ) {

      default :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    " {$this->sSigla}_rubric as codigo_rubrica, 
                                                      {$this->sSigla}_valor  as valor_rubrica, 
                                                      {$this->sSigla}_pd     as provento_desconto, 
                                                      {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                    null, 
                                                    $sWhere);
      break;

      case CalculoFolha::CALCULO_RESCISAO :
      case CalculoFolha::CALCULO_PROVISAO_FERIAS :
      case CalculoFolha::CALCULO_FERIAS :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    " {$this->sSigla}_rubric as codigo_rubrica, 
                                                      {$this->sSigla}_valor  as valor_rubrica, 
                                                      {$this->sSigla}_pd     as provento_desconto, 
                                                      {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                    null, 
                                                    $sWhere);


      break;
    }     
    
    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException(_M(self::MENSAGENS . 'erro_buscar_movimentacoes'));
    }

    $aMovimentacoes  =  array();

    for( $iEvento = 0; $iEvento < pg_num_rows($rsMovimentacoes); $iEvento++ ) {
      
      $oMovimentacao = db_utils::fieldsMemory($rsMovimentacoes, $iEvento);
      $oEvento       = new EventoFinanceiroFolha();
      $oRubrica      = RubricaRepository::getInstanciaByCodigo($oMovimentacao->codigo_rubrica);

      $oEvento->setServidor($this->oServidor); 
      $oEvento->setRubrica($oRubrica); 
      $oEvento->setQuantidade($oMovimentacao->quantidade_rubrica);
      $oEvento->setValor($oMovimentacao->valor_rubrica);
      $oEvento->setNatureza($oMovimentacao->provento_desconto);

      $aMovimentacoes[] = $oEvento;
    }

    return $aMovimentacoes;
  }

  /**
   * Função para retornar as rubricas utilizadas no calculo
   * 
   * @access public
   * @return void
   */
  public function getRubricas() {

     
    $oDaoRhrubricas = db_utils::getDao('rhrubricas');
    $sSql           = $oDaoRhrubricas->sql_queryRubricas( $this->oServidor->getMatricula(),
                                                          $this->sTabela,
                                                          $this->sSigla,
                                                          $this->oServidor->getMesCompetencia(),
                                                          $this->oServidor->getAnoCompetencia() );
    $rsRubricas = db_query($sSql);

    if ( !$rsRubricas ) {
      throw new Exception("Erro ao buscar rubricas da competencia: {$this->oServidor->getMesCompetencia()} / {$this->oServidor->getAnoCompetencia()}");
    }

    $aRubricas = array();

    foreach(db_utils::getCollectionByRecord($rsRubricas) as $oRubrica) {
      $aRubricas[] = RubricaRepository::getInstanciaByCodigo($oRubrica->codigo_rubrica);
    }

    return $aRubricas;
  }

  /**
   * Limpa a tabela do calculo
   *
   * @param string $sRubrica
   * @access public
   * @return bool
   */
  public function limpar($sRubrica = null) {
  
    $iAnoCompetencia = $this->getServidor()->getAnoCompetencia();
    $iMesCompetencia = $this->getServidor()->getMesCompetencia();
    $iMatricula      = $this->getServidor()->getMatricula();
  
    $oDaoCalculo = db_utils::getDao($this->sTabela);
    $oDaoCalculo->excluir($iAnoCompetencia, $iMesCompetencia, $iMatricula, $sRubrica);
  
    /**
     * Erro ao excluir registro
     */
    if ( $oDaoCalculo->erro_status == "0" ) {
      throw new Exception($oDaoCalculo->erro_msg);
    }
     
    $this->aRegistros = array();
    return true;
  }

  public function carregarEventos() {
    return $this->aRegistros = $this->getEventosFinanceiros();
  }

  public function adicionarEvento( EventoFinanceiroFolha $oEvento ) {

    $this->aRegistros[] = $oEvento;
  }

  public function salvar() {

    $oDaoFolha = db_utils::getDao($this->sTabela);

    foreach ( $this->aRegistros as $oRegistro ) {
      
      $oDaoFolha->{"{$this->sSigla}_valor"}  = "{$oRegistro->getValor()}";      //Forçando ser string por causa do DAO
      $oDaoFolha->{"{$this->sSigla}_pd"}     = $oRegistro->getNatureza();
      $oDaoFolha->{"{$this->sSigla}_quant"}  = "{$oRegistro->getQuantidade()}"; //Forçando ser string por causa do DAO
      $oDaoFolha->{"{$this->sSigla}_lotac"}  = $oRegistro->getServidor()->getCodigoLotacao();
      $oDaoFolha->{"{$this->sSigla}_semest"} = "0";
      $oDaoFolha->{"{$this->sSigla}_instit"} = $oRegistro->getServidor()->getInstituicao()->getSequencial();

      $oDaoFolha->incluir(
        $this->getAnoCompetencia(), 
        $this->getMesCompetencia(), 
        $oRegistro->getServidor()->getMatricula(), 
        $oRegistro->getRubrica()->getCodigo()
      ); 

      if ( $oDaoFolha->erro_status == "0" ) {
        throw new Exception($oDaoFolha->erro_msg);
      }

    }

    return true;
  }


  /**
   * 
   * @param  [type] $sTipoFolha [description]
   * @return [type]             [description]
   */
  public static function preCalcular( $sTipoFolha )  {
    
    $lFolhaAberta = false;
     
    switch ($sTipoFolha) {

      case self::CALCULO_SALARIO  :

        $sClass             = "CalculoFolhaSalario";
        $oFolha             = FolhaPagamentoSalario::getFolhaAberta();

        if (!$oFolha){
          throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_salario_aberta'));
        }

        $oFolhaComplementar = FolhaPagamentoComplementar::getUltimaFolha( $oFolha->getCompetencia() );

      break;
      case self::CALCULO_COMPLEMENTAR:

        $sClass = "CalculoFolhaComplementar";
        $oFolha = FolhaPagamentoComplementar::getFolhaAberta();

        if (!$oFolha){
          throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_complementar_aberta'));
        }
      break;
      
      default:
        return true;
      break;
    }
    
    if ( !( $oFolha instanceof FolhaPagamento ) ) {
      throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_aberta'));
    }
  
    if (isset($oFolhaComplementar) && $oFolhaComplementar && $oFolhaComplementar->getNumero()) {

      /**
       * Exclui o histórico do cálculo
       */
      $oDaoRhHistoricoCalculo   = new cl_rhhistoricocalculo();
      $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$oFolhaComplementar->getSequencial()}");

      $aServidores = ServidorRepository::getServidoresNoCalculoPorFolhaPagamento($oFolhaComplementar);
      $oFolhaComplementar->salvarHistoricoCalculo($aServidores);
      CalculoFolhaComplementar::limparFolha( $oFolhaComplementar->getCompetencia());

    }

    $aServidores = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($oFolha);
    $oFolha->salvarHistoricoPonto($aServidores);

    /**
     * Sempre será todas as fechadas mais a folha que estou calculando.
     */
    $aFolhasFechadasCompetencia   = FolhaPagamento::getFolhasFechadasCompetencia($oFolha->getCompetencia());
    $aFolhasFechadasCompetencia[] = $oFolha;

    foreach ($aServidores  as $oServidor ) {

      $oPonto = $oServidor->getPonto($oFolha->getTabelaPonto());
      $oPonto->limpar();
      foreach ( $aFolhasFechadasCompetencia as $oFolhaPagamento ) {

        foreach ($oFolhaPagamento->getHistoricoRegistrosPonto($oServidor) as $oRegistro ) {
          $oPonto->adicionarRegistro($oRegistro, false);
        }
      }
      $oPonto->salvar();
    }

    return $oFolha;
  }

  /**
   * Executa pós cálculo da folha de pagamento
   * @param  FolhaPagamento $oFolha [description]
   * @return [type]         [description]
   */
  public static function posCalcular($oFolha) {

    $sTipoFolha = $oFolha->getTipoFolha();

    if ( $sTipoFolha == FolhaPagamento::TIPO_FOLHA_SALARIO ) {

      $oFolhaComplementar = FolhaPagamentoComplementar::getUltimaFolha( $oFolha->getCompetencia() );
      if ($oFolhaComplementar instanceof FolhaPagamentoComplementar && $oFolhaComplementar->getNumero()) {
        $oFolhaComplementar->retornarCalculo();    
      }
    }

    $oDaoRhHistoricoCalculo   = new cl_rhhistoricocalculo();
    $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$oFolha->getSequencial()}");
    
    if ( $oDaoRhHistoricoCalculo->erro_status == 0) {
      throw new DBException($oDaoRhHistoricoCalculo->erro_msg);       
    }

    /**
     * Busca as Folha de Pagamento que Estão fechadas(salaário/Complementar)
     */
    $aFolhasFechadasCompetencia   = FolhaPagamento::getFolhasFechadasCompetencia($oFolha->getCompetencia());
    $aServidores                  = ServidorRepository::getServidoresNoCalculoPorFolhaPagamento($oFolha);

     /**
     * Percorremos os servidores que foram calculadospes
     */
    foreach ($aServidores  as $oServidor ) {

      /**
       * Armazena em memória os eventos resultantes do calculo atual 
       * Porem este array não é associativo
       */
      $oCalculoAtual             = $oServidor->getCalculoFinanceiro($oFolha->getTabelaCalculo());
      $aEventosFinanceirosAtuais = $oCalculoAtual->getEventosFinanceiros();
      $aEventosAtuaisAssociados  = array();

      for ( $iIndiceEvento = 0; $iIndiceEvento < count($aEventosFinanceirosAtuais); $iIndiceEvento++ ) {
        
        $oEventoAtual                             = $aEventosFinanceirosAtuais[$iIndiceEvento];
        $sCodigoRubrica                           = $oEventoAtual->getRubrica()->getCodigo();
        $aEventosAtuaisAssociados[$sCodigoRubrica]= $oEventoAtual;
      }

      /**
       * Agora os eventos financeiros da folha atual estão em um array associativo para facilitar as buscas 
       * dos registros fechados
       * Esse que será gravado no banco
       */
      $aEventosFinanceirosAtuais   = $aEventosAtuaisAssociados;
      $aEventosFinanceirosFechados = array(); 
      /**
       * Limpa a tabela do Calculo
       */
       $oCalculoAtual->limpar();

      /**
       * Percorre as folhas fechadas
       */
      foreach ( $aFolhasFechadasCompetencia as $oFolhaFechada ) {
        /**
         * Percorre os eventos financeiros diminuindo os valores quando houver
         */
        foreach ($oFolhaFechada->getHistoricoEventosFinanceiros($oServidor) as $oEventoHistorico ) {
          $aEventosFinanceirosFechados[] = $oEventoHistorico;
        }
      }

      foreach ( $aEventosFinanceirosFechados as $oEventoFechado ) {

        $sRubricaFechada = $oEventoFechado->getRubrica()->getCodigo();

        if ( !array_key_exists($sRubricaFechada, $aEventosFinanceirosAtuais) ) {
          /**
           * Quando não houver no atual e existir no historico
           */
          continue;
        }

        $oEventoAtual  = $aEventosFinanceirosAtuais[$sRubricaFechada];
        $nValorAtual   = $oEventoAtual->getValor(); 
        $nValorFechado = $oEventoFechado->getValor();

        /**
         * Não altera valores de base de cálculo, apenas de proventos e descontos
         */
        if ( $oEventoAtual->getNatureza() == EventoFinanceiroFolha::BASE ) {
          continue;
        }

        if ( $nValorAtual <= $nValorFechado ) {
          unset($aEventosFinanceirosAtuais[$sRubricaFechada]);
        } elseif ( $nValorAtual > $nValorFechado ) { 
           $oEventoAtual->setValor($nValorAtual -  $nValorFechado);
        }
      }

      /**
       * Percorre persistindo os dados no banco
       */
      foreach ($aEventosFinanceirosAtuais as $oEvento ) {
        $oCalculoAtual->adicionarEvento($oEvento);
      }

      $oCalculoAtual->salvar();

      $oServidor->getPonto($oFolha->getTabelaPonto())->limpar();
    }




    $oFolha->salvarHistoricoCalculo($aServidores);
    $oFolha->retornarPonto();

    return true;
  }
}
