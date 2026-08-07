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
require_once(modification("dbforms/db_funcoes.php"));

$oDaoReciboUnica        = db_utils::getDao('recibounica');
$oDaoReciboUnicaGeracao = db_utils::getDao('recibounicageracao');

$oJson                  = new services_json();

$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->status       = 1;
$oRetorno->message      = '';

$aDadosRetorno          = array();

try {
	switch ($oParam->exec) {

		case "processaDados":

	  		$aNumpres = array();
	  		$aUnicas = $oParam->oDados->aUnicas;

		  	if($oParam->oDados->sChavePesquisa == null) {		  		
	            $sTipoGeracao  = "G";
	            $sExerciciosSelecionados = implode(', ', $oParam->oDados->aExercicio);

		  		// Verificamos se esta configuraro o parametro no exercicio atual
	            $sql = "SELECT 
	                        j18_taxaseparada as habilitataxa 
	                    from 
	                        cadastro.cfiptu
	                    WHERE 
	                        j18_anousu in ({$sExerciciosSelecionados})
	                    limit 1";
	            $rs = db_query($sql);
	            if (!$rs) {
	                throw new DBException("Não foi possivel buscar configurações de iptu para o exercicio {$oParam->oDados->aExercicio}.");
	            }

	            $habilitaTaxas = db_utils::fieldsMemory($rs, 0)->habilitataxa;
	                       	
            	foreach ($aUnicas as $unica) {
            		
		      		$aNumpres = array();
            		$unica = $oJson->decode(str_replace("\\","", $unica));
            		$debito = $unica->debito;
				  	$percentual = $unica->desconto;
            		$dtLancamento = implode("-",array_reverse(explode("/",$unica->lancamento)));
				  	$dtVencimento = implode("-",array_reverse(explode("/",$unica->vencimento)));
		      		$sObservacao = addslashes(db_stdClass::normalizeStringJsonEscapeString($unica->observacoes));	      		

            		if(!$habilitaTaxas || $oParam->oDados->iCadTipoDebito != 1 || $debito == 'IPTU'){
            			$sSqlNumpres   = $oDaoReciboUnicaGeracao->sql_query_pesquisa($oParam->oDados->sTipoPesquisa,
		                                                                 	 $oParam->oDados->sChavePesquisa,
		                                                                 	 true,
		                                                                 	 $oParam->oDados->iCadTipoDebito);

		        		if( !empty($oParam->oDados->aExercicio) ){

		          			$sExerciciosSelecionados = implode(', ', $oParam->oDados->aExercicio);
		          			$sWhere = " and extract(year from arrecad.k00_dtoper) in ({$sExerciciosSelecionados})";
		          			if ( $oParam->oDados->sTipoPesquisa == "M" ) {
		            			$sWhere = " and j20_anousu in ({$sExerciciosSelecionados})";
		          			}
		          			$sSqlNumpres .= $sWhere;

		        		}
				    	
            		} else {
            			$sql = "SELECT 
            						j08_tabrec as tipodebito 
            					from 
            						iptucadtaxaexe 
            					where j08_iptucadtaxaexe = {$debito}";
            			$rs = db_query($sql);
			            if (!$rs) {
			                throw new DBException("Não foi possivel buscar configurações de iptu para o exercicio {$oParametros->exercicio}.");
			            }

			            $tipoDebito = db_utils::fieldsMemory($rs, 0)->tipodebito;
			           	$sSqlNumpres   = $oDaoReciboUnicaGeracao->sql_query_pesquisa_taxa($oParam->oDados->sTipoPesquisa,$tipoDebito);

		        		if( !empty($oParam->oDados->aExercicio) ){		          			
		          			$sWhere = " and extract(year from arrecad.k00_dtoper) in ({$sExerciciosSelecionados})";			          			
		          			$sSqlNumpres .= $sWhere;
		        		}				 		  	
            		}	 

            		$rsNumpres     = $oDaoReciboUnicaGeracao->sql_record($sSqlNumpres);
				    	
			    	if($rsNumpres && pg_num_rows($rsNumpres)) {
			      		$aRowsNumpres = db_utils::getCollectionByRecord($rsNumpres);		      		
			      		foreach($aRowsNumpres as $oNumpre) {
			        		$aNumpres[] = $oNumpre->k00_numpre;
			      		}
			    	}

			    	db_inicio_transacao();
				  	try {
						/**
						* inserindo dados da recibounica geração
						*/
						$oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
						$oDaoReciboUnicaGeracao->ar40_dtoperacao         = $dtLancamento;
						$oDaoReciboUnicaGeracao->ar40_dtvencimento       = $dtVencimento;
						$oDaoReciboUnicaGeracao->ar40_percentualdesconto = $percentual;
						$oDaoReciboUnicaGeracao->ar40_tipogeracao        = $sTipoGeracao;
						$oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
						$oDaoReciboUnicaGeracao->ar40_observacao         = $sObservacao;
						$oDaoReciboUnicaGeracao->incluir(null);

		  		  		if($oDaoReciboUnicaGeracao->erro_status == 0) {
		  		    		throw new Exception($oDaoReciboUnicaGeracao->erro_msg);
		  		  		} else {
		  		    		foreach ($aNumpres as $iNumpre) {
			  		      		/**
			  		       	 	 * Incluindo dados na recibunica
			  		       		'*/
				    		    $oDaoReciboUnica->k00_numpre             = $iNumpre;
				    		    $oDaoReciboUnica->k00_dtvenc             = $dtVencimento;
				    		    $oDaoReciboUnica->k00_dtoper             = $dtLancamento;
				    		    $oDaoReciboUnica->k00_percdes            = $percentual;
				    		    $oDaoReciboUnica->k00_tipoger            = $sTipoGeracao;
				    		    $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
				    		    $oDaoReciboUnica->incluir(null);

			    		    	if ($oDaoReciboUnica->erro_status == 0) {
				    		      	throw new Exception($oDaoReciboUnica->erro_msg);
		    			    	}
		  			    	}
		  		  		}

		  		  		db_fim_transacao(false);

				  	} catch(Exception $eErroBanco) {
				    	db_fim_transacao(true);			  		
				    	throw new ErrorException("Erro no Base de Dados: \n"+ $eErroBanco->getMessage());					  	
            		}
            	}	            	           
		  	} else {
		    	$sTipoGeracao  = "I";		    			    	

			  	foreach ($aUnicas as $unica) {

			  		$unica = $oJson->decode(str_replace("\\","", $unica));

				  	$dtLancamento = implode("-",array_reverse(explode("/",$unica->lancamento)));
				  	$dtVencimento = implode("-",array_reverse(explode("/",$unica->vencimento)));
				  	$percentual   = $unica->desconto;
				  	$iNumpre	  = $unica->debito;

		      		$sObservacao = addslashes(base64_decode($unica->observacoes));         

		 		  	db_inicio_transacao();
				  	try {
						/**
						* inserindo dados da recibounica geração
						*/
						$oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
						$oDaoReciboUnicaGeracao->ar40_dtoperacao         = $dtLancamento;
						$oDaoReciboUnicaGeracao->ar40_dtvencimento       = $dtVencimento;
						$oDaoReciboUnicaGeracao->ar40_percentualdesconto = $percentual;
						$oDaoReciboUnicaGeracao->ar40_tipogeracao        = $sTipoGeracao;
						$oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
						$oDaoReciboUnicaGeracao->ar40_observacao         = $sObservacao;
						$oDaoReciboUnicaGeracao->incluir(null);

		  		  		if($oDaoReciboUnicaGeracao->erro_status == 0) {
		  		    		throw new Exception($oDaoReciboUnicaGeracao->erro_msg);
		  		  		} else {		  		    		
		  		      		/**
		  		       	 	 * Incluindo dados na recibunica
		  		       		'*/
			    		    $oDaoReciboUnica->k00_numpre             = $iNumpre;
			    		    $oDaoReciboUnica->k00_dtvenc             = $dtVencimento;
			    		    $oDaoReciboUnica->k00_dtoper             = $dtLancamento;
			    		    $oDaoReciboUnica->k00_percdes            = $percentual;
			    		    $oDaoReciboUnica->k00_tipoger            = $sTipoGeracao;
			    		    $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
			    		    $oDaoReciboUnica->incluir(null);

		    		    	if ($oDaoReciboUnica->erro_status == 0) {
			    		      	throw new Exception($oDaoReciboUnica->erro_msg);
	    			    	}		  			    	
		  		  		}

		  		  		db_fim_transacao(false);

				  	} catch(Exception $eErroBanco) {
				    	db_fim_transacao(true);			  		
				    	throw new ErrorException("Erro no Base de Dados: \n"+ $eErroBanco->getMessage());
				  	}
			  	}
		  	}
		  	
			$oRetorno->msg = $oDaoReciboUnicaGeracao->erro_msg;
			break;

		case "prorrogar":

			$iCodGeracao           = $oParam->iCodGeracao;
			$dtVencimento          = implode("-", array_reverse(explode("/",$oParam->dtVencimento)));
			$dtLancamento          = implode("-", array_reverse(explode("/",$oParam->dtLancamento)));
			$iPercDesconto         = $oParam->iPercDesconto;
			$sObs                  = $oParam->sObs;

			$oDaoReciboUnicaGeracao->ar40_sequencial         = $iCodGeracao;
			$oDaoReciboUnicaGeracao->ar40_dtvencimento       = $dtVencimento;
			$oDaoReciboUnicaGeracao->ar40_dtoperacao         = $dtLancamento;
			$oDaoReciboUnicaGeracao->ar40_percentualdesconto = $iPercDesconto;
			$oDaoReciboUnicaGeracao->ar40_observacao         = $sObs;
			$oDaoReciboUnicaGeracao->alterar($oDaoReciboUnicaGeracao->ar40_sequencial);

			if($oDaoReciboUnicaGeracao->erro_status == 0) {
				throw new ErrorException($oDaoReciboUnicaGeracao->erro_msg);
			}

			$sSqlAtualizaReciboUnica = $oDaoReciboUnica->sql_query_file(null, "k00_sequencial", null, "k00_recibounicageracao = {$iCodGeracao}");
      		$rsAtualizaReciboUnica   = $oDaoReciboUnica->sql_record($sSqlAtualizaReciboUnica);

      		if($oDaoReciboUnica->numrows > 0) {

	      		$aDadosAtualizaReciboUnica = db_utils::getCollectionByRecord($rsAtualizaReciboUnica);

      			foreach ($aDadosAtualizaReciboUnica as $iIndAtualiza => $oValorAtualiza) {

			      	$oDaoReciboUnica->k00_sequencial         = $oValorAtualiza->k00_sequencial;
	      			$oDaoReciboUnica->k00_dtvenc             = $dtVencimento;
			      	$oDaoReciboUnica->k00_dtoper             = $dtLancamento;
			      	$oDaoReciboUnica->k00_percdes            = $iPercDesconto;
			      	$oDaoReciboUnica->alterar($oDaoReciboUnica->k00_sequencial);

			      	if ($oDaoReciboUnica->erro_status == 0) {
			        	throw new Exception($oDaoReciboUnica->erro_msg);
			      	}
      			}

      		}
		  	
		  	$oRetorno->prorrogacao = "Processamento Realizado";
			break;

		default:
		  	throw new ErrorException("Nenhuma Opção Definida");
	  break;
	}

} catch (ErrorException $eErro) {

	$oRetorno->status = 2;
	$oRetorno->msg    = $eErro->getMessage();
}

$oRetorno->msg    = urlencode($oRetorno->msg);
$oRetorno->aDados = $aDadosRetorno;

echo $oJson->encode($oRetorno);