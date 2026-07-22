<?php
/**
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMessage     = '';

define("MENSAGENS", "tributario.notificacoes.cai4_excluiitemlista.");

try {

  if ( empty($oParametros->iCodigoLista) ) {
    throw new Exception( _M( MENSAGENS . "erro_codigo_lista" ) );
  }

  switch ($oParametros->sExecucao) {

    case "getItensLista":

      ini_set('memory_limit', '-1');

      $sWhere = " k61_codigo = {$oParametros->iCodigoLista} ";

      $oDaoListaDebitos = new cl_listadeb();
      $sSqlListaDebitos = $oDaoListaDebitos->sql_query(null, null, null, "listadeb.*, lista.k60_filtros", null, $sWhere);
      $rsListaDebitos   = $oDaoListaDebitos->sql_record($sSqlListaDebitos);

      if ( $oDaoListaDebitos->numrows == 0 ) {
        throw new Exception( _M( MENSAGENS . "erro_busca_itens_lista" ) );
      }

      $sWhere = " k122_lista = {$oParametros->iCodigoLista} ";

      $oDaoPrescricaoLista = new cl_prescricaolista();
      $sSqlPrescricaoLista = $oDaoPrescricaoLista->sql_query_file(null, "*", null, $sWhere);
      $rsPrescricaoLista   = $oDaoPrescricaoLista->sql_record($sSqlPrescricaoLista);

      if ( $oDaoPrescricaoLista->numrows > 0 ) {
        throw new Exception( _M( MENSAGENS . "erro_lista_prescrita" ) );
      }

      $aItensLista     = db_utils::getCollectionByRecord($rsListaDebitos);
      $sDescricaoLista = $aItensLista[0]->k60_filtros;

      $oRetorno->sResumoLista = "";

      if ( !empty($sDescricaoLista) ) {

        $sDescricaoLista        = urlencode(str_replace("#","\n", $sDescricaoLista));
        $oRetorno->sResumoLista = $sDescricaoLista;
      }

      $aItensRetornar = array();

      foreach ($aItensLista as $iIndice => $oItemLista) {

        $oItemRetornavel  = new StdClass();
        $sSqlOrigemNumpre = "select fc_origem_numpre($oItemLista->k61_numpre, 1, '') as busca_origem_numpre";
        $rsOrigemNumpre   = db_query($sSqlOrigemNumpre);
        $sOrigemNumpre    = db_utils::fieldsMemory($rsOrigemNumpre, 0)->busca_origem_numpre;

        if ( empty($sOrigemNumpre) ) {

          $oMensagem->iNumpre = $oItemLista->k61_numpre;
          throw new Exception( _M( MENSAGENS . "erro_busca_origem_numpre",  $oMensagem) );
        }

        $aOrigemNumpre = explode(" ", $sOrigemNumpre);
        $iQuant        = count($aOrigemNumpre);

        $iCodigo = $aOrigemNumpre[$iQuant - 1];

        $sClasse = "cl_cgm";
        $sCampo  = "z01_nome";

        if ( $aOrigemNumpre[$iQuant - 2] == 'M:' ) {
          $sClasse = "cl_iptubase";
        }

        if ( $aOrigemNumpre[$iQuant - 2] == 'I:' ) {
          $sClasse = "cl_issbase";
        }

        $oDaoOrigem = new $sClasse();
        $sSqlOrigem = $oDaoOrigem->sql_query($iCodigo, $sCampo);
        $rsOrigem   = $oDaoOrigem->sql_record($sSqlOrigem);
        $sNome      = db_utils::fieldsMemory($rsOrigem, 0)->$sCampo;

        $oItemRetornavel->sNome  = $sNome;
        $oItemRetornavel->iNumpre = $oItemLista->k61_numpre . "/" . $oItemLista->k61_numpar;
        $aItensRetornar[] = $oItemRetornavel;
      }

      asort($aItensRetornar);
      $oRetorno->aItensLista = array_values($aItensRetornar);

      break;

      case "excluirItensLista":

        $oItensLista  = $oParametros->aItensMarcados;
        $aWhere       = array();
        $oDaolistadeb = new cl_listadeb();

        db_inicio_transacao();
        foreach ($oItensLista as $oNumpreNumpar) {

          list($sNumpre, $sNumpar) = explode('/', $oNumpreNumpar->sNumpreNumpar);

          $rslistadeb = $oDaolistadeb->excluir( $oParametros->iCodigoLista, $sNumpre, $sNumpar );

          if( !$rslistadeb ){

             $oMensagem = new stdClass();
             $oMensagem->sNumpreNumpar = $sNumpre . '/' . $sNumpar;
             throw new Exception( _M( MENSAGENS . "erro_excluir_numprenumpar",  $oMensagem) );
          }
        }

        db_fim_transacao(false);

        $oRetorno->sMessage = urlencode(_M( MENSAGENS . "sucesso_exclusao_item"));

      break;
  }

} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);