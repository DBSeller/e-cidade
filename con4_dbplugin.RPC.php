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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");  
require_once("model/configuracao/PluginService.service.php");


$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->sExecucao) {

    case "getPlugins":

      $oPluginService = new PluginService();
      $oRetorno->aPlugins = $oPluginService->getPlugins();

    break;

    case "alterarSituacao" :

      if (empty($oParam->iCodigo)) {
        throw new Exception("Nenhum plugin informado.");        
      }

      $oPlugin = new Plugin($oParam->iCodigo);
      $oPluginService = new PluginService();

      if ($oPluginService->isAtivo($oPlugin)) {
        $oPluginService->desativar($oPlugin);
        $oRetorno->sMessage = "Plugin desativado com sucesso.";
      } else {
        $oPluginService->ativar($oPlugin);
        $oRetorno->sMessage = "Plugin ativado com sucesso.";
      }

    break;

    case "desinstalar": 

      if (empty($oParam->iCodigo)) {
        throw new Exception("Nenhum plugin informado.");        
      }

      $oPlugin = new Plugin($oParam->iCodigo);
      $oPluginService = new PluginService();
      $oPluginService->desinstalar($oPlugin);

      $oRetorno->sMessage = "Plugin desinstalado com sucesso.";

    break;

    case "getConfig":

      $oPlugin = new Plugin($oParam->iCodigo);
      $oRetorno->aConfiguracoes = PluginService::getPluginConfig($oPlugin);

    break;

    case "saveConfig":

      $oPlugin = new Plugin($oParam->iCodigo);
      $oRetorno->sMessage = urlencode("Configuração salva com sucesso.");

      if (!PluginService::setPluginConfig($oPlugin, $oParam->aConfig)) {
        $oRetorno->sMessage = urlencode("Erro ao salvar configuração.");
      }

    break;
  }
  
  db_fim_transacao(false);

  
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

?>