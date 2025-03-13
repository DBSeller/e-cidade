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

require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/issqn/alvara/Alvara.model.php"));
require_once(modification("std/DBDate.php"));

/**
 * Model para processamento de arquivo para optantes do simples nacional
 *
 * @package ISSQN
 * @author Tales Baz <tales.baz@dbseller.com.br>
 */
 class GeracaoArquivoSimplesNacional {

  /**
   * Constantes com as mensagens de Validação
   */
  const MENSAGENS = 'tributario.issqn.GeracaoArquivoSimplesNacional.';

  /**
   * Codigo do arquivo que esta sendo gerado.
   * @var integer
   */
  private $iArquivoSimplesImportacao;

  /**
   * Data Limite dos débitos.
   * @var String
   */
  private $sDataLimite;
  /**
   * @var array
   */
  private $aEmpresasModificadas = [];

  public function __construct() {}

  /**
   * Define o arquivo que esta sendo processado.
   * @param Integer
   */
  public function setArquivo ($iArquivoSimplesImportacao) {

    $this->iArquivoSimplesImportacao = $iArquivoSimplesImportacao;
  }

  /**
   * Retorna o código do arquivo que esta sendo processado
   * @return integer
   */
  public function getArquivo() {

    return $this->iArquivoSimplesImportacao;
  }

  /**
   * Define a data limite dos débitos
   * @param String
   */
  public function setDataLimite ($sDataLimite) {

    $this->sDataLimite = $sDataLimite;
  }

  /**
   * Retornar cnae disponiveis ou invalidos no arquivo processado
   * @param
   * @return array
   */
  public function getCnae( $iArquivoSimplesImportacao, $lValidos = true ) {

    $this->setArquivo($iArquivoSimplesImportacao);

    $aCnaes                        = array();
    $oDaoArquivoSimplesImportacao  = new cl_arquivosimplesimportacao();

    $sSql                          = $oDaoArquivoSimplesImportacao->getCnaes( $iArquivoSimplesImportacao );

    /**
     * Retorna Cnae invalidos vindos da receita
     */
    if( !$lValidos ){
      $sSql                        = $oDaoArquivoSimplesImportacao->getCnaes( $iArquivoSimplesImportacao, false );
    }

    $rsDAOArquivoSimplesImportacao = $oDaoArquivoSimplesImportacao->sql_record( $sSql );

    if ( $oDaoArquivoSimplesImportacao->numrows > 0 ) {

      $aCnaes = db_utils::getCollectionByRecord( $rsDAOArquivoSimplesImportacao, false, false, true );
    }

    return $aCnaes;
  }

  /**
   * Realiza a validação automatica do arquivo.
   */
  public function validacaoAutomatica($lReprocessamento=false) {

    $rsEmpresas     = $this->getEmpresas();

    if(pg_num_rows($rsEmpresas) == 0){
      throw new DBException( _M( self::MENSAGENS . 'nenhuma_empresa_encontrada' ) );
    }

    $iTotalEmpresas = pg_num_rows($rsEmpresas);

    for ($iEmpresa = 0; $iEmpresa < $iTotalEmpresas; $iEmpresa++) {

      $oDaoArquivoSimplesImportacaoDetalhe = new cl_arquivosimplesimportacaodetalhe();
      $oEmpresa        = db_utils::fieldsMemory($rsEmpresas, $iEmpresa);
      $sValidaEmpresa  = $this->validaEmpresa($oEmpresa->q142_cnpj);

      if (empty($sValidaEmpresa)) {

        $oDaoArquivoSimplesImportacaoDetalhe->q142_apto       = 't';
        $oDaoArquivoSimplesImportacaoDetalhe->q142_observacao = "";
      } else {

        $oDaoArquivoSimplesImportacaoDetalhe->q142_apto       = 'f';
        $oDaoArquivoSimplesImportacaoDetalhe->q142_observacao = $sValidaEmpresa;
      }

      if($oEmpresa->q142_apto != $oDaoArquivoSimplesImportacaoDetalhe->q142_apto && $lReprocessamento == true) {
        
        $oEmpresa->q142_apto       = $oDaoArquivoSimplesImportacaoDetalhe->q142_apto;
        $oEmpresa->q142_observacao = $oDaoArquivoSimplesImportacaoDetalhe->q142_observacao;
        $this->aEmpresasModificadas[] = $oEmpresa;
      }

      if($lReprocessamento == false) {

        $oDaoArquivoSimplesImportacaoDetalhe->q142_sequencial = $oEmpresa->q142_sequencial;
        $oDaoArquivoSimplesImportacaoDetalhe->alterar($oEmpresa->q142_sequencial);
      }
    }
  }

  private function getEmpresas( $lAptos = null, $aFiltroRegistro = []){

    $oDaoArquivoSimplesImportacaoDetalhe = new cl_arquivosimplesimportacaodetalhe();

    $sWhere       = "q142_arquivosimplesimportacao = {$this->iArquivoSimplesImportacao} ";

  	if ($lAptos !== null) {
  		$sWhere .= " and q142_apto = " . ($lAptos ? 'true' : 'false');
  	}

    if(count($aFiltroRegistro) > 0) {

      $sWhere .= " and  q142_sequencial in (".implode(",", $aFiltroRegistro).")";
    }
    
    
  	$sSqlEmpresas = $oDaoArquivoSimplesImportacaoDetalhe->sql_query_file(null, 
                                                                        'q142_sequencial, 
                                                                         q142_arquivosimplesimportacao, 
                                                                         q142_cnpj, 
                                                                         q142_cnae, 
                                                                         q142_apto, 
                                                                         q142_observacao', 
                                                                         null, $sWhere);
                                                                             
    $rsEmpresas   = $oDaoArquivoSimplesImportacaoDetalhe->sql_record($sSqlEmpresas);

    return $rsEmpresas;
  }

  public function isValido() {

    $oDaoArquivoSimplesImportacaoDetalhe = new cl_arquivosimplesimportacaodetalhe();

    $sWhere  = "q142_arquivosimplesimportacao = {$this->iArquivoSimplesImportacao}";
    $sWhere .= " and (q142_observacao is null";
    $sWhere .= " or  q142_observacao = '')";
    $sWhere .= " and q142_apto is false ";

    $sSql       = $oDaoArquivoSimplesImportacaoDetalhe->sql_query_file(null, 'q142_sequencial', null, $sWhere);

    $rsEmpresas = $oDaoArquivoSimplesImportacaoDetalhe->sql_record($sSql);

    return $oDaoArquivoSimplesImportacaoDetalhe->numrows == 0;
  }

  /**
   * Verifica se a empresa é valida, uma empresa é valida quando:
   * -Possui CGM
   * -Possui inscrição Ativa
   * -Não possui débitos em aberto até a data limite informada.
   * -Possui um alvará válido.
   *
   * @param  Integer
   * @return String
   */
  private function validaEmpresa( $iCnpj ){

    $aMensagem = [];

    /**
     * Verificar se existe CGM / E se esta duplicado
     */
    $rsCgm = $this->getCgmByCnpj($iCnpj);
    if( pg_num_rows($rsCgm) > 1 ) {
      return _M ( self::MENSAGENS . 'cgm_duplicado' );
    }

    $iCgm = db_utils::fieldsMemory($rsCgm,0)->z01_numcgm;
    if( pg_num_rows($rsCgm) == 0) {
      $iCgm = false;
    }

    if (!$iCgm) {
      return _M ( self::MENSAGENS . 'cgm_nao_cadastrado' );
    }

    /**
     * verificar se existe inscricao ativa
     */
    $aInscricao = $this->getInscricaoByCgm($iCgm);
    if (empty($aInscricao)) {

      return _M ( self::MENSAGENS . 'inscricao_nao_cadastrada' );
    }

    foreach ($aInscricao as $iInscricao) {
      $oEmpresa = new Empresa($iInscricao);

      if ($this->isInscricaoBaixada($iInscricao)) {
        $aMensagem[$iInscricao] = _M ( self::MENSAGENS . 'inscricao_baixada' );
        continue;
      }

      if (!$oEmpresa->isAtiva()) {
        $aMensagem[$iInscricao] = _M ( self::MENSAGENS . 'inscricao_invalida' );
        continue;
      }

      /**
       * Verificar se possui débito vencido
       */
      $oDebitosVencidos = $oEmpresa->getDebitos()->getDebitosVencidos($this->sDataLimite);
      if (count( (array) $oDebitosVencidos )  > 0) {
        return _M ( self::MENSAGENS . 'empresa_com_debitos_em_aberto' );
      }

      /**
       * Alvara valido - No redmine 19502 foi solicitado para não ser mais validado o alvará
       */
      // $iCodigoAlvara = $this->getCodigoAlvaraByInscricao($oEmpresa->getInscricao());
      // if(!$iCodigoAlvara){
      //   return _M ( self::MENSAGENS . 'empresa_sem_alvara' );
      // }

      // $oAlvara       = new Alvara($iCodigoAlvara);
      // if( $oAlvara->getSituacao() == Alvara::INATIVO ) {
      //   return _M ( self::MENSAGENS . 'alvara_inativo' );
      // }
      if (!$this->isInscricaoBaixada($iInscricao) && ($oEmpresa->isAtiva())) {
        $aMensagem[$iInscricao] = '';
      }
    }

    if (sizeof($aMensagem)>1) {
      foreach ($aMensagem as $value) {
        if (empty($value)) {
          return '';
        }
      }
    }
    return array_pop(array_reverse($aMensagem));
  }

  public function gerarTxt() {

    $iCodTom   = $this->getTom();

    $dtArquivo = date("Ymd");
    $tArquivo  = date("His");
    
    $sNomeArquivo = "01-$iCodTom-UP-OPC-$dtArquivo-$tArquivo.txt";

    $rsFile       = fopen("tmp/" . $sNomeArquivo, "w");

    $rsEmpresas = $this->getEmpresas(false);

    if (!$rsEmpresas) {
      throw new Exception("[ERRO] - Não foi possível buscar os registros");
    }
    
    if(pg_num_rows($rsEmpresas) == 0) {

      throw new Exception( _M( self::MENSAGENS . 'todos_os_registros_aptos' ));
    }
    /**
     * HEADER
     */
    fwrite($rsFile, "INI".str_repeat("0", 11) . "\n");

    for ($i = 0; $i < pg_num_rows($rsEmpresas); $i++) {

      $oEmpresa = db_utils::fieldsMemory($rsEmpresas, $i);
      fwrite($rsFile, str_pad($oEmpresa->q142_cnpj, 11, "0", STR_PAD_LEFT) . "\n");
    }

    /**
     * TRAILLER
     */
    fwrite($rsFile, str_repeat("9", 14));

    fclose($rsFile);

    return "tmp/$sNomeArquivo";
  }

  private function getTom() {

    $oDaoArquivoSimplesImportacao = new cl_arquivosimplesimportacao();
    $oDaoDbConfig                 = db_utils::getDao("db_config");

    $oTom = $oDaoDbConfig->getCodigoTom(db_getsession("DB_instit"));

    if (empty($oTom)) {
      return false;
    }

    return $oTom->db125_codigosistema;
  }


  /**
   * Gera o relatório de incosistências dos registros de empresas
   *
   * @return null|String
   */
  public function relatorioInconsistencias() {
    $oDaoArquivoSimplesDetalhe = new cl_arquivosimplesimportacaodetalhe();

    $sSql = $oDaoArquivoSimplesDetalhe->sql_query_inconsistencias( null,
                                                                   "distinct ON (z01_numcgm,q142_cnpj) q142_cnpj, z01_numcgm, z01_nome, q02_inscr,"
                                                                   . " q142_observacao, q64_nomearquivo, (array_to_string(array(SELECT x.q02_inscr FROM issbase x WHERE x.q02_numcgm = cgm.z01_numcgm),', ','')) AS inscricoes",
                                                                   'q142_cnpj',
                                                                   "q64_sequencial = {$this->iArquivoSimplesImportacao}"
                                                                   . " and q142_apto = false" );

    $rsIncosistencias = $oDaoArquivoSimplesDetalhe->sql_record( $sSql );

    if ($oDaoArquivoSimplesDetalhe->numrows <= 0) {
      return null;
    }

    /**
     * Gera o PDF
     */
    $oPdf = new PDFNovo();

    $oPdf->addTableHeader('CNPJ', 25, 4, 'C');
    $oPdf->addTableHeader('CGM', 13, 4, 'C');
    $oPdf->addTableHeader('NOME', 70, 4, 'C');
    $oPdf->addTableHeader('INSCRIÇÃO', 35, 4, 'C');
    $oPdf->addTableHeader('MOTIVO', 0, 4, 'C');

    $oRegistro = db_utils::fieldsMemory($rsIncosistencias, 0);

    $oPdf->addHeader('RELATÓRIO DE INCONSISTÊNCIAS DE NÃO APTOS AO SIMPLES NACIONAL');
    $oPdf->addHeader("ARQUIVO: {$oRegistro->q64_nomearquivo}");

    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->AddPage("p");
    $oPdf->SetFillColor(235);

    for ($iLinha = 0; $iLinha < $oDaoArquivoSimplesDetalhe->numrows; $iLinha++) {

      $oRegistro = db_utils::fieldsMemory($rsIncosistencias, $iLinha);

      /**
       * Trata o CNPJ colocando a máscara
       */
      $oRegistro->q142_cnpj = str_pad($oRegistro->q142_cnpj, 14, '0', STR_PAD_LEFT);
      $oRegistro->q142_cnpj = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', "$1.$2.$3/$4-$5", $oRegistro->q142_cnpj);

      /**
       * Verifica se deve colorir a linha ou não
       */
      $lFill = !($iLinha % 2 == 0);

      /**
       * Faz um Substr caso a string seja maior que o tamanho da célula
       */
      while ($oPdf->GetStringWidth($oRegistro->q142_observacao) > 60) {
        $oRegistro->q142_observacao = substr($oRegistro->q142_observacao, 0, strlen($oRegistro->q142_observacao)-2);
      }

      /**
       * Cria outra linha caso o número de inscrições seja maior que o tamanho da célula
       */ 

      if($oPdf->GetStringWidth($oRegistro->inscricoes) > 35){
          $y = $oPdf->y;
          $oPdf->Cell(25, 8, $oRegistro->q142_cnpj, true, 0, 'C', $lFill);
          $oPdf->Cell(13, 8, $oRegistro->z01_numcgm, true, 0, 'C', $lFill);
          $oPdf->Cell(70, 8, $oRegistro->z01_nome, true, 0, 'L', $lFill);
          $oPdf->setX(118);
          $oPdf->MultiCell(35, 4, $oRegistro->inscricoes, true, "L", $lFill, 0);
          $ym = $oPdf->y;
          $oPdf->setY($y);
          $oPdf->setX(153);
          $oPdf->Cell(0, 8,  $oRegistro->q142_observacao, true, 1, 'L', $lFill);

          $oPdf->setY($ym);
        
      } else {

      $oPdf->Cell(25, 4, $oRegistro->q142_cnpj, true, 0, 'C', $lFill);
      $oPdf->Cell(13, 4, $oRegistro->z01_numcgm, true, 0, 'C', $lFill);
      $oPdf->Cell(70, 4, $oRegistro->z01_nome, true, 0, 'L', $lFill);
      $oPdf->Cell(35, 4, $oRegistro->inscricoes, true, 0, 'C', $lFill);
      $oPdf->Cell(0, 4,  $oRegistro->q142_observacao, true, 1, 'L', $lFill);
      
      }
    }

    $oPdf->Cell(0, 4,  'TOTAL: '.$oDaoArquivoSimplesDetalhe->numrows, true, 1, 'L', true);

    $sCaminho = "tmp/Inconsistencias_Simples_Nacional_" . date('ymd-His') . ".pdf";

    $oPdf->Output($sCaminho, false, true);

    return $sCaminho;
  }

  /**
   * Retorna o CGM da empresa a partir do CNPJ informado.
   * @param  integer $iCnpj CNPJ empresa
   * @return integer        Número do CGM
   */
  public function getCgmByCnpj($iCnpj) {

    $oDaoCgm = new cl_cgm();

    $sWhereCgm = " z01_cgccpf = '{$iCnpj}'";
    $sSqlCgm   = $oDaoCgm->sql_query_file(null,"z01_numcgm",null,$sWhereCgm);
    $rsCgm     = db_query($sSqlCgm);

    return $rsCgm;
  }

  /**
   * Retorna a Inscrição da empresa a partir do CGM informado
   * @param  integer $iCgm CGM da empresa
   * @return array       INscrição
   */
  public function getInscricaoByCgm($iCgm) {

    $oDaoIssbase = new cl_issbase();

    $dtOper         = date('Y-m-d');
    $sWhereIssbase  = " q02_numcgm = {$iCgm} ";

    $sSqlIssbase    = $oDaoIssbase->sql_query(null,"q02_inscr",null,$sWhereIssbase);
    $rsIssbase      = $oDaoIssbase->sql_record($sSqlIssbase);
    $aInscricao = [];

    if ( $oDaoIssbase->numrows > 0 ) {
      // $iInscricao = db_utils::fieldsMemory($rsIssbase,0)->q02_inscr;
      foreach (pg_fetch_all($rsIssbase) as $value) {
        array_push($aInscricao, $value['q02_inscr']);
      }
    } else {
      $aInscricao = [];
    }

    return $aInscricao;
  }

  /**
   * Retorna o Alvara da empresa a partir da INscrição informada.
   * @param  integer $iInscricao Iscrição da empresa.
   * @return integer             Código do álvara.
   */
  public function getCodigoAlvaraByInscricao( $iInscricao ) {

    $oDaoAlvara = db_utils::getDao('issalvara');

    $sSqlAlvara = $oDaoAlvara->sql_query_file(null, "q123_sequencial", null, "q123_inscr = $iInscricao");
    $rsAlvara   = $oDaoAlvara->sql_record($sSqlAlvara);

    $iAlvara = 0;
    if ( $oDaoAlvara->numrows != 0) {

      $oAlvara = db_utils::fieldsMemory($rsAlvara, 0);
      $iAlvara = $oAlvara->q123_sequencial;
    }

    return $iAlvara;
  }

  public function getEmpresasByCnae( $sEstrutural ) {

    $oDaoArquivoSimplesImportacao = db_utils::getDao('arquivosimplesimportacao');

    $sSqlEmpresas = $oDaoArquivoSimplesImportacao->getEmpresabyCnaes( $sEstrutural, $this->iArquivoSimplesImportacao );

    /**
     * Quando Cnae inválido é enviado pela receita utilizamos o estrutural "Y"
     * por estar fora da faixa válida de seções. Exemplo estrutural do Cnae:
     *
     * Seção:     A         AGRICULTURA, PECUARIA, SILVICULTURA E EXPLORAÇAO FLORESTAL
     * Divisão:   02        SILVICULTURA, EXPLORAÇAO FLORESTAL E SERVIÇOS RELACIONADOS COM ESTAS ATIVIDADES
     * Grupo:     021       SILVICULTURA, EXPLORAÇAO FLORESTAL E SERVIÇOS RELACIONADOS COM ESTAS ATIVIDADES
     * Classe:    0213-5    ATIVIDADES DOS SERVIÇOS RELACIONADOS COM A SILVICULTURA E A EXPLORAÇAO FLORESTAL
     * Subclasse: 0213-5/00 ATIVIDADES DOS SERVIÇOS RELACIONADOS COM A SILVICULTURA E A EXPLORAÇAO FLORESTAL
     * @var [type]
     */
    if ( $sEstrutural == 'Y'){

      $sSqlEmpresas = $oDaoArquivoSimplesImportacao->getCnaes( $this->iArquivoSimplesImportacao, false );
    }

    $rsEmpresas   = $oDaoArquivoSimplesImportacao->sql_record( $sSqlEmpresas );

    return db_utils::getCollectionByRecord( $rsEmpresas, false, false, true );
  }

  public function setAptos($oEmpresas, $lApto) {

    foreach ( $oEmpresas as $oEmpresa ) {

      $oDaoArquivoSimplesImportacaoDetalhe = new cl_arquivosimplesimportacaodetalhe();
      $oDaoArquivoSimplesImportacaoDetalhe->q142_sequencial = $oEmpresa->iSequencial;

      $oDomDocument = new DOMDocument('1.0', 'utf8');
      $oDomDocument->loadHTML( $oEmpresa->sObservacao);
      $sMensagem    = $oDomDocument->getElementsByTagName("input")->item(0)->getAttribute("value");

      $oDaoArquivoSimplesImportacaoDetalhe->q142_observacao = utf8_decode($sMensagem);

      if ($lApto !== null){

        $oDaoArquivoSimplesImportacaoDetalhe->q142_apto = 'f';
        if ($lApto) {
          $oDaoArquivoSimplesImportacaoDetalhe->q142_apto = 't';
        }
      }

      $oDaoArquivoSimplesImportacaoDetalhe->alterar($oEmpresa->iSequencial);
    }
  }

  /**
   * Retorna se a inscrição está baixada
   * @param  integer $iInscricao Inscrição
   * @return boolean
   */
  public function isInscricaoBaixada($iInscricao) {

    $oDaoIssbase = new cl_issbase();
    // $dtOper         = date('Y-m-d');  
    // $sWhereIssbase  = " q02_inscr = {$iInscricao} and (q02_dtbaix is null or q02_dtbaix > '{$dtOper}') ";

    $sWhereIssbase     = " q02_inscr = {$iInscricao} and (q02_dtbaix is not null) ";
    $sSqlIssbase       = $oDaoIssbase->sql_query_atividades(null,"q02_inscr",null,$sWhereIssbase);
    $rsIssbase         = $oDaoIssbase->sql_record($sSqlIssbase);
    $oInscricaoBaixada = db_utils::fieldsMemory($rsIssbase,0);

    if ( $oDaoIssbase->numrows > 0 ) {
      return true;
    }

    return false;
  }

  public function gerarTxtArquivosADC($aCNPJAlterado){
    
    
    $iCodTom      = $this->getTom();
    $dtArquivo    = date("Ymd");
    $tArquivo     = date("His");    
    
    $aCnpj        = array_reduce($aCNPJAlterado, function($dados, $data) 
                       {  
                         
                         if($data->q142_apto == 'f') {
                           
                           $dados[] = $data->q142_sequencial;
                         }                                  
                         
                         return $dados;
                       }              
                      );
    if(count($aCnpj) == 0) {
    
      return false;
    }  
    
    $rsEmpresas = $this->getEmpresas(true, $aCnpj);

    if (!$rsEmpresas) {
      throw new Exception("[ERRO] - Não foi possível buscar os registros");
    }
    
    if(pg_num_rows($rsEmpresas) == 0) {

      return false;
    }
    
    $sNomeArquivo = "03-$iCodTom-UP-OPC-$dtArquivo-$tArquivo.txt";
    $rsFile       = fopen("tmp/" . $sNomeArquivo, "w");
    /**
     * HEADER
     */
    fwrite($rsFile, "ADC".str_repeat("0", 11) . "\n");

    for ($i = 0; $i < pg_num_rows($rsEmpresas); $i++) {

      $oEmpresa = db_utils::fieldsMemory($rsEmpresas, $i);
      fwrite($rsFile, str_pad($oEmpresa->q142_cnpj, 11, "0", STR_PAD_LEFT) . "\n");
    }

    /**
     * TRAILLER
     */
    fwrite($rsFile, str_repeat("9", 14));

    fclose($rsFile);
    
    return "tmp/$sNomeArquivo"; 
  }

  public function gerarTxtArquivosEXC($aCNPJAlterado){
    
    $iCodTom      = $this->getTom();
    $dtArquivo    = date("Ymd");
    $tArquivo     = date("His");    
    $aCnpj        = array_reduce($aCNPJAlterado, function($dados, $data) 
                      {  
                       
                       if($data->q142_apto == 't') {
                         
                         $dados[] = $data->q142_sequencial;
                       }                                  
                       
                       return $dados;
                     }              
                    );

    if(count($aCnpj) == 0) {
    
      return false;
    }  
                  
    $rsEmpresas = $this->getEmpresas(false, $aCnpj);

    if (!$rsEmpresas) {

      throw new Exception("[ERRO] - Não foi possível buscar os registros");
    }
    
    if(pg_num_rows($rsEmpresas) == 0) {

      return false;
    }
    
    $sNomeArquivo = "04-$iCodTom-UP-OPC-$dtArquivo-$tArquivo.txt";
    $rsFile       = fopen("tmp/" . $sNomeArquivo, "w"); 
    
    /**
     * HEADER
     */
    fwrite($rsFile, "EXC".str_repeat("0", 11) . "\n");

    for ($i = 0; $i < pg_num_rows($rsEmpresas); $i++) {

      $oEmpresa = db_utils::fieldsMemory($rsEmpresas, $i);
      fwrite($rsFile, str_pad($oEmpresa->q142_cnpj, 11, "0", STR_PAD_LEFT) . "\n");
    }

    /**
     * TRAILLER
     */
    fwrite($rsFile, str_repeat("9", 14));

    fclose($rsFile);

    return "tmp/$sNomeArquivo"; 
  }
   
  public function empresasModificadasReprocessamento() {
    return $this->aEmpresasModificadas;
  }

  public function getArquivosGerados() {

    $oDaoArquivoSimplesImportacaoRetorno = new cl_arquivosimplesimportacaoretorno();
    $sSqlRetono                          = $oDaoArquivoSimplesImportacaoRetorno
                          ->sql_query(null, 
                                     "q182_sequencial, q182_nomearquivo, nome", 
                                     null, 
                                     "q182_arquivosimplesimportacao = {$this->iArquivoSimplesImportacao}");
    $rsRetorno                           = $oDaoArquivoSimplesImportacaoRetorno->sql_record($sSqlRetono);
    
    return db_utils::getCollectionByRecord( $rsRetorno, false, false, true );
  }
}