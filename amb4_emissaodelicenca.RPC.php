<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

$oDaoLicencaEmpreendimento          = db_utils::getDao("licencaempreendimento");
$oDaoEmpreendimentoAtividadeImpacto = db_utils::getDao("empreendimentoatividadeimpacto");
$oDaoResponsavelTecnico             = db_utils::getDao("responsaveltecnico");
$oDaoDocumentoTemplate              = db_utils::getDao("db_documentotemplate");

define("MENSAGENS", "tributario.meioambiente.amb4_emissaodelicenca.");

define("TIPO_LICENCA_PREVIA",     1);
define("TIPO_LICENCA_INSTALACAO", 2);
define("TIPO_LICENCA_OPERACAO",   3);

define("TIPO_EMISSAO_NOVA",        1);
define("TIPO_EMISSAO_PRORROGACAO", 2);
define("TIPO_EMISSAO_RENOVACAO",   3);

try {

  switch ($oParametros->sExecucao) {

    case "emitirLicenca":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'empreendimento_obrigatorio'));
      }

      if (empty($oParametros->iCodigoProtocolo)) {
        throw new Exception(_M( MENSAGENS . 'processo_obrigatorio'));
      }

      if (empty($oParametros->iTipoLicenca)) {
        throw new Exception(_M( MENSAGENS . 'tipo_licenca_obrigatorio'));
      }

      if (empty($oParametros->iTipoEmissao)) {
        throw new Exception(_M( MENSAGENS . 'tipo_emissao_obrigatorio'));
      }

      if (empty($oParametros->sDataEmissao)) {
        throw new Exception(_M( MENSAGENS . 'data_emissao_obrigatorio'));
      }

      if (empty($oParametros->sDataVencimento)) {
        throw new Exception(_M( MENSAGENS . 'data_vencimento_obrigatorio'));
      }

      $aDataEmissao    = explode("/", $oParametros->sDataEmissao);
      $aDataVencimento = explode("/", $oParametros->sDataVencimento);

      if ($aDataEmissao[2] > $aDataVencimento[2]) {
        throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
      } else if ($aDataEmissao[2] == $aDataVencimento[2]) {

        if($aDataEmissao[1] > $aDataVencimento[1]) {
          throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
        } else if ($aDataEmissao[1] == $aDataVencimento[1]) {

          if ($aDataEmissao[0] > $aDataVencimento[0]) {
            throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
          }
        }
      }

      $sWhere            = " am06_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sWhere           .= " and am06_principal = true";
      $sSql              = $oDaoEmpreendimentoAtividadeImpacto->sql_query_file(null, "*", null, $sWhere);
      $rsRecordAtividade = $oDaoEmpreendimentoAtividadeImpacto->sql_record($sSql);
      $oAtividade        = db_utils::getCollectionByRecord($rsRecordAtividade);

      if (empty($oAtividade)) {
        throw new Exception(_M( MENSAGENS . 'erro_atividade_obrigatorio' ));
      }

      $sWhere              = " am07_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sSql                = $oDaoResponsavelTecnico->sql_query_file(null, "*", null, $sWhere);
      $rsRecordResponsavel = $oDaoResponsavelTecnico->sql_record($sSql);
      $oResponsavel        = db_utils::getCollectionByRecord($rsRecordResponsavel);

      if (empty($oResponsavel)) {
        throw new Exception(_M( MENSAGENS . 'erro_responsavel_obrigatorio' ));
      }

      $sWhere   = "     am08_empreendimento = {$oParametros->iCodigoEmpreendimento} ";
      $sWhere  .= " and am08_tipolicenca    = {$oParametros->iTipoLicenca}    ";

      $sSql     = $oDaoLicencaEmpreendimento->sql_query_file(null, "*", "am08_sequencial DESC", $sWhere);
      $rsRecord = $oDaoLicencaEmpreendimento->sql_record($sSql);
      $oLicenca = db_utils::getCollectionByRecord($rsRecord);

      if ($oParametros->iTipoEmissao == TIPO_EMISSAO_NOVA) {

        if (!empty($oLicenca)) {
          throw new Exception( _M( MENSAGENS . 'erro_licenca_existente' ) );
        }
      }

      if ($oParametros->iTipoEmissao == TIPO_EMISSAO_PRORROGACAO || $oParametros->iTipoEmissao == TIPO_EMISSAO_RENOVACAO) {

        if (empty($oLicenca)) {
          throw new Exception( _M( MENSAGENS . 'erro_licenca_inexistente' ) );
        }

        if ( $oParametros->iTipoEmissao == TIPO_EMISSAO_PRORROGACAO ) {
          $oDaoLicencaEmpreendimento->am08_licencaanterior = $oLicenca[0]->am08_sequencial;
        }
      }

      $oDaoLicencaEmpreendimento->am08_empreendimento = $oParametros->iCodigoEmpreendimento;
      $oDaoLicencaEmpreendimento->am08_protprocesso   = $oParametros->iCodigoProtocolo;
      $oDaoLicencaEmpreendimento->am08_tipolicenca    = $oParametros->iTipoLicenca;
      $oDaoLicencaEmpreendimento->am08_dataemissao    = $oParametros->sDataEmissao;
      $oDaoLicencaEmpreendimento->am08_datavencimento = $oParametros->sDataVencimento;

      db_inicio_transacao();
      $oDaoLicencaEmpreendimento->incluir(null);
      db_fim_transacao(false);

      $iCodigoLicenciamento = $oDaoLicencaEmpreendimento->am08_sequencial;
      $iNumeroLicenca       = $iCodigoLicenciamento;

      if ($oParametros->iTipoEmissao == TIPO_EMISSAO_PRORROGACAO) {

        $oLicencaOriginal = array_pop($oLicenca);
        $iNumeroLicenca   = $oLicencaOriginal->am08_sequencial;
      }

      /**
       * Emissão via agata
       */
      $aParam = array(
        'iCodigoLicenciamento' => $iCodigoLicenciamento,
        'numero_licenca'       => $iNumeroLicenca
      );

      $sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");

      $sNomeRelatorio   = "tmp/licenca_{$sDescrDoc}.pdf";
      $sCaminhoSalvoSxw = "tmp/licenca_{$sDescrDoc}.sxw";

      $sAgt = "meioambiente/licencas_meio_ambiente.agt";

      $aParametros = array(
        'iCodigoLicenciamento' => $iCodigoLicenciamento,
        'numero_licenca'       => $iNumeroLicenca
      );

      /**
       * Definir qual o template e ajustar o array para emissao
       */
      if ($oParametros->iTipoLicenca == TIPO_LICENCA_PREVIA) {
        $iCodigoTemplateTipo   = 50; // db_documentotemplatetipo
      }

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_INSTALACAO) {
        $iCodigoTemplateTipo   = 48; // db_documentotemplatetipo
      }

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_OPERACAO) {
        $iCodigoTemplateTipo   = 49; // db_documentotemplatetipo
      }

      $sWhere = " db82_templatetipo = {$iCodigoTemplateTipo}";

      $sSql                  = $oDaoDocumentoTemplate->sql_query(null, "db82_sequencial", null, $sWhere);
      $rsDocumentoTemplate   = $oDaoDocumentoTemplate->sql_record($sSql);
      $oDocumentoTemplate    = db_utils::getCollectionByRecord($rsDocumentoTemplate);

      $iCodigoTemplatePadrao = $oDocumentoTemplate[0]->db82_sequencial;

      /**
       * Gerando agata
       */
      $clagata = new cl_dbagata($sAgt);
      $api     = $clagata->api;
      $api->setOutputPath($sCaminhoSalvoSxw);
      $api->setFormat('sxw');

      foreach ($aParam as $sParN=>$sParVal) {
        $api->setParameter($sParN,$sParVal);
      }

      try {
        $oDocumentoTemplate = new documentoTemplate($iCodigoTemplateTipo,$iCodigoTemplatePadrao);
      } catch (Exception $eException){

        $sErroMsg  = $eException->getMessage();
        //Mover erro agata para json
        //Erro ao gerar arquivo de licença
        throw new Exception( $sErroMsg );
      }

      $oRetorno->sArquivoRetorno = '';
      /**
       * Desabilitando erros devido a classe do agata
       */
      error_reporting('E_ERROR');
      $lProcessado = $api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());
      if( $lProcessado ){

        $oRetorno->sArquivoRetorno = $sCaminhoSalvoSxw;
        $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'emissao_sucesso' ) );
      }else{
        throw new Exception( _M( MENSAGENS . 'erro_impressao_licenca' ) );
      }

    break;

    case "getTiposLicenca":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      $sWhere    = "am08_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sSql      = $oDaoLicencaEmpreendimento->sql_query_file(null, "*", null, $sWhere);
      $rsRecord  = $oDaoLicencaEmpreendimento->sql_record($sSql);
      $aLicencas = db_utils::getCollectionByRecord($rsRecord);

      $aTiposLicenca = array(
          TIPO_LICENCA_PREVIA     => utf8_encode("Prévia"),
          TIPO_LICENCA_INSTALACAO => utf8_encode("Instalação"),
          TIPO_LICENCA_OPERACAO   => utf8_encode("Operação")
      );

      foreach ($aLicencas as $oLicenca) {

        if ($oLicenca->am08_tipolicenca == TIPO_LICENCA_OPERACAO) {

          unset($aTiposLicenca[TIPO_LICENCA_PREVIA]);
          unset($aTiposLicenca[TIPO_LICENCA_INSTALACAO]);
          break;
        }

        if ($oLicenca->am08_tipolicenca == TIPO_LICENCA_INSTALACAO) {
          unset($aTiposLicenca[TIPO_LICENCA_PREVIA]);
        }
      }

      $oRetorno->aTiposLicenca = $aTiposLicenca;
    break;

    case "getTiposEmissao":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      if ($oParametros->iTipoLicenca == "") {
        throw new Exception(_M( MENSAGENS . 'tipo_licenca_obrigatorio'));
      }

      $aTiposEmissao = array(
          TIPO_EMISSAO_NOVA        => utf8_encode("Nova"),
          TIPO_EMISSAO_PRORROGACAO => utf8_encode("Prorrogação"),
          TIPO_EMISSAO_RENOVACAO   => utf8_encode("Renovação")
        );

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_PREVIA || $oParametros->iTipoLicenca == TIPO_LICENCA_INSTALACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
      }

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_OPERACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
      }

      $sWhere    = "am08_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sWhere   .= " and am08_tipolicenca = {$oParametros->iTipoLicenca}";

      $sSql      = $oDaoLicencaEmpreendimento->sql_query_file(null, "*", null, $sWhere);
      $rsRecord  = $oDaoLicencaEmpreendimento->sql_record($sSql);
      $aLicencas = db_utils::getCollectionByRecord($rsRecord);

      if ( empty($aLicencas) ) {

        if (isset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
        }

        if (isset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
        }
      } else {
        unset($aTiposEmissao[TIPO_EMISSAO_NOVA]);
      }

      $oRetorno->aTiposEmissao = $aTiposEmissao;
      break;
  }

} catch (Exception $eErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);