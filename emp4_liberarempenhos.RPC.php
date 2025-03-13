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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/empenho.php"));
require_once(modification("classes/db_empempenholiberado_classe.php"));

include(modification("libs/JSON.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();	
$oRetorno->status  = 1;
$oRetorno->aItens  = array();

switch ($oParam->exec) {

	/*
	 * Pesquisa empenhos para a liberacao
	 */
	
	case "pesquisaEmpempenho":
		
		$oEmpenho          = new empenho();
	  	$oRetorno->aItens  = $oEmpenho->getEmpenhosLiberados($oParam);
		break;
		
	/*
	 * Processa empenhos selecionados para a liberaчуo
	 */
		
	case "processaEmpenhoLiberados":

    $oEmpenho = new empenho();
    try {
		$clEmpEmpenho = new cl_empempenho();
		$sIdsEmpenho = '';

		foreach($oParam->aEmpenhos as $empenho) {
			$sIdsEmpenho .= $empenho->iNumemp;
		}
		
		$sSql = $clEmpEmpenho->sql_query_liberarempenho(null, 'e60_numemp, e22_sequencial', '', "e60_numemp in ({$sIdsEmpenho})");
		$oPostgresResource = db_query($sSql);

		$mapEmpenhoLiberacao = array();
		while ($row = pg_fetch_assoc($oPostgresResource)) {
			$mapEmpenhoLiberacao[$row['e60_numemp']] = $row['e22_sequencial'] ? true : false;			
		}

		db_inicio_transacao();

		$oEmpenho->liberarEmpenho($oParam->aEmpenhos);

		foreach($oParam->aEmpenhos as &$empenho) {
			$empenho->flagSemAcao = $mapEmpenhoLiberacao[$empenho->iNumemp];
		}
		
		$oRetorno->empenhos = $oParam->aEmpenhos;
	
    	db_fim_transacao(false);	
    } catch (Exception $eErro) {
    	
    	db_fim_transacao(true);
    	$oRetorno->status = 2;
    	$oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}
echo $oJson->encode($oRetorno);
?>