<?php
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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_folha_classe.php");
require_once("classes/db_selecao_classe.php");
require_once("classes/db_gerfsal_classe.php");
require_once("classes/db_gerfadi_classe.php");
require_once("classes/db_gerffer_classe.php");
require_once("classes/db_gerfres_classe.php");
require_once("classes/db_gerfs13_classe.php");
require_once("classes/db_gerfcom_classe.php");
require_once("classes/db_gerffx_classe.php");
require_once("classes/db_rhgeracaofolha_classe.php");
require_once("classes/db_rhgeracaofolhatipo_classe.php");
require_once("classes/db_rhgeracaofolhareg_classe.php");
require_once("classes/db_rhsuspensaopag_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("libs/db_sql.php");
require_once("fpdf151/pdf.php");

$clrhgeracaofolha     = new cl_rhgeracaofolha();
$clrhgeracaofolhatipo = new cl_rhgeracaofolhatipo();
$clrhgeracaofolhareg  = new cl_rhgeracaofolhareg();
$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = 1;
$oRetorno->message    = '';

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_rhgeracaofolha.');

try {

  switch ($oParam->exec) {

    case "getServidores":

      $sSqlServidores         = $clrhgeracaofolha->sqlGeracaoFolha($oParam);
    	$rsServidores           = $clrhgeracaofolha->sql_record($sSqlServidores);
      $aServidores            = db_utils::getCollectionByRecord($rsServidores, false, false, true);
      if (count($aServidores) == 0) {
      	throw new Exception( _M( MENSAGENS . 'sem_registros_encontrados' ) );
      }
      $oRetorno->aServidores  = $aServidores;

    break;

    case "geraFolha":

      $oDadosGeracaoFolha     = $oParam->oDados;
      $lExisteDadosComSaldo   = false;
      $sMsgGeracao            = "geracao_com_sucesso";
      $sNomeRelatorio         = "tmp/relatorio_inconsistencia_geracao_folha_disco_".date("Ymdhis").".pdf";
      
      $oPdf = new PDF();
      $oPdf->Open();
      $oPdf->AliasNbPages();
      
      $descricao_folha = "";
      switch ($oParam->oDados->folhaselecion) {
      	case "0": 
      		$descricao_folha = "Salário";
      	break;	
      	case "1":
      	  $descricao_folha = "Adiantamento";
      	break;
      	case "3":
      	  $descricao_folha = "Rescisão";
      	break;
      	case "4":
      	  $descricao_folha = "Saldo do 13o";
      	break;
      	case "5":
      	  $descricao_folha = "Complementar";
      	break;
        case "6":
          $descricao_folha = "Suplementar";
        break;
      }
      
      $head2 = "Relatório de Inconsistências da Geração em Disco";
      $head4 = "Ano/Mês: ".$oDadosGeracaoFolha->anofolha."/".$oDadosGeracaoFolha->mesfolha;
      $head5 = "Tipo de Folha: ".$descricao_folha;
      $head6 = "Descrição: ".$oDadosGeracaoFolha->rh102_descricao;
      
      $lCabecalhoRelatorio = true;
      $lRelatorio          = false;
      $lCor                = 1;
      $iTotalServidores    = 0;
      
      db_inicio_transacao();

      $aServidores = array();
      foreach ($oParam->aDadosServidores as $aServidorTipoFolha => $iServidorTipoFolha ) {

        list($iServidor) = explode('_',$iServidorTipoFolha);
        array_push( $aServidores, $iServidor );
      }

      $sSqlServidores    = $clrhgeracaofolha->sqlGeracaoFolha($oParam, $aServidores);
      $rsServidores      = $clrhgeracaofolha->sql_record($sSqlServidores);
      if ($clrhgeracaofolha->numrows == 0){
      	throw new Exception( _M( MENSAGENS . 'sem_registros_encontrados' ) );
      }
      $aServidores       = db_utils::getCollectionByRecord($rsServidores, false, false, true);
      
      $clrhgeracaofolha->rh102_descricao  = $oDadosGeracaoFolha->rh102_descricao;
      $clrhgeracaofolha->rh102_usuario    = db_getsession('DB_id_usuario');
      $clrhgeracaofolha->rh102_dtproc     = date('Y-m-d',db_getsession('DB_datausu'));;
      $clrhgeracaofolha->rh102_ativo      = 't';
      $clrhgeracaofolha->rh102_mesusu     = $oDadosGeracaoFolha->mesfolha;
      $clrhgeracaofolha->rh102_anousu     = $oDadosGeracaoFolha->anofolha;
      $clrhgeracaofolha->rh102_instit     = db_getsession('DB_instit');
      $clrhgeracaofolha->incluir("");
      if($clrhgeracaofolha->erro_status == "0"){
      	throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
      }

      foreach ($aServidores as $oDados) {

       /*
        * Se o saldo a receber for menor que o liquido e o valor recebido subtraido do
      	* liquido for uma diferença menor que 0,01 realizamos um ajuste devido a arredondamento
      	*
      	* O ajuste é subtrair a diferença do valor liquido que está sendo gerado.
      	*
      	* Este caso ocorre frequentemente quando é pago 50/50 do salario.
      	*/
      	if ( round($oDados->valor_recebido,2) > 0
      			&& ( round(($oDados->proven - $oDados->descon - $oDados->valor_recebido),2) < round($oDados->liquido,2) )
      			&& ( round((round($oDados->liquido,2) - round(($oDados->proven - $oDados->descon - $oDados->valor_recebido),2)),2) == 0.01 ) 
      		 ) {
      	
      		$oDados->liquido =  $oDados->liquido - 0.01;
      	
      	}
      	
      	/*
      	 * Adicionada Valiação dos servidores que possuem saldo a receber
      	*/
  	    if( round($oDados->liquido,2) > 0
  	    		&& (round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido),2)) > 0  
  	    		&& (round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido ),2) <= round(($oDados->proven - $oDados->descon),2))
  	    		&& (round($oDados->liquido,2) <= round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido ),2)) 
  	      ) {
  	    	
  	    	$lExisteDadosComSaldo = true;
  	      /**
  	       * Incluindo dado na tabela rhgeracaofolhareg
  	       */
  	    	 $clrhgeracaofolhareg->rh104_sequencial     = null;
  	       $clrhgeracaofolhareg->rh104_seqpes         = $oDados->rh02_seqpes;
  	       $clrhgeracaofolhareg->rh104_instit         = db_getsession('DB_instit');
  	       $clrhgeracaofolhareg->rh104_rhgeracaofolha = $clrhgeracaofolha->rh102_sequencial;
  	       $clrhgeracaofolhareg->rh104_vlrsalario     = $oDados->f010;
  	       $clrhgeracaofolhareg->rh104_vlrliquido     = $oDados->liquido;
  	       $clrhgeracaofolhareg->rh104_vlrprovento    = $oDados->proven;
  	       $clrhgeracaofolhareg->rh104_vlrdesconto    = $oDados->descon;
  	       $clrhgeracaofolhareg->incluir("");
  	       if ($clrhgeracaofolhareg->erro_status == "0") {
             throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
           }

           /**
            * Incluindo dados na tabela rhgeracaofolhareg
            */
           $clrhgeracaofolhatipo->rh103_sequencial        = null;
           $clrhgeracaofolhatipo->rh103_rhgeracaofolhareg = $clrhgeracaofolhareg->rh104_sequencial;
           $clrhgeracaofolhatipo->rh103_tipofolha         = $oDados->tipo_folha;

           if(isset($oDadosGeracaoFolha->complementares)){
             $iCodigoComplementar = $oDadosGeracaoFolha->complementares;
           } else {
             $iCodigoComplementar = "0";
           }
           $clrhgeracaofolhatipo->rh103_complementar      =  $iCodigoComplementar;
           $clrhgeracaofolhatipo->incluir("");
           if($clrhgeracaofolhatipo->erro_status == "0"){
             throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
  		     }
  		     
        } else {
        	
        	$lRelatorio  = true; 
        	$sMsgGeracao = "geracao_com_sucesso_com_inconsistencias";
        	
        	/*
        	 * Geramos o relatório com as inconsistencias.
        	 */
        	if ($oPdf->gety() > $oPdf->h - 30 || $lCabecalhoRelatorio) {
        		
        		$oPdf->AddPage();
        		$oPdf->SetTextColor(0,0,0);
        		$oPdf->SetFillColor(220);
        		$oPdf->SetFont('arial','B',8);
        		$oPdf->cell(25 ,4,"Matrícula"  ,1,0,"C",1);
            $oPdf->cell(105,4,"Nome"       ,1,0,"C",1);
            $oPdf->cell(30 ,4,"Valor Pago" ,1,0,"C",1);
            $oPdf->cell(30 ,4,"Saldo"      ,1,1,"C",1);
            
            $lCabecalhoRelatorio = false;
        	}
        	
        	$lCor = ($lCor == 1?"0":"1");
        	
        	$oPdf->setfont('arial','',8);
        	$oPdf->cell(25 ,4,$oDados->regist                                                                ,1,0,"C",$lCor);
          $oPdf->cell(105,4,urlDecode($oDados->z01_nome)                                                   ,1,0,"L",$lCor);
          $oPdf->cell(30 ,4,db_formatar($oDados->valor_recebido,'f')                                       ,1,0,"R",$lCor);
          $oPdf->cell(30 ,4,db_formatar(($oDados->proven - $oDados->descon - $oDados->valor_recebido),'f') ,1,1,"R",$lCor);
          
          $iTotalServidores++;
        }
        
      }
      
      if ($lExisteDadosComSaldo == false) {
      	throw new Exception( _M( MENSAGENS . 'sem_registros_com_saldo' ) );
      }
      
      if ($lRelatorio) {
      	$oPdf->setfont('arial','B',8);
      	$oPdf->cell(160,4,"Total de Servidores:",1,0,"R",1);
      	$oPdf->cell(30 ,4,$iTotalServidores     ,1,1,"R",1);
        $oPdf->Output($sNomeRelatorio, false, true);
        $oRetorno->relatorio_inconsistencias = $sNomeRelatorio;
      } else {
      	unset($oPdf);
      }
      
      $oRetorno->message = urlencode( _M ( MENSAGENS . $sMsgGeracao ) );
      db_fim_transacao(false);

    break;

    /**
     * Verifica existencia de lançamento escrituração de férias ou décimo terceiro
     */
    case "verificaExistenciaLancamentoFeriasDecimoTerceiro":

      $iAno                   = db_getsession("DB_anousu");
      $iMes                   = date("m", db_getsession("DB_datausu"));
      $iInstituicao           = db_getsession("DB_instit");
      $oDaoEscrituraProvisao  = db_utils::getDao('escrituraprovisao');

      $sWhere  = "     c102_instit = {$iInstituicao}";
      $sWhere .= " and c102_processado is true";
      $sWhere .= " and c102_ano = {$iAno} and c102_mes >= {$iMes}";

      $sSqlBuscaEscrituraProvisao = $oDaoEscrituraProvisao->sql_query_file(null, "*", null, $sWhere);
      $rsBuscaEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlBuscaEscrituraProvisao);

      if ($oDaoEscrituraProvisao->numrows > 0) {

        $oLancamento = db_utils::fieldsMemory($rsBuscaEscrituraProvisao, 0);
        $sTipo       = $oLancamento->c102_tipoprovisao == "2" ? "Férias" : "Décimo Terceiro";

        $oRetorno->status  = 2;
        $sMensagem         = "Existem lançamentos para {$sTipo}\n";
        $sMensagem        .= "Para executar novo processamento da rotina, os lançamentos da escrituração devem ser estornados";
        $oRetorno->message = urlencode($sMensagem);
      }
    break;

  }

} catch (Exception $oErro) {

	db_fim_transacao(true);

	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);