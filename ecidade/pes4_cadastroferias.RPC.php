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
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/JSON.php"));  

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));

require_once(modification("interfaces/ICalculoMediaRubrica.interface.php"));

db_app::import('exceptions.*');
db_app::import('pessoal.*');
db_app::import('CgmFactory');

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$oRetorno->lVoltar      = 'false';

try {

  switch ($oParam->sExec) {
    

    case "carregaDadosDefaultFormulario":

     /**
      * Busca configurações de férias
      */
     $iMesFolha = db_mesfolha();
     $iAnoFolha = db_anofolha(); 
     $oDaoCfpess      = db_utils::getDao('cfpess');
     $sSqlBuscaCfpess = $oDaoCfpess->sql_query_file($iAnoFolha, 
                                                    $iMesFolha, 
                                                    db_getsession('DB_instit'));
     $rsBuscaCfpess   = $oDaoCfpess->sql_record($sSqlBuscaCfpess);
     if ($oDaoCfpess->numrows == 0) {
       throw new DBException("Não encontrados parâmetros configurados para a competência ".db_mesfolha()."/".db_anofolha());  
     }
      
     $oCfpess         = db_utils::fieldsMemory($rsBuscaCfpess, 0);
     
     $oRetorno->lPagaTerco = $oCfpess->r11_13ferias;               
     $oRetorno->sTipoPonto = $oCfpess->r11_pagarferias;
     $oRetorno->iAnoFolha  = $iAnoFolha;
     $oRetorno->iMesFolha  = $iMesFolha;     

    break;
    
    case "buscaNomeMatricula":
    	
    	$oDaoRhPessoal = db_utils::getDao("rhpessoal");
    	$sSqlBuscaMatricula = $oDaoRhPessoal->sql_query($oParam->iMatricula, "z01_nome", null);
    	$rsBuscaMatricula = $oDaoRhPessoal->sql_record($sSqlBuscaMatricula);
    	if ($oDaoRhPessoal->numrows > 0) {
    		$oRetorno->sNomeMatricula = db_utils::fieldsMemory($rsBuscaMatricula, 0)->z01_nome; 
    	} else {
    		$oRetorno->sNomeMatricula = "Matrícula não encontrada";
    	}
    break;
     
    case "buscaDadosFeriasCadastro":
     
     /**
      * Busca dados das férias da Matricula
      */
  	 $oFerias = new Ferias();
  	 $oFerias->setMatricula($oParam->iMatricula);
     
     if ($oFerias->verificaRescisao()) {
  	   
     	 $oRetorno->lVoltar = true;
  	   throw new Exception('Funcionario possui rescisão');
  	 }
  	 
  	 $oFerias->geraPeriodoAquisitivo();
  	 $dDataPeriodoAquisitivoInicial = $oFerias->getPeriodoAquisitivoInicial();
  	 $dDataPeriodoAquisitivoFinal   = $oFerias->getPeriodoAquisitivoFinal();
     
 	   $oRetorno->iDiasDireito = $oFerias->verificaSaldoDias($oParam->iMatricula, 
 	                                                         $dDataPeriodoAquisitivoInicial, 
 	                                                         $dDataPeriodoAquisitivoFinal);  
 	 
  	 $oRetorno->dPeriodoAquisitivoInicial = implode("/", array_reverse(explode("-", $dDataPeriodoAquisitivoInicial))); 
  	 $oRetorno->dPeriodoAquisitivoFinal   = implode("/", array_reverse(explode("-", $dDataPeriodoAquisitivoFinal)));
  	 
  	 /**
      * Verifica se deve usar faltas para o calculo de dias de direito a gozar
      */
     $oRetorno->lUsarFaltas = $oFerias->usarFaltas() ? 'true' : 'false';
  	 
  	break;  
    
  	case "buscaDadosFeriasExclusao" :  	
  		
  		$oFerias = new Ferias();
			$oFerias->setMatricula($oParam->iMatricula);
			
			/**
       * Verifica se existe férias cadastrada para a competencia
       */
			$oFeriasMatricula = $oFerias->verificaFeriasMatricula();
  		if ($oFeriasMatricula) {

  		  $oDadosRetorno = new stdClass();
  		  
  		  foreach ($oFeriasMatricula as $iInd => $oDadosFerias) {
  		    
  		 	   $oDadosRetorno->iCodigoFerias                   = $oDadosFerias->getCodigoFerias();
  		 	   $oDadosRetorno->dPeriodoAquisitivoInicial       = implode("/", array_reverse(explode("-",$oDadosFerias->getPeriodoAquisitivoInicial() )));
  		 	   $oDadosRetorno->dPeriodoAquisitivoFinal         = implode("/", array_reverse(explode("-",$oDadosFerias->getPeriodoAquisitivoFinal()   )));
  		 	   $oDadosRetorno->dPeriodoEspecificoInicial       = implode("/", array_reverse(explode("-",$oDadosFerias->getPeriodoEspecificoInicial() )));
  		 	   $oDadosRetorno->dPeriodoEspecificoFinal         = implode("/", array_reverse(explode("-",$oDadosFerias->getPeriodoEspecificoFinal()   )));  		 	   
  		     $oDadosRetorno->nDias                           = $oDadosFerias->getDiasDireito();
  		     $oDadosRetorno->nFaltasPeriodoAquisitivo        = $oDadosFerias->getFaltasPeriodoAquisitivo();
  		     $aPeriodos                                      = $oDadosFerias->getPeriodoGozo(null, date("Y",db_getsession("DB_datausu")), date("m",db_getsession("DB_datausu")));
  		     foreach ($aPeriodos as $iInd => $oPeriodoGozo) {
  		       
  		       
  		       $oPeriodos = new stdClass();
  		       $oPeriodos->sObservacao         = urlencode($oPeriodoGozo->sObservacao);
  		       $oPeriodos->iAnoPagamento       = $oPeriodoGozo->iAnoPagamento;
  		       $oPeriodos->iMesPagamento       = $oPeriodoGozo->iMesPagamento;
  		       $oPeriodos->dPeriodoInicial     = $oPeriodoGozo->dPeriodoInicial; 
  		       $oPeriodos->dPeriodoFinal       = $oPeriodoGozo->dPeriodoFinal;  
  		       $oPeriodos->iDiasGozo           = $oPeriodoGozo->iDiasGozo; 
  		       $oPeriodos->iDiasAbono          = $oPeriodoGozo->iDiasAbono;
  		       
  		       $oDadosRetorno->aPeriodosGozo[] = $oPeriodos;
  		       
  		     }
  		     
  		  }
  		  $oRetorno->oDadosFerias = $oDadosRetorno;
      } else if (isset($oParam->lExclusao)) {
         
        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = "Não encontradas férias cadastradas";
      }

  	break;
  	
  	case "verificaDireitoFerias":

  	  $oFerias  = new Ferias();
  	  $oFerias->setMatricula($oParam->iMatricula);
  	  $lDireito = $oFerias->verificaDireitoFerias($oParam->dPeriodoAquisitivoInicial, $oParam->dPeriodoAquisitivoFinal);
  	  $iDiasDireito = $oFerias->verificaSaldoDias($oParam->iMatricula, $oParam->dPeriodoAquisitivoInicial, $oParam->dPeriodoAquisitivoFinal);
  	  
  	  $oRetorno->lDireito     = $lDireito;
  	  $oRetorno->iDiasDireito = $iDiasDireito;
  	  if($lDireito == false) {
  	  	
  	    $oRetorno->iStatus  = 2;
  	    $oRetorno->sMessage = "Servidor perdeu direito a férias neste período!"; 
  	    $oRetorno->lVoltar  = true;
  	  }
  	  
  	break;  
  	
  	case "verificaPeriodoGozo":
  	  
  	  $oDaoRhFeriasPeriodo = db_utils::getDao("rhferiasperiodo");
  	  
  	  $sWhere  = "rh109_regist = {$oParam->iMatricula} and";
  	  $sWhere .= " ( ('{$oParam->dDataPeriodoInicial}' between rh110_datainicial and rh110_datafinal) or ";
  	  $sWhere .= "   ('{$oParam->dDataPeriodoFinal}'   between rh110_datainicial and rh110_datafinal) ) ";
  	  $oDaoRhFeriasPeriodo->sql_record($oDaoRhFeriasPeriodo->sql_query(null, "*", null, $sWhere));
  	  if ($oDaoRhFeriasPeriodo->numrows > 0) {
  	    $oRetorno->iStatus  = 2;
  	    $oRetorno->sMessage = "Período de gozo informado está dentro de um período de gozo já existente para o Servidor"; 
  	  }
  	  

  	break;
  	
    /**
     * ---------------------------------------------------------------------------
     * Salvar ferias 
     * ---------------------------------------------------------------------------
     */
    case "salvarFerias":

      db_inicio_transacao();

      /*
       * Verificar se as férias cadastradas possuem saldo, isto é, se a soma dos dias dos períodos das férias é menor que
       * os dias das férias.
       * Se possuir saldo de férias, lançar o período nas férias cadastradas anteriores e o restante do saldo lançar no novo período da nova férias cadastrada
       * Se não possuir saldo, apenas cadastrar, isto é, lançar um cadastro de férias novo.
       * 
       */
      $oFerias = new Ferias();
      $oFerias->setMatricula($oParam->iMatricula);
      
      /*
       * Verificamos se o servidor possui férias cadastradas
       */
      $aFeriasAtivas = $oFerias->verificaFeriasMatricula($oParam->iMatricula, 
                                                         $oParam->dPeriodoAquisitivoInicial, 
                                                         $oParam->dPeriodoAquisitivoFinal);
          
      $iDiasPeriodo = $oParam->dadosPeriodo->iDiasGozar;
      if ($aFeriasAtivas) {
        
        $iDiasGozo    = 0;
        foreach ($aFeriasAtivas as $iInd => $oDadosFerias) {
        
          //Se está sendo cadatrado uma perda de direito de férias, verificamos se o usuário possui férias em aberto
          if ($oParam->lDireitoFerias == "N") {
             
            if ( ($oDadosFerias->getDiasAbonados() + $oDadosFerias->getDiasGozados() ) > 0 ) {
              throw new BusinessException("Não é possível lançar a perda do direiro de férias pois o servidor possui férias pagas para o mesmo período");      
            }
         
          } else { 
        
            if ($iDiasPeriodo == 0) {
              break;
            }
            /*
             * Caso ainda existam perídos em aberto paras férias
             * Será cadastrado um novo período verificando o saldo de dias restantes nas férias e os dias informados para o período 
             */
             $iDiasSaldoFerias = $oDadosFerias->verificaSaldoDias($oParam->iMatricula, $oParam->dPeriodoAquisitivoInicial, $oParam->dPeriodoAquisitivoFinal);
            if ($iDiasSaldoFerias > 0) {
               
              if ($iDiasSaldoFerias < $iDiasPeriodo) {
                $iDiasGozo    = $iDiasSaldoFerias;
                $iDiasPeriodo = $iDiasPeriodo - $iDiasGozo;              
              } else if ($iDiasSaldoFerias >= $iDiasPeriodo) {
                $iDiasGozo    = $iDiasPeriodo;
                $iDiasPeriodo = 0;
              }
               
              $oFeriasPeriodo = new FeriasPeriodo();
              $oFeriasPeriodo->setDiasGozo      ($iDiasGozo);
              $oFeriasPeriodo->setCodigoFerias  ($oDadosFerias->getCodigoFerias());
              $oFeriasPeriodo->setPeriodoInicial($oParam->dadosPeriodo->dDataInicial);
              $oFeriasPeriodo->setPeriodoFinal  ($oParam->dadosPeriodo->dDataFinal);
              $oFeriasPeriodo->setObservacao    (db_stdClass::normalizeStringJson($oParam->dadosPeriodo->sObservacao));
              $oFeriasPeriodo->setAnoPagamento  ($oParam->dadosPeriodo->iAnoPagamento);
              $oFeriasPeriodo->setMesPagamento  ($oParam->dadosPeriodo->iMesPagamento);
              $oFeriasPeriodo->setDiasAbono     ($oParam->dadosPeriodo->iDiasAbono);
              $oFeriasPeriodo->setPagaTerco     ($oParam->dadosPeriodo->lTerco);
              
              if ($oParam->dadosPeriodo->sTipoPonto=="S" ) {
               $oFeriasPeriodo->setTipoPonto("1");
              } else if ($oParam->dadosPeriodo->sTipoPonto=="C") {
                $oFeriasPeriodo->setTipoPonto("2");
              } else {
                $oFeriasPeriodo->setTipoPonto("0");
              }
  
              $oFerias->setCodigoFerias($oDadosFerias->getCodigoFerias());
              $oFerias->addPeriodoGozo($oFeriasPeriodo); 
              $oFerias->salvarPeriodos();        
             }
          }
       }
        
      } else {
        
         $oFerias->setPeriodoAquisitivoInicial(implode("-", array_reverse(explode("/", $oParam->dPeriodoAquisitivoInicial) )));
         $oFerias->setPeriodoAquisitivoFinal  (implode("-", array_reverse(explode("/", $oParam->dPeriodoAquisitivoFinal) )));
         $oFerias->setPeriodoEspecificoInicial(implode("-", array_reverse(explode("/", $oParam->dPeriodoEspecificoInicial) )));
         $oFerias->setPeriodoEspecificoFinal  (implode("-", array_reverse(explode("/", $oParam->dPeriodoEspecificoFinal) )));      
         $oFerias->setDiasDireito             ($oParam->iDiasDireito);
         $oFerias->setFaltasPeriodoAquisitivo ($oParam->iFaltasPeriodo);
       
         /**
          * Verifica se o $oParam->lDireitoFerias == N não gravaremos periodo
          * somente rhferias e com os dias de direito zerado
          */
         if ($oParam->lDireitoFerias == "N") {
           $oFerias->setDiasDireito('0');        
         }        
       
         $salvarFerias = $oFerias->salvarFerias();

         $oFeriasPeriodo = new FeriasPeriodo();
          
         $oFeriasPeriodo->setDiasGozo      ($oParam->dadosPeriodo->iDiasGozar);
         $oFeriasPeriodo->setCodigoFerias  ($oFerias->getCodigoFerias());
         $oFeriasPeriodo->setPeriodoInicial($oParam->dadosPeriodo->dDataInicial);
         $oFeriasPeriodo->setPeriodoFinal  ($oParam->dadosPeriodo->dDataFinal);
         $oFeriasPeriodo->setObservacao    (db_stdClass::normalizeStringJson($oParam->dadosPeriodo->sObservacao));
         $oFeriasPeriodo->setAnoPagamento  ($oParam->dadosPeriodo->iAnoPagamento);
         $oFeriasPeriodo->setMesPagamento  ($oParam->dadosPeriodo->iMesPagamento);
         $oFeriasPeriodo->setDiasAbono     ($oParam->dadosPeriodo->iDiasAbono);
         $oFeriasPeriodo->setPagaTerco     ($oParam->dadosPeriodo->lTerco);
         
         if ($oParam->dadosPeriodo->sTipoPonto=="S" ) {
           $oFeriasPeriodo->setTipoPonto("1");
         } else if ($oParam->dadosPeriodo->sTipoPonto=="C") {
           $oFeriasPeriodo->setTipoPonto("2");
         } else {
           $oFeriasPeriodo->setTipoPonto("0");
         }
         
         $oFerias->setCodigoFerias($oFerias->getCodigoFerias());
         $oFerias->addPeriodoGozo($oFeriasPeriodo);        
         $oFerias->salvarPeriodos();
      }

       /**
        * Mensagem de cadastro efetuado com sucesso
        */
       $oRetorno->sMessage = "Férias Cadastrada com sucesso.";

       db_fim_transacao(false);      

     break;
    
     case "salvarFeriasEmLote":
     	
     	/**
     	 * Buscamos as matrículas referentes à seleção.
     	 */
     	$oDaoSelecao      = db_utils::getDao('selecao');
     	$sSqlBuscaSelecao = $oDaoSelecao->sql_query_file($oParam->iSelecao, db_getsession('DB_instit'), 
     	                                                 "*", null, null);
     	$rsBuscaSelecao   = $oDaoSelecao->sql_record($sSqlBuscaSelecao);
     	if ($oDaoSelecao->numrows > 0) {
     		
     	  $oSelecao              = db_utils::fieldsMemory($rsBuscaSelecao, 0);
     		$oDaoFerias            = db_utils::getDao('rhferias');
     		$sWhereBuscaMatriculas = "";
     		if ($oParam->sPeriodosAquisitivosVencidosAte != '') {
     		  
     			$aMatriculas            = funcionarioferiasvencidas($oParam->sPeriodosAquisitivosVencidosAte);
     			$aListaMatriculas       = array();
     			foreach ($aMatriculas as $oMatricula) {
     			  $aListaMatriculas[] = $oMatricula->matricula;
     			}
     		  $sListaMatriculas       = implode(',', $aListaMatriculas);
     		  $sWhereBuscaMatriculas .= " and rh01_regist in ({$sListaMatriculas}) ";
     		}
     		if ($oParam->iFeriasProcessadas == '1') {
     		  $sWhereBuscaMatriculas .= " and exists ";
     		} else {
     			$sWhereBuscaMatriculas .= "and not exists ";
     		}
     		$sWhereBuscaMatriculas .= "(select 1                                  "; 
     		$sWhereBuscaMatriculas .= "   from rhcadastroferiaslote               "; 
     		$sWhereBuscaMatriculas .= "  where rh93_mesusu = {$oParam->iMesFolha} ";
     		$sWhereBuscaMatriculas .= "    and rh93_anousu = {$oParam->iAnoFolha} ";
     		$sWhereBuscaMatriculas .= "    and rh93_regist = rh01_regist)         ";
     	  $sSqlBuscaMatriculas    = $oDaoFerias->sql_query_busca_matriculas_selecao(db_anofolha(), db_mesfolha(), 
     	                                                                            " distinct rh01_regist, z01_nome ", 
     	                                                                            $oSelecao->r44_where.
     	                                                                            $sWhereBuscaMatriculas);
     	  $rsBuscaMatriculas   = $oDaoFerias->sql_record($sSqlBuscaMatriculas);
     	  $oMatriculasSelecao  = db_utils::getCollectionByRecord($rsBuscaMatriculas);
     	  /**
     	   * Verificamos se o tipo de processamento solicitado pelo usuário é 'Com confirmação'.
     	   * Em caso positivo definimos o comportamento adequado. 
     	   */
     	  if ($oParam->iTipoProcessamento == '1') {
     	  	
     	  	if (count($oMatriculasSelecao) > 0) {
	       		
     	  		$_SESSION['aListaMatriculasProcessamentoEmLote'] = array();
     	  		foreach ($oMatriculasSelecao as $oMatricula) {
	       			$_SESSION['aListaMatriculasProcessamentoEmLote'][] = $oMatricula->rh01_regist;
	       	  }
	       	  
	       	  /**
	       	   * O status 3 indica que o processo foi efetuado corretamente e que o array das matriculas
	       	   * está armazenado na sessão.
	       	   */
	       	  $oRetorno->iStatus   = 3;
	       	  $oRetorno->sMessage  = "A lista de matrículas está na variável de ";
	       	  $oRetorno->sMessage .= "sessão 'aListaMatriculasProcessamentoEmLote'.";
     	    } else {
     	    	
     	    	$oRetorno->iStatus = 2;
     	    	$oRetorno->sMessage = "A seleção solicitada não possui matrículas. Favor verificar.";
     	    }
     	  /**
     	   * Verificamos se o tipo de processamento selecionado pelo usuário é 'Sem confirmação'.
     	   * Em caso positivo definimos o comportamento adequado.
     	   */
     	  } else if ($oParam->iTipoProcessamento == '2') {
     	  	
     	  	$_SESSION['inconsistencias_cadastroferiaslote'] = array();
     	  	if (count($oMatriculasSelecao) > 0) {
     	  	
     	  		db_inicio_transacao();
     	  		
     	  		foreach ($oMatriculasSelecao as $oMatricula) {
     	  			
     	  			$oFerias = new Ferias();
     	  			$oFerias->setMatricula($oMatricula->rh01_regist);
     	  			if ($oFerias->verificaRescisao()) {
     	  			
     	  				$oErro = new stdClass();
     	  				$oErro->regist = $oMatricula->rh01_regist;
     	  				$oErro->nome   = $oMatricula->z01_nome;
     	  				$oErro->erro   = "1 - Servidor possui rescisão.";
     	  				$_SESSION['inconsistencias_cadastroferiaslote'][] = $oErro;
     	  				continue;
     	  			}
     	  			
     	  			$oFerias->geraPeriodoAquisitivo();
     	  			$dDataPeriodoAquisitivoInicial = $oFerias->getPeriodoAquisitivoInicial();
     	  			$dDataPeriodoAquisitivoFinal   = $oFerias->getPeriodoAquisitivoFinal();
     	  			 
     	  			$iDiasDireito = $oFerias->verificaSaldoDias($oMatricula->rh01_regist,
     	  			                                            $dDataPeriodoAquisitivoInicial,
     	  			                                            $dDataPeriodoAquisitivoFinal);
     	  			if ($iDiasDireito < 30) {
     	  				
     	  				$oErro = new stdClass();
     	  				$oErro->regist = $oMatricula->rh01_regist;
     	  				$oErro->nome   = $oMatricula->z01_nome;
     	  				
     	  				$sMsg  = "2 - Servidor possui férias pendentes. ";
     	  				$sMsg .= "Período aquisitivo de ".db_formatar($dDataPeriodoAquisitivoInicial, "d")." à ".db_formatar($dDataPeriodoAquisitivoFinal, "d"); 
     	  				$oErro->erro   = $sMsg;
     	  				
     	  				$_SESSION['inconsistencias_cadastroferiaslote'][] = $oErro;
     	  				continue;
     	  			}
     	  			
     	  			
     	  			$oFerias->setPeriodoAquisitivoInicial(implode("-", array_reverse(explode("/", $dDataPeriodoAquisitivoInicial))));
     	  			$oFerias->setPeriodoAquisitivoFinal  (implode("-", array_reverse(explode("/", $dDataPeriodoAquisitivoFinal))));
     	  			$oFerias->setPeriodoEspecificoInicial(implode("-", array_reverse(explode("/", $oParam->sPeriodoEspecificoInicial))));
     	  			$oFerias->setPeriodoEspecificoFinal  (implode("-", array_reverse(explode("/", $oParam->sPeriodoEspecificoFinal))));
     	  			$oFerias->setDiasDireito             ($iDiasDireito);
     	  			$oFerias->setFaltasPeriodoAquisitivo  = 0;
     	  			 
     	  			$salvarFerias = $oFerias->salvarFerias();
     	  			
     	  			$oFeriasPeriodo = new FeriasPeriodo();
     	  			
     	  			$oFeriasPeriodo->setDiasGozo      ($oParam->iDiasGozo);
     	  			$oFeriasPeriodo->setCodigoFerias  ($oFerias->getCodigoFerias());
     	  			$oFeriasPeriodo->setPeriodoInicial(db_formatar($oParam->sDataInicialFerias, 'd'));
     	  			$oFeriasPeriodo->setPeriodoFinal  ($oParam->sDataFinalFerias);
     	  			$oFeriasPeriodo->setObservacao    (db_stdClass::normalizeStringJson($oParam->sObservacoes));
     	  			$oFeriasPeriodo->setAnoPagamento  ($oParam->iAnoPagamento);
     	  			$oFeriasPeriodo->setMesPagamento  ($oParam->iMesPagamento);
     	  			$oFeriasPeriodo->setDiasAbono     ("0");
     	  			$oFeriasPeriodo->setPagaTerco     ($oParam->lPagaTerco);
     	  			$oFeriasPeriodo->setTipoPonto     (($oParam->sTipoPonto=="S"?1:2));
     	  			
     	  			$oFerias->setCodigoFerias($oFerias->getCodigoFerias());
     	  			$oFerias->addPeriodoGozo($oFeriasPeriodo);
     	  			$oFerias->salvarPeriodos();
     	  		}
     	  		
     	  		if (count($_SESSION['inconsistencias_cadastroferiaslote']) > 0) {
     	  			
     	  			$oRetorno->iStatus  = 4;
     	  			$oRetorno->sMessage = "Houveram inconsistencias no processamento. A variável está na sessão.";
     	  		} else {
     	  			unset($_SESSION['inconsistencias_cadastroferiaslote']);
     	  			$oRetorno->iStatus  = 1;
     	  		}
     	  		
     	  		db_fim_transacao(false);
     	  		
     	  	}
     	  }
     	} else {
     		$oRetorno->iStatus = 2;
     		$oRetorno->sMessage = "A seleção solicitada não possui matrículas. Favor verificar.";
     	}
     break;
    
    case "excluirFerias":
      
      db_inicio_transacao();
      
       $oFerias            = new Ferias($oParam->iCodigoFerias);
       $oRetorno->sMessage = $oFerias->excluirFerias();
       
      db_fim_transacao(false);
      
    break;

    case "excluirFeriasLote":
      
      db_inicio_transacao();
      
      $oDaoRhCadastroFeriasLote = db_utils::getDao("rhcadastroferiaslote");
      $oFerias                  = new Ferias();
      $oFeriasSelecao           = $oFerias->verificaFeriasCompetenciaSelecao($oParam->iCodigoSelecao);
      if (count($oFeriasSelecao) > 0) {

        foreach ($oFeriasSelecao as $oFeriasExcluir) {

           $oFeriasExclusao = new Ferias($oFeriasExcluir->rh109_sequencial) ;
           $oFeriasExclusao->excluirFerias();
           
           $sWhere  = "     rh93_regist = {$oFeriasExcluir->rh109_regist} ";
           $oDaoRhCadastroFeriasLote->excluir(null, $sWhere);
           if($oDaoRhCadastroFeriasLote->erro_status == "0") {
             throw new DBException("Erro excluindo registros da tabela rhcadastroferiaslote. Msg: ".$oDaoRhCadastroFeriasLote->erro_msg);
           }
           
           unset($oFeriasExclusao);

        }
      
        $oRetorno->sMessage = "Processamento realizado com Sucesso";
        
      } else {
        $oRetorno->sMessage = "Nenhum registro de férias encontrado para a seleção informada";
      }
     
      db_fim_transacao(false);
      
    break;  
    
    case "limparSessaoCadastroEmLote" :
    	
    	if (isset($_SESSION['aListaMatriculasProcessamentoEmLote'])) {
    		unset($_SESSION['aListaMatriculasProcessamentoEmLote']);
    	}
    break;
    
    case "consultaFeriasMatricula" :
      
      $oFerias = new Ferias();
      
      $oFerias->setMatricula($oParam->iMatricula);
      
      $oRetorno->aFerias        = array();
      
      if ($aFerias = $oFerias->verificaFeriasMatricula()) {

        foreach($aFerias as $oRhFerias) {

          $oFerias = new stdClass();
          
          $oFerias->dPeriodoAquisitivoInicial = "{$oRhFerias->getPeriodoAquisitivoInicial()}";
          $oFerias->dPeriodoAquisitivoFinal   = "{$oRhFerias->getPeriodoAquisitivoFinal()}";
          $oFerias->dPeriodoEspecificoInicial = "{$oRhFerias->getPeriodoEspecificoInicial()}";  
          $oFerias->dPeriodoEspecificoFinal   = "{$oRhFerias->getPeriodoEspecificoFinal()}";
          $oFerias->iDiasDireito              = "{$oRhFerias->getDiasDireito()}";
          $oFerias->iFaltas                   = "{$oRhFerias->getFaltasPeriodoAquisitivo()}";
          $oFerias->aPeriodos = array();
          
          foreach($oRhFerias->getPeriodoGozo() as $oRhFeriasPeriodo)  {
            
            
            $oFeriasPeriodo = new stdClass();
            
            $oFeriasPeriodo->iDiasAbono      =           "{$oRhFeriasPeriodo->getDiasAbono()}";
            $oFeriasPeriodo->dPeriodoInicial =           "{$oRhFeriasPeriodo->getPeriodoInicial()}";
            $oFeriasPeriodo->dPeriodoFinal   =           "{$oRhFeriasPeriodo->getPeriodoFinal()}";
            $oFeriasPeriodo->iAnoPagamento   =           "{$oRhFeriasPeriodo->getAnoPagamento()}";
            $oFeriasPeriodo->iMesPagamento   =           "{$oRhFeriasPeriodo->getMesPagamento()}";
            $oFeriasPeriodo->iDiasGozo       =           "{$oRhFeriasPeriodo->getDiasGozo()}";
            $oFeriasPeriodo->iTipoPonto      =           "{$oRhFeriasPeriodo->getTipoPonto()}";
            $oFeriasPeriodo->sObservacao     = urlencode("{$oRhFeriasPeriodo->getObservacao()}");
            
            
            if ($oRhFeriasPeriodo->getTipoPonto() == 1) {
              $oFeriasPeriodo->sTipoPonto = 'Sal&aacute;rio';
            } else if ($oRhFeriasPeriodo->getTipoPonto() == 2) {
              $oFeriasPeriodo->sTipoPonto = 'Complementar';
            } else {
              $oFeriasPeriodo->sTipoPonto = ' - ';
            }
            
            $oFerias->aPeriodos[] = $oFeriasPeriodo;
                        
          }
          $oRetorno->aFerias[] = $oFerias;
          
        }
        
      } else {
        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = "Nenhum registro de férias encontrado para a matrícula {$oParam->iMatricula}";
      }
      break;
    
    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (DBException $eErro){          // DB Exception
   
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
  
} catch (BusinessException $eErro){     // Business Exception
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
  
} catch (ParameterException $eErro){     // Parameter Exception
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>