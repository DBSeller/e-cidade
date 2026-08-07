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
 * Classe para processamento da Reinclusao de Imoveis no Recadastro imobiliario
 * 
 * @uses     RecadastroImobiliarioImoveisInterface
 * @package  Recadastro Imobiliario
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @revision $Author: dbanderson $
 * @version  $Revision: 1.6 $
 */
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveis.interface.php");
class RecadastroImobiliarioImoveisReinclusao  implements RecadastroImobiliarioImoveisInterface {

  /**
   * Matricula do Imovel
   * 
   * @var integer
   * @access public
   */
  public $iMatricula;
  
  /**
   * Dados da Linha do Arquivo
   * @var stdClass
   */
  public $oRegistro;
  
  public $sMensagemLog;
  
  public $iCodigoRegistro;
  
  public $sSQL;

  /**
   * Construtor da Classe 
   * 
   * @param mixed $oRegistroArquivo 
   * @access public
   * @return void
   */
  public function __construct($oRegistroArquivo) {

    if (!is_object($oRegistroArquivo)) {
      throw new Exception('Registro invÃ¡lido para exclusÃ£o.');
    }
    
    $this->oRegistro       = $oRegistroArquivo;
    $this->iMatricula      = $oRegistroArquivo->iMatricula;
    $this->iCodigoRegistro = $oRegistroArquivo->iCodigoRegistro;
    
    $this->sSQL            = $oRegistroArquivo->sSetorCartograficoNovo  . "/" ;
    $this->sSQL           .= $oRegistroArquivo->sQuadraCartograficaNovo . "/" ;
    $this->sSQL           .= $oRegistroArquivo->sLoteCartograficoNovo;
    
  }

  public function log($sMensagem, $iTipo = DBLog::LOG_INFO) {
    
    $this->sMensagemLog .= $sMensagem;
    
    RecadastroImobiliarioImoveisArquivo::$oLog($sMensagem, $iTipo);
    
  }
  
  /**
   * Executa Processamento do Registro 
   * 
   * @access public
   * @return boolean
   */
  public function processar() {

    if ( $this->matriculaBaixada() ) {

      $sSqlRemocaoIptuBaixa = "delete from iptubaixa where j02_matric = {$this->iMatricula}";
      $sUpdateIptuBaixa     = "update iptubase set j01_baixa = null where j01_matric = '{$this->iMatricula}'";


      if ( !db_query($sSqlRemocaoIptuBaixa ) ) {

        $this->log( "Erro ao Processar Exclusao dos Registros da Baixa da MatrÃ­cula", DBLog::LOG_ERRO );
        $this->log( "Descricao do Erro: ".pg_last_error(), DBLog::LOG_ERRO );
        throw new Exception("Erro ao Processar Exclusao dos Registros da Baixa da MatrÃ­cula:".pg_last_error());
      }

      if ( !db_query($sUpdateIptubaixa) ) {
        $this->log( "Erro ao Processar AlteraÃ§Ã£o da Data de Baixa da MatrÃ­cula", DBLog::LOG_ERRO );
        $this->log( "Descricao do Erro: ".pg_last_error(), DBLog::LOG_ERRO );
        throw new Exception("Erro ao Processar AlteraÃ§Ã£o da Data de Baixa da MatrÃ­cula".pg_last_error());
      }

      $this->log( " Reincluindo Matricula no cadastro ImobiliÃ¡rio Atraves Cancelamento de Baixa", DBLog::LOG_INFO );
      
      $this->registraLog();
      
      return true;

    } 

    $this->log( "Matricula jÃ¡ esta ativa. Ignorando Registro", DBLog::LOG_INFO );

    return false;

  }

  /**
   * Gera Ocorrencia Para a Matricula
   * @return boolean
   */
  public function registraOcorrencia () {
  
    $oConfiguracoes         = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $iInstituicao           = $aConfiguracoes->sistema['instituicao_prefeitura'];
    RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog( "Incluindo histórico para a matrícula {$this->iMatricula}.", 
                                                             DBLog::LOG_INFO );
    $sMensagemOcorrencia    = "Imóvel ReIncluido pelo recadastramento. Nome do arquivo: {$this->oRegistro->sNomeArquivo}";
    $sInsertHistocorrencia  = "insert into histocorrencia                                    ";
    $sInsertHistocorrencia .= "       (ar23_sequencial  ,                                    ";
    $sInsertHistocorrencia .= "        ar23_id_usuario  ,                                    ";
    $sInsertHistocorrencia .= "        ar23_instit      ,                                    ";
    $sInsertHistocorrencia .= "        ar23_modulo      ,                                    ";
    $sInsertHistocorrencia .= "        ar23_id_itensmenu,                                    ";
    $sInsertHistocorrencia .= "        ar23_data        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_hora        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_tipo        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_descricao   ,                                    ";
    $sInsertHistocorrencia .= "        ar23_ocorrencia)                                      ";
    $sInsertHistocorrencia .= " values (nextval('histocorrencia_ar23_sequencial_seq'),       ";
    $sInsertHistocorrencia .= "         1,                                                   ";
    $sInsertHistocorrencia .= "         {$iInstituicao},                                     ";
    $sInsertHistocorrencia .= "         578,                                                 ";
    $sInsertHistocorrencia .= "         1722,                                                ";
    $sInsertHistocorrencia .= "         '{$this->oRegistro->oDataEnvio->getDate()}',         ";
    $sInsertHistocorrencia .= "         '00:00',                                             ";
    $sInsertHistocorrencia .= "         2,                                                   ";
    $sInsertHistocorrencia .= "         '$sMensagemOcorrencia.',                             ";
    $sInsertHistocorrencia .= "         '$sMensagemOcorrencia.')                             ";
  
    if (db_query ($sInsertHistocorrencia)) {
  
      $sInsertHistocorrenciaMatric  = "insert into histocorrenciamatric                            ";
      $sInsertHistocorrenciaMatric .= "       (ar25_sequencial   ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_matric       ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_histocorrencia)                                ";
      $sInsertHistocorrenciaMatric .= "values (nextval('histocorrenciamatric_ar25_sequencial_seq'),";
      $sInsertHistocorrenciaMatric .= "        {$this->iMatricula},                                ";
      $sInsertHistocorrenciaMatric .= "        currval('histocorrencia_ar23_sequencial_seq'))      ";
  
      if (db_query($sInsertHistocorrenciaMatric)) {
        return true;
      }
  
    }
  
    RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog( "Incluindo histórico de ocorrência para a matrícula {$this->iMatricula}.", DBLog::LOG_ERRO );
  
    return false;
  
  }
  
  /**
   * Valida Matricula, verificando se ela esta baixada.
   *
   * @access public
   * @return boolean
   */
  public function matriculaBaixada() {

    /**
     * Verifica se matrÃ­cula ja estÃ¡ baixada 
     */
    if (!empty($this->iMatricula)) {


      $sSqlIptubaixa = "select * from iptubaixa where j02_matric = {$this->iMatricula}";
      $rsIptubaixa   = db_query($sSqlIptubaixa);

      if ( !$rsIptubaixa ) {

        $this->log( "Buscar InformaÃ§Ãµes da MatrÃ­cula .", DBLog::LOG_ERRO );
        $this->log( "Descricao do Erro: ".pg_last_error(), DBLog::LOG_ERRO );
        throw new Exception("Erro ao Processar Exclusao dos Registros da Baixa da MatrÃ­cula:".pg_last_error());
      }


      if (pg_num_rows($rsIptubaixa) > 0) {
        return true;
      }

      return false;

    }
  }
  
  public function registraLog() {
  
    $this->sMensagemLog = pg_escape_string(Conexao::getInstancia()->getConexao(), $this->sMensagemLog);
  
    $sUpdateRecadastroImobiliarioImoveis  = "update recadastroimobiliarioimoveis                 ";
    $sUpdateRecadastroImobiliarioImoveis .= "   set ie28_processado  = 't',                      ";
    $sUpdateRecadastroImobiliarioImoveis .= "       ie28_observacoes = '{$this->sMensagemLog}'   ";
    $sUpdateRecadastroImobiliarioImoveis .= " where ie28_sequencial  =  {$this->iCodigoRegistro} ";
  
    if (!db_query(Conexao::getInstancia()->getConexao(), $sUpdateRecadastroImobiliarioImoveis)) {
  
      $sMensagem = "Erro ao salvar log das operações do setor/quadra/lote: {$this->sSQL}.";
  
      $this->log($sMensagem, DBLog::LOG_ERROR);
  
      throw new Exception($sMensagem);
  
    }
  
    $this->log("Log do registro das operações executadas para o setor/quadra/lote {$this->sSQL} salvo nas observações do registro {$this->iCodigoRegistro}.", DBLog::LOG_INFO);
  
    return true;
  
  }

}