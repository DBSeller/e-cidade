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
define( 'MENSAGENS_LAB4_AUTORIZACAO_RPC', 'saude.laboratorio.lab4_autorizacao_RPC.' );

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oJson               = new services_json();
$oParam              = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  switch ($oParam->exec) {

    case 'autorizaExames':

      db_inicio_transacao();

      $oDaoAutoriza   = new cl_lab_autoriza();
      $oDaoRequisicao = new cl_lab_requisicao();
      $oDaoRequiItem  = new cl_lab_requiitem();

      /**
       * Inclui autorização
       */
      $oDaoAutoriza->la48_i_requisicao = $oParam->iRequisicao;
      $oDaoAutoriza->la48_d_data       = date('Y-m-d',db_getsession("DB_datausu"));
      $oDaoAutoriza->la48_c_hora       = db_hora();
      $oDaoAutoriza->la48_i_usuario    = db_getsession("DB_id_usuario");
      $oDaoAutoriza->incluir(null);

      if ($oDaoAutoriza->erro_status != "0") {

       /**
        * Altera a requisição para autorizada
        */
        $oDaoRequisicao->la22_i_autoriza = 2;
        $oDaoRequisicao->la22_i_codigo   = $oParam->iRequisicao;
        $oDaoRequisicao->alterar($oParam->iRequisicao);
        if ($oDaoRequisicao->erro_status == "0") {

          $oErro        = new stdClass();
          $oErro->sErro = pg_last_error();
          throw new Exception ( _M( MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_requisicao', $oErro ) );
        }

        /**
         * Busca os itens da requisição e altera a situação para autorizado
         */
        $sExames  = implode(", ", $oParam->aExemes );
        $sWhere   = " la21_i_requisicao = {$oParam->iRequisicao} and la21_i_setorexame in ({$sExames}) ";
        $sSqlItem = $oDaoRequiItem->sql_query_file(null, "la21_i_codigo", null, $sWhere);
        $rsItem   = $oDaoRequiItem->sql_record($sSqlItem);
        $iLinhas  = $oDaoRequiItem->numrows;

        for ( $i = 0; $i < $iLinhas; $i++ ) {

          $iCodigoItem = db_utils::fieldsMemory($rsItem, $i)->la21_i_codigo;
          $oDaoRequiItem->la21_c_situacao = "8 - Autorizado";
          $oDaoRequiItem->la21_i_codigo   = $iCodigoItem;
          $oDaoRequiItem->alterar($iCodigoItem);

          if ($oDaoRequiItem->erro_status == 0) {

            $oErro        = new stdClass();
            $oErro->sErro = pg_last_error();
            throw new Exception ( _M( MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_item_requisicao', $oErro ) );
          }
        }
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_LAB4_AUTORIZACAO_RPC . 'exames_autorizados' ) );
      db_fim_transacao();

      break;
  }
} catch ( Exception $oErro ) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
  db_fim_transacao( true );
}

echo $oJson->encode( $oRetorno );