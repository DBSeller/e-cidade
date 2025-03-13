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


$HTTP_SERVER_VARS['HTTP_HOST']      = '';
$HTTP_SERVER_VARS['PHP_SELF']       = '';
$HTTP_SERVER_VARS["HTTP_REFERER"]   = '';
$HTTP_POST_VARS                     = array();
$HTTP_GET_VARS                      = array();

define("PATH_IMPORTACAO", "integracao_externa/recadastramento_imobiliario/");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioLogradouros.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisBic.php");
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

define("DB_BIBLIOT", PATH_IMPORTACAO);
define("FPDF_FONTPATH", "font/");



try {
  
 db_query(Conexao::getInstancia()->getConexao(), "BEGIN");
 db_query(Conexao::getInstancia()->getConexao(), "SELECT FC_STARTSESSION()");
 
 $oRecadastroImobiliarioLogradouros = new RecadastroImobiliarioLogradouros(PATH_IMPORTACAO . $argv[1]);
 $oRecadastroImobiliarioLogradouros->carregarArquivo();
 $oRecadastroImobiliarioLogradouros->processarImportacao();

 $sSql            = "SELECT j01_matric from iptubase;";
 $rsSqlMatriculas = db_query( Conexao::getInstancia()->getConexao(), $sSql );
 $aTotalRegistros = db_utils::getCollectionByRecord($rsSqlMatriculas);
 $iTotalRegistros = count($aTotalRegistros);
 $oBarraProgresso = new BarraProgressoCli($iTotalRegistros);
 echo "\n";
 echo "Processamento BIC's: \n";

 foreach (db_utils::getCollectionByRecord($rsSqlMatriculas) as $oResultado) {

   $oBarraProgresso->atualizar();
    
   //echo "Processando BIC da Matricula: $oResultado->j01_matric";
   $oProcessamentoBIC = new RecadastroImobiliarioImoveisBic($oResultado->j01_matric);
   $oProcessamentoBIC->processar();
}
 
 db_query(Conexao::getInstancia()->getConexao(), "COMMIT;");
 echo "\n";
} catch( Exception $eErro ) {

  db_query(Conexao::getInstancia()->getConexao(), "ROLLBACK");
  echo "Erro ao Processar".$eErro->getMessage();
}