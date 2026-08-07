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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

//use ECidade\Tributario\Cadastro\Iptu\CalculoRetroativo\Repository\CalculoRetroativoIptuRepository;

$oJson                  = new services_json();
$oParametro             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->mensagem     = '';

$clpredio = new cl_predio;

try {

	switch ($oParametro->executa) {
		case "buscaCondominio";
             if(isset($oParametro->j107_sequencial) && trim($oParametro->j107_sequencial) != "") {
 
                 $sCampos = "j111_sequencial, j111_nome ";
                 $sWhere = "j111_condominio = {$oParametro->j107_sequencial}";
                 $sQueryPredios = $clpredio->sql_query(null, $sCampos, null, $sWhere);
                 $resQueryPredios = $clpredio->sql_record($sQueryPredios);
                 if ($clpredio->numrows > 0) {
 
                    $iTotalPredios = $clpredio->numrows;
                    $aPredios = array();
 
                    for  ($i = 0; $i < $iTotalPredios; $i++)	{
             
                         $oRow = db_utils::fieldsMemory($resQueryPredios, $i, false, false, true);
                         $aPredios[] = $oRow;
 
                    }
 				   $oRetorno->predios = $aPredios;
             	} else {
                    $oRetorno->predios = "Vazio";
             	}
             } else {
                 $oRetorno->predios = "Vazio";
             }
 			break;

        case "getRegistroProprietarioLote":
             $iIdbql = $oParametro->iIdbql;
             $oDaoProprietario = new cl_iptubase;
             $sWhereProprietario = "    j01_idbql = $iIdbql
             					  and  EXISTS ( select 1 from iptubase where j01_idbql = $iIdbql and j01_baixa is null )";
             $SsqlProprietario = $oDaoProprietario->proprietario_query (null, "*", null, $sWhereProprietario);
 
             $sProprietario = "Vários Prorietários";
             $rsProprietario = $oDaoProprietario->sql_record($SsqlProprietario);
             if ($oDaoProprietario->numrows == 1) {
 
             	$oDadosProprietario = db_utils::fieldsMemory($rsProprietario, 0);
             	$sProprietario = $oDadosProprietario->proprietario;
             }
             if ($oDaoProprietario->numrows <= 0) {
             	$sProprietario = "";
             }
             $oDadosRetorno = new stdClass();
             $oDadosRetorno->sProprietario = urlencode($sProprietario);
             $oDadosRetorno->sCampo = $oParametro->sCampo;
             $oRetorno->oDados = $oDadosRetorno;
             break;
	
        case "buscarAnosAnteriores":

			 /* Só liberar esse trecho de código após nivelarmos os fontes com São Borja */
			 /*
             $calculoRetroativoIptuRepository = CalculoRetroativoIptuRepository::getInstance();
             $calculoRetroativoIptuRepository->setAnousu(db_getsession("DB_anousu"))
                                             ->setMatricula($oParametro->matricula);
             $liberaCalculoRetroativo = $calculoRetroativoIptuRepository->getLiberaCalculoRetroativo();
             
             if ($liberaCalculoRetroativo) {
                $anosAnterioresMatricula = $calculoRetroativoIptuRepository->getAnosAnterioresMatricula();       
                $oRetorno->anos = $anosAnterioresMatricula;
             } else {
                $oRetorno->erro = true;
                $oRetorno->mensagem = "Cálculo retroativo não liberado.";
             }
			 */
             break;

        default:
             throw new Exception('Nenhuma ação encontrada.');
             break;
    }
} catch (Exception $eErro) {

	db_fim_transacao(true);
    $oRetorno->erro = true;
    $oRetorno->mensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
