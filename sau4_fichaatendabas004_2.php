<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require ("libs/db_utils.php");
require ("libs/JSON.php");

include("classes/db_prontuarios_classe.php");

include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$clprontuarios = new cl_prontuarios;

if( $oPost->strAction == 'gravar' ){

	db_inicio_transacao();
	
	$clprontuarios->sd24_i_codigo      = $oPost->sd24_i_codigo;
	$clprontuarios->sd24_t_diagnostico = $oPost->sd24_t_diagnostico;
	$clprontuarios->sd24_c_digitada    = $oPost->sd24_c_digitada;
	$clprontuarios->alterar($oPost->sd24_i_codigo );

  	db_fim_transacao();
  	
  	$booErro = $clprontuarios->numrows_alterar==0;
  	
}

if($oPost->strAction == 'gravar' ){
	$arrRetorno = array("mensagem"=>urlencode($clprontuarios->erro_msg), 
						"erro"=>$booErro, 
						"action"=>$oPost->strAction);
	
	$oJson = new services_json();	
    echo $oJson->encode($arrRetorno);
}

?>
