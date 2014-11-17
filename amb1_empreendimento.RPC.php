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

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

define("MENSAGENS", "tributario.meioambiente.amb1_empreendimentos.");

$oDaoEmpreendimento                 = db_utils::getDao("empreendimento");
$oDaoAtividadeImpacto               = db_utils::getDao("atividadeimpacto");
$oDaoAtividadeImpactoPorte          = db_utils::getDao("atividadeimpactoporte");
$oDaoEmpreendimentoAtividadeImpacto = db_utils::getDao("empreendimentoatividadeimpacto");

try {

  switch ($oParametros->sExecucao) {

    case "getDadosEmpreendedor":

      if( empty($oParametros->iCgmEmpreendedor) ){
        throw new BusinessException( _M( MENSAGENS . 'cgm_invalido' ) );
      }

      /**
       * Retorna dados do cgm para visualização
       */
      $iCgmEmpreendedor = $oParametros->iCgmEmpreendedor;
      try {

        $oCgmEmpreendedor = CgmFactory::getInstanceByCgm($iCgmEmpreendedor);

        $oDadosEmpreendedor = new StdClass();
        $oDadosEmpreendedor->isFisico      = true;
        $oDadosEmpreendedor->z01_numcgm    = $oCgmEmpreendedor->getCodigo();
        $oDadosEmpreendedor->z01_nome      = utf8_encode($oCgmEmpreendedor->getNome());

        /**
         * Valida se CGM é pessoa física
         */
        if ( !$oCgmEmpreendedor->isFisico() ) {

          $oDadosEmpreendedor->isFisico      = false;
          $oDadosEmpreendedor->z01_nomefanta = utf8_encode($oCgmEmpreendedor->getNomeFantasia());
          $oDadosEmpreendedor->z01_cgccpf    = $oCgmEmpreendedor->getCnpj();
        }else{
          $oDadosEmpreendedor->z01_cgccpf    = $oCgmEmpreendedor->getCpf();
        }

        $oDadosEmpreendedor->z01_ender  = utf8_encode($oCgmEmpreendedor->getLogradouro());
        $oDadosEmpreendedor->z01_cep    = $oCgmEmpreendedor->getCep();
        $oDadosEmpreendedor->z01_munic  = utf8_encode($oCgmEmpreendedor->getMunicipio());

      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oRetorno->oDadosEmpreendedor = $oDadosEmpreendedor;
    break;

    case "setEmpreendimento":

      db_inicio_transacao();

      $oDaoEmpreendimento->am05_nome        = db_stdClass::normalizeStringJsonEscapeString($oParametros->sNome);
      $oDaoEmpreendimento->am05_nomefanta   = db_stdClass::normalizeStringJsonEscapeString($oParametros->sNomeFanta);
      $oDaoEmpreendimento->am05_numero      = $oParametros->iNumero;
      $oDaoEmpreendimento->am05_complemento = db_stdClass::normalizeStringJsonEscapeString($oParametros->sComplemento);
      $oDaoEmpreendimento->am05_cep         = $oParametros->iCep;
      $oDaoEmpreendimento->am05_bairro      = $oParametros->iCodigoBairro;
      $oDaoEmpreendimento->am05_ruas        = $oParametros->iCodigoLogradouro;
      $oDaoEmpreendimento->am05_cnpj        = $oParametros->iCnpj;
      $oDaoEmpreendimento->am05_cgm         = $oParametros->iNumcgm;

      if ( !empty( $oParametros->iCodigoEmpreendimento ) ) {

        $oDaoEmpreendimento->am05_sequencial = $oParametros->iCodigoEmpreendimento;
        $oDaoEmpreendimento->alterar( $oDaoEmpreendimento->am05_sequencial );
        $oRetorno->iCodigoEmpreendimento = $oDaoEmpreendimento->am05_sequencial;
        $oRetorno->sMensagem             = urlencode( _M( MENSAGENS . 'sucesso_alterar_empreendimento' ) . " \nCódigo: {$oRetorno->iCodigoEmpreendimento}" );
      } else {

        $oDaoEmpreendimento->incluir(null);
        $oRetorno->iCodigoEmpreendimento = $oDaoEmpreendimento->am05_sequencial;
        $oRetorno->sMensagem             = urlencode( _M( MENSAGENS . 'sucesso_cadastrar_empreendimento' ) . " \nCódigo: {$oRetorno->iCodigoEmpreendimento}" );
      }

      if ($oDaoEmpreendimento->erro_status == "0") {

        db_fim_transacao(true);
        throw new BusinessException( _M( MENSAGENS . 'erro_incluir_empreendimento' ) );
      }

      db_fim_transacao(false);
    break;

    case "getEmpreendimento":

      if( empty( $oParametros->iCodigoEmpreendimento ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_empreendimento_obrigatorio' ) );
      }

      $sSqlEmpreendimento = $oDaoEmpreendimento->sql_query( $oParametros->iCodigoEmpreendimento );
      $rsEmpreendimento   = $oDaoEmpreendimento->sql_record( $sSqlEmpreendimento );

      $oEmpreendimento     = db_utils::getCollectionByRecord($rsEmpreendimento, true, false, true);

      $oRetorno->oEmpreendimento = $oEmpreendimento;
    break;

  }

} catch (Exception $eErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);