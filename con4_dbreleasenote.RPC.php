<?php

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_GET["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getContent":

      $sNomeArquivo = db_getsession('DB_itemmenu_acessado');
      $lNotaGeral = false;

      if ( $sNomeArquivo == "0" ) {

        $lNotaGeral = true;
        $sNomeArquivo = "nota_geral_01";

        if ( !empty($oParam->sNomeArquivo) ) {
          $sNomeArquivo = $oParam->sNomeArquivo;
        }
      }

      $oDBReleaseNote = new DBReleaseNote(db_getsession("DB_id_usuario"), $sNomeArquivo, $oParam->sVersao);
      $oRetorno->sContent = $oDBReleaseNote->getContent();

      $oRetorno->sProximaVersao  = $oDBReleaseNote->getProximaVersao($oParam->lSomenteNaoLidos);
      $oRetorno->sVersaoAnterior = $oDBReleaseNote->getVersaoAnterior($oParam->lSomenteNaoLidos);
      $oRetorno->sVersaoAtual    = $oDBReleaseNote->getVersao();

      $oRetorno->sArquivoAnterior = $oDBReleaseNote->getArquivoAnterior();
      $oRetorno->sProximoArquivo  = $oDBReleaseNote->getProximoArquivo();
      $oRetorno->sArquivoAtual    = $sNomeArquivo;

      $oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));
      $oRetorno->sNomeUsuario = urlencode($oUsuario->getNome());

    break;

    case "marcarComoLido":

      $oDBReleaseNote = new DBReleaseNote(db_getsession("DB_id_usuario"));
      $oDBReleaseNote->marcarComoLido($oParam->aArquivosLidos);

    break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);