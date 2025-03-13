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

use \ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\TipoProcesso;
use \ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso as TipoProcessoModel;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));


$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);

if (isset($oGet->p51_codigo)) {
    $iCod = $oGet->p51_codigo;
} else if (isset($oPost->p51_codigo)) {
    $iCod = $oPost->p51_codigo;
} else {
    $iCod = 0;
}


function getTipoProcesso($codigoTipoProcesso)
{
    return TipoProcesso::getInstancia()->getByCodigo($codigoTipoProcesso);
}

if (!empty($oPost) && $oPost->actionItemMenu == 'salvar') {
    $tipoproc = new   cl_tipoproc();
    $tipoproc->p51_codigo = $oPost->p51_codigo;
    $tipoproc->p51_itemmenu = $oPost->p51_itemmenu;
    $tipoproc->alterar($oPost->p51_codigo);
    if ($tipoproc->erro_status != "0") {
        db_msgbox('Item de Menu salvo com sucesso!');
    }
    unset($_POST["actionItemMenu"]);
}

if (!empty($oPost) && $oPost->actionLinkSaiba == 'salvar') {
    $tipoproc = new   cl_tipoproc();
    $tipoproc->p51_codigo = $oPost->p51_codigo;
    $tipoproc->p51_linksaibamais = $oPost->linksaibamais;
    $tipoproc->alterar($oPost->p51_codigo);
    if ($tipoproc->erro_status != "0") {
        db_msgbox('Link salvo com sucesso!');
    }
    unset($_POST["actionLinkSaiba"]);
}

try {
    $tipoProcesso = getTipoProcesso($iCod);
} catch (\Exception $ex) {
}


?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

<div style="width:500px;margin: auto;margin-top: 90px">

    <fieldset>
        <form method="post" enctype="multipart/form-data">

            <legend>ITEM DE MENU</legend>
            <input
                type="hidden"
                name="p51_codigo"
                value="<?= $tipoProcesso instanceof TipoProcessoModel
                    ? $tipoProcesso->getCodigo()
                    : "" ?>"
            >
            <br>
            <input
                type="text"
                name="p51_itemmenu"
                style="width: 100%"
                value="<?= $tipoProcesso instanceof TipoProcessoModel ? $tipoProcesso->getItemMenu() : "" ?>"
            >
            <br>
            <br>
            <div style="width: 100%; text-align: right">
                <input type="submit" value="salvar" name="actionItemMenu">
            </div>

        </form>
    </fieldset>

    <fieldset>
        <form method="post" enctype="multipart/form-data">

            <legend>LINK SAIBA MAIS</legend>
            <input
                type="hidden"
                name="p51_codigo"
                value="<?= $tipoProcesso instanceof TipoProcessoModel  ?  $tipoProcesso->getCodigo()   : "" ;?>"
            >
            <br>
            <input
                type="url"
                name="linksaibamais"
                style="width: 100%"
                value="<?= $tipoProcesso instanceof TipoProcessoModel  ? $tipoProcesso->getLinkSaibaMais() : "";?>"
            >
            <br>
            <br>
            <div style="width: 100%; text-align: right">
                <input type="submit" value="salvar" name="actionLinkSaiba">
            </div>
        </form>
    </fieldset>

</div>
</body>
</html>
