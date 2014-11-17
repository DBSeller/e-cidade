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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$aDadosRetorno          = array();

try {

  switch ($oParam->exec) {

  	case 'getDetalhes' :

  		$iMovimento = $oParam->iMovimento;
  		$aDetalhes  = array();
  		$oDaoEmpAgeMovDetalheTransmissao = db_utils::getDao("empagemovdetalhetransmissao");
  		$sSqlDetalhes = $oDaoEmpAgeMovDetalheTransmissao->sql_query_file (null, "*", null, "e74_empagemov = {$iMovimento}");
  		$rsDetalhes   = $oDaoEmpAgeMovDetalheTransmissao->sql_record($sSqlDetalhes);
  		if ($oDaoEmpAgeMovDetalheTransmissao->numrows > 0) {

  		  for ($iDetahes = 0; $iDetahes < $oDaoEmpAgeMovDetalheTransmissao->numrows; $iDetahes++) {

  		  	$oValorDetalhe = db_utils::fieldsMemory($rsDetalhes, $iDetahes);
  		  	$oDadosDetalhe = new stdClass();
  		  	$sFatura       = "Fatura";
  		  	if ($oValorDetalhe->e74_tipofatura == 2) {
  		  		$sFatura = "Convênio";
  		  	}
  		  	$oDadosDetalhe->e74_sequencial     = $oValorDetalhe->e74_sequencial    ;
  		  	$oDadosDetalhe->e74_empagemov      = $oValorDetalhe->e74_empagemov     ;
  		  	$oDadosDetalhe->e74_codigodebarra  = $oValorDetalhe->e74_codigodebarra ;
  		  	$oDadosDetalhe->e74_valornominal   = $oValorDetalhe->e74_valornominal  ;
  		  	$oDadosDetalhe->e74_datavencimento = $oValorDetalhe->e74_datavencimento;
  		  	$oDadosDetalhe->e74_valorjuros     = $oValorDetalhe->e74_valorjuros    ;
  		  	$oDadosDetalhe->e74_valordesconto  = $oValorDetalhe->e74_valordesconto ;
  		  	$oDadosDetalhe->sFatura            = urlencode($sFatura);
  		  	$aDetalhes[] = $oDadosDetalhe;
  		  }
  		}
  		$oRetorno->aDados = $aDetalhes;

  	break;

  	case "getTipoTransmissao" :

  		$aTiposTransmissao            = array();
  		$iCodigoMovimento             = $oParam->iMovimento;
  		$oDaoEmpAgeTipoTransmissao    = db_utils::getDao("empagetipotransmissao");
  		$oDaoEmpAgeMovTipoTransmissao = db_utils::getDao("empagemovtipotransmissao");

  		// verificamos o tipo atual cadastrado para o movimento.
  		$sSqlTipoTra    = $oDaoEmpAgeMovTipoTransmissao->sql_query(null,"e57_sequencial, e57_descricao", null,"e25_empagemov = {$iCodigoMovimento}");
  		$rsTipoTraAtual = $oDaoEmpAgeMovTipoTransmissao->sql_record($sSqlTipoTra);
  		if ($oDaoEmpAgeMovTipoTransmissao->numrows == 0) {
  			throw new BusinessException('ERRO [ 0 ] - Movimento sem vínculo com tipo de transmissão.');
  		}

  		$oDadosTipoCadastrado    = db_utils::fieldsMemory($rsTipoTraAtual, 0);
  		$iTipoAtual              = $oDadosTipoCadastrado->e57_sequencial;
  		$oValoresTipoTransmissao = new stdClass();
  		$oValoresTipoTransmissao->e57_sequencial = $oDadosTipoCadastrado->e57_sequencial;
  		$oValoresTipoTransmissao->e57_descricao  = urlencode($oDadosTipoCadastrado->e57_descricao);
  		$aTiposTransmissao[0] = $oValoresTipoTransmissao;

  		$sSqlTiposTransmissao = $oDaoEmpAgeTipoTransmissao->sql_query_file(null, "e57_sequencial, e57_descricao", 1, "e57_sequencial <> {$iTipoAtual}");
  		$rsTiposTransmissao   = $oDaoEmpAgeTipoTransmissao->sql_record($sSqlTiposTransmissao);
  		if ($oDaoEmpAgeTipoTransmissao->numrows > 0) {

  		  for ($iTipoTra = 0; $iTipoTra < $oDaoEmpAgeTipoTransmissao->numrows; $iTipoTra++) {

  		  	$oDadosTipoTra = db_utils::fieldsMemory($rsTiposTransmissao, $iTipoTra);
  		  	$oValoresTipoTransmissao = new stdClass();
  		  	$oValoresTipoTransmissao->e57_sequencial = $oDadosTipoTra->e57_sequencial;
  		  	$oValoresTipoTransmissao->e57_descricao  = urlencode($oDadosTipoTra->e57_descricao);
  		  	$aTiposTransmissao[] = $oValoresTipoTransmissao;
  		  }
  		}
  		$oRetorno->aDados = $aTiposTransmissao;
  		$oRetorno->iCodigoRecurso = $oParam->iCodigoRecurso;

  	break;


  	case "salvarDetalhes":

  		$iMovimento       = $oParam->iMovimento;
  		$aDetalhes        = $oParam->aDetalhes;
  		$iTipoTransmissao = $oParam->iTipoTransmissao;

  		db_inicio_transacao();

  		$oDaoEmpAgeMovTipoTransmissao = db_utils::getDao('empagemovtipotransmissao');
  		$sSqlEmpAgeMovTipoTransmissao = $oDaoEmpAgeMovTipoTransmissao->sql_query_file (null, "*", null, "e25_empagemov = {$iMovimento}");
  		$rsEmpAgeMovTipoTransmissao   = $oDaoEmpAgeMovTipoTransmissao->sql_record($sSqlEmpAgeMovTipoTransmissao);
  		if ($oDaoEmpAgeMovTipoTransmissao->numrows == 0) {
  			throw new BusinessException("ERRO [ 0 ] - Não existe vinculo do Movimento com Tipo de Transmissão.");
  		}
  		$oDadosEmpAgeMovTipoTransmissao = db_utils::fieldsMemory($rsEmpAgeMovTipoTransmissao, 0);
  		$oDaoEmpAgeMovTipoTransmissao->e25_sequencial            = $oDadosEmpAgeMovTipoTransmissao->e25_sequencial;
  		$oDaoEmpAgeMovTipoTransmissao->e25_empagemov             = $oDadosEmpAgeMovTipoTransmissao->e25_empagemov;
  		$oDaoEmpAgeMovTipoTransmissao->e25_empagetipotransmissao = $iTipoTransmissao;
  		$oDaoEmpAgeMovTipoTransmissao->alterar($oDaoEmpAgeMovTipoTransmissao->e25_sequencial);
  		if ($oDaoEmpAgeMovTipoTransmissao->erro_status == 0) {
  			throw new DBException("ERRO [ 1 ] - Alterando vínculo do Movimento com Tipo de Transmissão " . $oDaoEmpAgeMovTipoTransmissao->erro_msg);
  		}

  		/*
  		 * Desvinculamos os detalhes de codigos de barra
  		 */
      $oDaoEmpAgeMovDetalheTransmissao = db_utils::getDao("empagemovdetalhetransmissao");
      $oDaoEmpAgeMovDetalheTransmissao->excluir(null,"e74_empagemov = {$iMovimento}");
      if ($oDaoEmpAgeMovDetalheTransmissao->erro_status == '0') {

        $sMensagemErro  = "ERRO [ 2 ] - Desvinculando detalhes do tipo de transmissao do movimento - ";
        $sMensagemErro .= $oDaoEmpAgeMovDetalheTransmissao->erro_msg;
        throw new DBException($sMensagemErro);
      }

  		foreach ($aDetalhes as $oDetalhes) {

  		  $iTipoFatura = 2;
  		  if ($oDetalhes->iFatura == "Fatura") {
  		    $iTipoFatura = 1;
  		  }

  			$iCodigoBarras = $oDetalhes->iCodigoBarras;
  			$nValor        = str_replace(",", ".", (str_replace(".", "", $oDetalhes->nValor)));
  			$nJuros        = str_replace(",", ".", (str_replace(".", "", $oDetalhes->nJuros)));
  			$nDesconto     = str_replace(",", ".", (str_replace(".", "", $oDetalhes->nDesconto)));
  			$dtData        = $oDetalhes->dtData;

	  		$oDaoVerifica = new cl_empagemovdetalhetransmissao();
	  		$sSqlVerifica = $oDaoVerifica->sql_query_busca_codigo_barras($iCodigoBarras, $iMovimento);
	  		$rsVerifica   = $oDaoVerifica->sql_record($sSqlVerifica);

        if ($oDaoVerifica->numrows > 0) {
          throw new DBException('Código de barras "'.$iCodigoBarras.'" já lançado em outro movimento.');
        }

	  		$oDaoEmpAgeMovDetalheTransmissao = new cl_empagemovdetalhetransmissao();
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_empagemov      = $iMovimento;
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_codigodebarra  = $iCodigoBarras;
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_valornominal   = number_format($nValor, 2, '.', '');
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_datavencimento = $dtData;
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_valorjuros     = number_format($nJuros, 2, '.', '');
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_valordesconto  = number_format($nDesconto, 2, '.', '');
	  		$oDaoEmpAgeMovDetalheTransmissao->e74_tipofatura     = $iTipoFatura;
	  		$oDaoEmpAgeMovDetalheTransmissao->incluir(null);
	  		if ($oDaoEmpAgeMovDetalheTransmissao->erro_status == 0) {
	  			throw new DBException("ERRO [ 1 ] - Incluindo detalhe - " .  $oDaoEmpAgeMovDetalheTransmissao->erro_msg);
	  		}
  		}
  		db_fim_transacao(false);
  		$oRetorno->sMessage   = 'Movimento(s) salvo(s) com sucesso.';
  		$oRetorno->iMovimento = $iMovimento;

  	break;


  	case "getRecursoFundeb":

  	  $iCodigoRecursoFundeb = ParametroCaixa::getCodigoRecursoFUNDEB(db_getsession('DB_instit'));
	    $oRetorno->iCodigoRecurso = $iCodigoRecursoFundeb;

  	  break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;

  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (DBException $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (ParameterException $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (BusinessException $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>
