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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oParam                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

define( 'MSG_BASE_RPC', 'educacao.secretariaeducacao.edu4_base_RPC.' );

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Busca as etapas de um curso e identica se as mesmas estão em uma Base Curricular
     */
    case 'etapasPorCursoBaseCurricular':

      if ( empty($oParam->iCurso)) {
        throw new ParameterException( _M( MSG_BASE_RPC . "curso_nao_informado" ) );
      }

      if ( empty($oParam->iTipo)) {
        throw new ParameterException( _M( MSG_BASE_RPC . "tipo_nao_informado" ) );
      }

      $oCurso  = new Curso($oParam->iCurso);
      $oEnsino = $oCurso->getEnsino();
      $aEtapas = $oEnsino->getEtapas();

      $oRetorno->sBase   = "";
      $oRetorno->iBase   = "";
      $oRetorno->aEtapas = array();
      foreach ($aEtapas as $oEtapa) {

        $oDadoEtapa          = new stdClass();
        $oDadoEtapa->lCheck  = false;
        $oDadoEtapa->iEtapa  = $oEtapa->getCodigo();
        $oDadoEtapa->sEtapa  = $oEtapa->getNome();
        $oRetorno->aEtapas[] = $oDadoEtapa;
      }


      $sWhere       = " ed141_cursoedu = {$oParam->iCurso} and ed141_tipo = {$oParam->iTipo} ";
      $oDaoBase     = new cl_basecurricularserie();
      $sSql         = $oDaoBase->sql_query(null, "ed141_sequencial, ed141_descricao, ed142_serie", null, $sWhere);
      $rsEtapasBase = db_query($sSql);
      if ( !$rsEtapasBase ) {

        $oMsgErro->sErro = pg_last_error();
        throw new DBException( _M( MSG_BASE_RPC . "erro_buscar_etapas_base", $oMsgErro ) );
      }

      if ( pg_num_rows($rsEtapasBase) > 0 ) {

        $aEtapasBase = db_utils::getCollectionByRecord($rsEtapasBase);
        $oRetorno->iBase = $aEtapasBase[0]->ed141_sequencial;
        $oRetorno->sBase = $aEtapasBase[0]->ed141_descricao;
        foreach ($aEtapasBase as $oEtapaBase) {

          foreach ($oRetorno->aEtapas as $oEtapa) {

            if ($oEtapa->iEtapa == $oEtapaBase->ed142_serie) {
              $oEtapa->lCheck = true;
              continue 2;
            }
          }
        }
      }
      break;

    /**
     * Salva uma base incluindo ou alterando as informações;
     * Se informado uma base sem etapas ($oParam->aEtapas), a base é excluída
     */
    case 'salvar':

      if ( empty($oParam->iCurso)) {
        throw new ParameterException( _M( MSG_BASE_RPC . "curso_nao_informado" ) );
      }

      if ( empty($oParam->iTipo)) {
        throw new ParameterException( _M( MSG_BASE_RPC . "tipo_nao_informado" ) );
      }
      if ( empty($oParam->sNome)) {
        throw new ParameterException( _M( MSG_BASE_RPC . "nome_nao_informado" ) );
      }

      $oDaoBase      = new cl_basecurricular();
      $oDaoBaseEtapa = new cl_basecurricularserie();

      if ( !empty($oParam->iBase)) {

        $oDaoBaseEtapa->excluir( null, "ed142_basecurricular = {$oParam->iBase}");

        if ( $oDaoBaseEtapa->erro_status == 0 ) {
          throw new DBException( _M(MSG_BASE_RPC . "erro_excluir_etapas") );
        }

        if ( count($oParam->aEtapas) == 0 ) {

          $oDaoBase->excluir($oParam->iBase);
          if ( $oDaoBase->erro_status == 0 ) {
            throw new DBException( _M(MSG_BASE_RPC . "erro_excluir_base") );
          }
          break; // Quando desmarcado todas etapas, exclui a base
        }
      }

      $oDaoBase->ed141_sequencial = null;
      $oDaoBase->ed141_cursoedu   = $oParam->iCurso;
      $oDaoBase->ed141_tipo       = $oParam->iTipo;
      $oDaoBase->ed141_descricao  = $oParam->sNome;

      if ( !empty($oParam->iBase)) {

        $oDaoBase->ed141_sequencial = $oParam->iBase;
        $oDaoBase->alterar($oParam->iBase);
      } else {
        $oDaoBase->incluir(null);
      }

      if ( $oDaoBase->erro_status == 0 ) {
        throw new DBException( _M(MSG_BASE_RPC . "erro_salvar_base") );
      }

      $oDaoBaseEtapa->ed142_sequencial     = null;
      $oDaoBaseEtapa->ed142_basecurricular = $oDaoBase->ed141_sequencial;

      foreach ($oParam->aEtapas as $iEtapa) {

        $oDaoBaseEtapa->ed142_serie = $iEtapa;
        $oDaoBaseEtapa->incluir(null);

        if ( $oDaoBaseEtapa->erro_status == 0 ) {
          throw new DBException( _M(MSG_BASE_RPC . "erro_salvar_etapa") );
        }
      }

      $oRetorno->sMessage = _M(MSG_BASE_RPC . "base_salva");

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);