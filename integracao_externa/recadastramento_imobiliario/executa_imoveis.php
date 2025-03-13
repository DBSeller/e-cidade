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

require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisArquivo.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisStrategy.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisExclusao.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisInclusao.php"); 
require_once(PATH_IMPORTACAO . "libs/Conexao.model.php");
require_once(PATH_IMPORTACAO . "libs/caracteristicas_imovel.php");
require_once(PATH_IMPORTACAO . "libs/BarraProgressoCli.php");


require_once("model/dataManager.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("std/DBDate.php");
require_once("libs/JSON.php");

db_app::import("configuracao.DBLog");
db_app::import("configuracao.DBLogTXT");

define("DB_BIBLIOT", PATH_IMPORTACAO);
define("FPDF_FONTPATH", "font/");

try {

  Conexao::getInstancia()->query("BEGIN");
  Conexao::getInstancia()->query("SELECT fc_startsession();");
  Conexao::getInstancia()->query("CREATE TEMP TABLE w_testadanumero as select * from testadanumero limit 1;");
  Conexao::getInstancia()->query("CREATE TEMP TABLE w_testada       as select * from testada       limit 1;");
  
  if (!file_exists(PATH_IMPORTACAO . $argv[1])) {
    throw new Exception('Arquivo n�o encontrado.');
  }

  $oRecadastroImobiliarioImoveisArquivo = new RecadastroImobiliarioImoveisArquivo(PATH_IMPORTACAO . $argv[1]);

  if ( $oRecadastroImobiliarioImoveisArquivo->processar() ) {
    Conexao::getInstancia()->query("COMMIT");
  }
} catch( Exception $eErro ) {

  print_r($eErro) . "\n" ;

  Conexao::getInstancia()->query("ROLLBACK");

}