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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam   = JSON::requestParameters();
$oRetorno = (object)['erro' => false, 'mensagem' => ''];


switch ($oParam->exec) {

    case "buscarProcedimentos":
        $oRetorno->aPaises   = [];
        try {
            $sql = "
                select
                       h28_codigo as codigo,
                       h28_descricao as descricao
                from
                     recursoshumanos.monitoramentosaudeprocedimento
            ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Ocorreu erro ao buscar os dados de procedimentos médicos.");
            }

            $registros = pg_num_rows($rs);
            for ($i = 0; $i < $registros; $i++) {
                $oDado = db_utils::fieldsMemory($rs, $i);
                $oDados = new stdClass();
                $oDados->codigo = $oDado->codigo;
                $oDados->descricao = utf8_encode($oDado->descricao);
                $oRetorno->procedimentos[] = $oDados;
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;
}

echo JSON::create()->stringify($oRetorno);
?>
