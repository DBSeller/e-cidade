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
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\Pessoal\Model\TipoAgrupamentoRubrica;
use ECidade\RecursosHumanos\Pessoal\Repository\TipoAgrupamentoRubricaRepository;

$oParametros       = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case 'get':
        
        $model = new TipoAgrupamentoRubrica($oParametros->iCodigo);
        
        $oRetorno->iCodigo    = $model->getSequencial();
        $oRetorno->sDescricao = $model->getDescricao();
        
      break;

    case "save":
        
        $model = new TipoAgrupamentoRubrica();

        $model->setSequencial($oParametros->iCodigo);
        $model->setDescricao($oParametros->sDescricao);

        TipoAgrupamentoRubricaRepository::persist($model);

        $oRetorno->iCodigo = $model->getSequencial();
        $oRetorno->message = 'Cadastro efetuado';

      break;

    case 'delete':
        
        $model = new TipoAgrupamentoRubrica($oParametros->iCodigo);
        TipoAgrupamentoRubricaRepository::delete($model);
        $oRetorno->message = 'Exclusão efetuada';

      break;

    case 'getAll':

      break;

    default:
      throw new ParameterException('Método inválido.');

  }
  db_fim_transacao(false);

} catch (Exception $oException) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($oException->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);