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

// Declarando variáveis necessárias para que a inclusão das bibliotecas não retorne mensagens.
$HTTP_SERVER_VARS['HTTP_HOST']      = '';
$HTTP_SERVER_VARS['PHP_SELF']       = '';
$HTTP_SERVER_VARS["HTTP_REFERER"]   = '';
$HTTP_POST_VARS                     = array();
$HTTP_GET_VARS                      = array();

define("PATH_IMPORTACAO", "integracao_externa/recadastramento_imobiliario/");

require_once(PATH_IMPORTACAO . "RecadastroImobiliarioFacesQuadra.php");
require_once(PATH_IMPORTACAO . "libs/Conexao.model.php");
require_once(PATH_IMPORTACAO . "libs/BarraProgressoCli.php");
require_once("model/dataManager.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("std/DBDate.php");
require_once("libs/JSON.php");

db_app::import("configuracao.DBLog");
db_app::import("configuracao.DBLogTXT");

try {

  db_query(Conexao::getInstancia()->getConexao(), "BEGIN");
  db_query(Conexao::getInstancia()->getConexao(), "SELECT fc_startsession();");
  
  if (!file_exists(PATH_IMPORTACAO . $argv[1])) {
    throw new Exception('Arquivo n�o encontrado.');
  }
  
  $oRecadastroImobiliarioFacesQuadra = new RecadastroImobiliarioFacesQuadra(PATH_IMPORTACAO . $argv[1]);
  
  $oRecadastroImobiliarioFacesQuadra->carregarArquivo();
 if ( $oRecadastroImobiliarioFacesQuadra->processarInformacoes() ) {
  db_query(Conexao::getInstancia()->getConexao(), "COMMIT");
 }
} catch( Exception $eErro ) {

  db_query(Conexao::getInstancia()->getConexao(), "ROLLBACK");
  
}