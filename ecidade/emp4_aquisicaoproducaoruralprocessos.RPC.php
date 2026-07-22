<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2022  DBSeller Servicos de Informatica
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

use App\Domain\Financeiro\Empenho\Models\AquisicaoProducaoRuralProcessos;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_retencaotiporec_classe.php"));

$oParam               = JSON::create()->parse(str_replace('\\', '', $_REQUEST['json']));
$oRetorno             = new stdClass();
$oRetorno->lErro      = false;
$oRetorno->sMessage   = '';

$iInstituicaoSessao = db_getsession('DB_instit');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getProcessos":
        $processos = AquisicaoProducaoRuralProcessos::select([
            'e157_sequencial as id',
            'e157_nrprocjud as numero',
            'e157_vlrcpnret as cp',
            'e157_vlrratnret as rat',
            'e157_vlrsenarnret as senar'
        ])->where('e157_retencaoreceitasprodutorrural', $oParam->retencao)->get();

        $oRetorno->processos = $processos;
    break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->lErro    = true;
  $oRetorno->sMessage = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
