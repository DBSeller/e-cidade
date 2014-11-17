<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
try {

  $oJson               = new services_json();
  $oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));

  $oRetorno            = new stdClass();
  $oRetorno->iStatus   = 1;
  $oRetorno->sMensagem = '';

  switch ( $oParametros->sExec ) {

  case 'salvar':

    $oTraceLog = TraceLog::getInstance();


    if ( $oParametros->lActive ) {
      db_destroysession("TracelogObject");
    }

    $oTraceLog->setProperty('lActive'          , $oParametros->lActive);
    $oTraceLog->setProperty('lShowAccount'     , $oParametros->lShowAccount);
    $oTraceLog->setProperty('lShowSourceInfo'  , $oParametros->lShowSourceInfo);
    $oTraceLog->setProperty('lShowFunctionName', $oParametros->lShowFunctionName);
    $oTraceLog->setProperty('lShowTime'        , $oParametros->lShowTime);
    $oTraceLog->setProperty('lShowBackTrace'   , $oParametros->lShowBackTrace);

    /**
     * Compatibiliadade com a versÃ£o antiga
     */
    db_putsession("DB_traceLog",true);

    if ( !$oParametros->lShowAccount ) {
      db_destroysession("DB_traceLogAcount");
    }

    if ( !$oParametros->lActive ) {

      db_destroysession("DB_traceLog");
    //  db_destroysession("DB_traceLogAcount");
    }

    break;

  case 'testar':

    db_query("select 'Teste 1 de Tracelog';");
    db_query("select 'Teste 2 de Tracelog';");
    db_query("select 'Teste 3 de Tracelog';");
    break;

  case 'retornarStaus':

    $oTraceLog                              = TraceLog::getInstance();
    $oRetorno->oTracelog                    = new stdClass();
    $oRetorno->oTracelog->lActive           = $oTraceLog->isActive();
    $oRetorno->oTracelog->lShowAccount      = $oTraceLog->isDisplayed('Account');
    $oRetorno->oTracelog->lShowSourceInfo   = $oTraceLog->isDisplayed('SourceInfo');
    $oRetorno->oTracelog->lShowFunctionName = $oTraceLog->isDisplayed('FunctionName');
    $oRetorno->oTracelog->lShowTime         = $oTraceLog->isDisplayed('Time');
    $oRetorno->oTracelog->lShowBackTrace    = $oTraceLog->isDisplayed('BackTrace');
    $oRetorno->oTracelog->sFilePath         = '';
    $sArquivo                               = $oTraceLog->getFilePath();
    if ( file_exists($sArquivo) ) {
      $oRetorno->oTracelog->sFilePath         = $sArquivo;
    }

    break;

  case "lerArquivo":

    if ( !isset($_SESSION["DB_ultima_linha_trace_log"]) ) {
      $_SESSION["DB_ultima_linha_trace_log"] = 0;
    }

    $hArquivoAberto = fopen($oParametros->sArquivo, "r");
    $aDadosRetorno  = array();

    /**
     * DefiniÃ§Ã£o das variÃ¡veis de controle
     */
    $iLinhaAtual    = 0;
    $lEmTransacao  = false;
    $lFimTransacao = false;


    while ( !feof($hArquivoAberto) ) {

      /**
       * Pego a string com tamanho de 30000 caracteres.
       */
      $sInstrucaoSQL = fgets($hArquivoAberto, 30000);

      /**
       * Leio somente as linhas que ainda nÃ£o foram lidas.
       */
      if ($_SESSION["DB_ultima_linha_trace_log"] > $iLinhaAtual) {

        $iLinhaAtual++;
        continue;
      }

      if (trim($sInstrucaoSQL) == "") {
        continue;
      }

      /*
       * Verificamos se ocorreu erro para executar a query
       */
      $lLinhaDeErro = false;
      if (strpos($sInstrucaoSQL, "ERRO") > 0) {
        $lLinhaDeErro = true;
      }

      /*
       * Verificamos se estÃ¡ sendo iniciando uma transaÃ§Ã£o com o banco de dados
       */
      if (strpos(strtolower($sInstrucaoSQL), "begin") > 0) {
        $lEmTransacao = true;
      }

      $oStdDado               = new stdClass();
      $oStdDado->lErro        = $lLinhaDeErro;
      $oStdDado->sSql         = urlencode($sInstrucaoSQL);
      $oStdDado->iLinha       = $iLinhaAtual;
      $oStdDado->lEmTransacao = $lEmTransacao;

      /*
       * Caso esteja concluido a transaÃ§Ã£o com o banco, Ã© alterado o parÃ£metro
       */
      if (strpos(strtolower($sInstrucaoSQL), "rollback") > 0 || strpos(strtolower($sInstrucaoSQL), "commit") > 0) {
        $lEmTransacao = false;
      }

      $aDadosRetorno[] = $oStdDado;
      $iLinhaAtual++;
    }

    fclose($hArquivoAberto);

    /*
     * Guardo a Ãºltima linha lida pelo programa
     */
    $_SESSION["DB_ultima_linha_trace_log"] = $iLinhaAtual;

    $oRetorno->aInstrucoesSQL = $aDadosRetorno;


      break;

    case "limparUltimaLinhaLidaTraceLog":
      unset($_SESSION["DB_ultima_linha_trace_log"]);
      break;

  default:
    break;
  }

} catch ( Exception $eErro ) {

  $oRetorno            = new stdClass();
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);