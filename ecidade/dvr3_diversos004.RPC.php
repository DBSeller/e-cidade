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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro = false;

db_inicio_transacao();

try {

    switch ($oParam->exec) {

        case 'verificaDadosValidos':

            $arrayDadosValidos = $oParam->dadosValidos;
            $arrayDadosExistentes = [];
            $arrayDadosInexistentes = [];

            foreach ($arrayDadosValidos as $key => $value) {

                if ($oParam->tipoDados == 'cgm'){
                    $sql = "SELECT * FROM cgm WHERE z01_numcgm = $value ";
                    $result = db_query($sql);
                    if (pg_num_rows($result) > 0) {
                        array_push($arrayDadosExistentes, $value);
                    } else {
                        array_push($arrayDadosInexistentes, $value);
                    }
                }
    
                if ($oParam->tipoDados == 'matric'){
                    $sql = "SELECT * FROM iptubase WHERE j01_matric = $value ";
                    $result = db_query($sql);
                    if (pg_num_rows($result) > 0) {
                        array_push($arrayDadosExistentes, $value);
                    } else {
                        array_push($arrayDadosInexistentes, $value);
                    }
                }
    
                if ($oParam->tipoDados == 'inscr'){
                    $sql = "SELECT * FROM issbase WHERE q02_inscr = $value ";
                    $result = db_query($sql);
                    if (pg_num_rows($result) > 0) {
                        array_push($arrayDadosExistentes, $value);
                    } else {
                        array_push($arrayDadosInexistentes, $value);
                    }
                }

            }

            $oRetorno->arrayDadosExistentes = $arrayDadosExistentes;
            $oRetorno->arrayDadosInexistentes = $arrayDadosInexistentes;

            break;

    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMensagem = $e->getMessage();
    $oRetorno->erro = true;
}

echo $oJson->stringify($oRetorno);