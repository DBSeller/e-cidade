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

require_once(modification('classes/db_acordocomissao_classe.php'));
require_once(modification('classes/db_acordocomissaomembro_classe.php'));
require_once(modification("classes/db_acordotipo_classe.php"));
require_once(modification("classes/db_acordopenalidade_classe.php"));
require_once(modification("classes/db_acordogarantia_classe.php"));
require_once(modification('model/AcordoComissao.model.php'));
require_once(modification('model/Acordo.model.php'));
require_once(modification('model/AcordoItem.model.php'));
require_once(modification('model/AcordoComissaoMembro.model.php'));
require_once(modification("model/AcordoPenalidade.model.php"));
require_once(modification("model/AcordoGarantia.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification('model/CgmBase.model.php'));
require_once(modification('model/CgmFisico.model.php'));
require_once(modification('model/CgmJuridico.model.php'));
require_once(modification("model/MaterialCompras.model.php"));
require_once(modification("model/AcordoPosicao.model.php"));
require_once(modification("model/licitacao.model.php"));
require_once(modification("model/ProcessoCompras.model.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->itens   = array();
switch($oParam->exec) {

  case 'getDadosPenalidadeGarantia' :

    try {
      if ($oParam->iTipo == 1) {
        if (!empty($oParam->iCodigo)) {

          $oPenalidade         = new AcordoPenalidade($oParam->iCodigo);
          $oRetorno->descricao = urlencode($oPenalidade->getDescricao());
          $oRetorno->texto     = urlencode($oPenalidade->getTextoPadrao());
        }
      } else if ($oParam->iTipo == 2) {

        if (!empty($oParam->iCodigo)) {
          $oGarantia           = new AcordoGarantia($oParam->iCodigo);
          $oRetorno->descricao = urlencode($oGarantia->getDescricao());
          $oRetorno->texto     = urlencode($oGarantia->getTextoPadrao());
        }
      }
    }
    catch (Exception $eErro) {
       $oRetorno->status = 2;
       $oRetorno->messag = urlencode($eErro->getMessage());
     }
    break;

  case "getPenalidades" :
    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      $oContrato    = $_SESSION["oContrato"];
      $aPenalidades = $oContrato->getPenalidades();
      $oRetorno->isUpdate = false;

      foreach ($aPenalidades as $oPenalidade) {
        if ($oParam->iPenalidade != '') {
          if ($oPenalidade->getCodigo() == $oParam->iPenalidade) {
            $oRetorno->isUpdate = true;
            $oPenal  = new stdClass();
            $oPenal->codigo    = $oPenalidade->getCodigo();
            $oPenal->descricao = urlencode($oPenalidade->getDescricao());
            $oPenal->texto     = urlencode($oPenalidade->getTextoPadrao());
            $oRetorno->itens[] = $oPenal;
          }
        } else {
          $oPenal  = new stdClass();
          $oPenal->codigo    = $oPenalidade->getCodigo();
          $oPenal->descricao = urlencode($oPenalidade->getDescricao());
          $oPenal->texto     = urlencode(str_replace("\n","<br>",$oPenalidade->getTextoPadrao()));
          $oRetorno->itens[] = $oPenal;
        }
      }
    }

    break;

  case "salvarPenalidade":
    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      try {
        db_inicio_transacao();
        $oContrato    = $_SESSION["oContrato"];
        $oPenalidade  = new AcordoPenalidade($oParam->iPenalidade);

        if (empty($oParam->iPenalidade)) {
          throw new Exception('Penalidade do acordo não informada.');
        }

        $oContrato->adicionarPenalidades($oPenalidade, db_stdClass::normalizeStringJsonEscapeString($oParam->sTexto));
        $oContrato->save();

        db_fim_transacao(false);
      } catch (Exception $eErro) {
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;

  case "excluirPenalidade":
      if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
        try {
          db_inicio_transacao();
          $oContrato    = $_SESSION["oContrato"];

          if (empty($oParam->iPenalidade)) {
            throw new Exception('Penalidade do acordo não informada.');
          }

          $oContrato->removerPenalidade($oParam->iPenalidade);
          $oContrato->save();

          db_fim_transacao(false);
        } catch (Exception $eErro) {
          db_fim_transacao(true);
          $oRetorno->status = 2;
          $oRetorno->message = urlencode($eErro->getMessage());
        }
      }
      break;

  case "getGarantias" :
    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      $oContrato = $_SESSION["oContrato"];
      $aGarantias = $oContrato->getGarantias();
      $oRetorno->isUpdate = false;

      foreach ($aGarantias as $oGarantia) {
        if ($oParam->iGarantia != '') {
          if ($oGarantia->getCodigo() == $oParam->iGarantia) {
            $oRetorno->isUpdate = true;
            $oGaran  = new stdClass();
            $oGaran->codigo    = $oGarantia->getCodigo();
            $oGaran->descricao = urlencode($oGarantia->getDescricao());
            $oGaran->texto     = urlencode($oGarantia->getTextoPadrao());
            $oRetorno->itens[] = $oGaran;
          }
        } else {
          $oGaran  = new stdClass();
          $oGaran->codigo = $oGarantia->getCodigo();
          $oGaran->descricao = urlencode($oGarantia->getDescricao());
          $oGaran->texto = urlencode(str_replace("\n", '<br>', $oGarantia->getTextoPadrao()));
          $oRetorno->itens[] = $oGaran;
        }
      }
    }

    break;

  case "salvarGarantia":
    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      try {
        db_inicio_transacao();
        $oContrato = $_SESSION["oContrato"];
        $oGarantia = new AcordoGarantia($oParam->iGarantia);

        if (empty($oGarantia->getCodigo())) {
          throw new Exception('Garantia do acordo não informada.');
        }

        $oContrato->adicionarGarantias($oGarantia, db_stdClass::normalizeStringJsonEscapeString($oParam->sTexto));
        $oContrato->save();

        db_fim_transacao(false);
      } catch (Exception $eErro) {
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;

   case "excluirGarantia":
      if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
        try {
          db_inicio_transacao();

          if (empty($oParam->iGarantia)) {
              throw new Exception('Garantia do acordo não informada.');
          }

          $oContrato = $_SESSION["oContrato"];
          $oContrato->removerGarantia($oParam->iGarantia);
          $oContrato->save();

          db_fim_transacao(false);
        } catch (Exception $eErro) {
          db_fim_transacao(true);

          $oRetorno->status = 2;
          $oRetorno->message = urlencode($eErro->getMessage());
        }
      }
      break;
}

echo $oJson->encode($oRetorno);
