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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));

const FILE_PATH = "/tmp/formulario.json";

$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);
$db_opcao = 2;


if (isset($oGet->p51_codigo)) {
    $iCod = $oGet->p51_codigo;
} else if (isset($oPost->p51_codigo)) {
    $iCod = $oPost->p51_codigo;
} else {
    $iCod = 0;
}

$formulario = getFormulario($iCod);
/**
 * SALVAR ARQUIVO
 */
if (isset($oPost->saveFile)) {
    try {
        $conteudoArquivo = "{}";

        if ($oPost->tipo == "") {
            throw new \Exception("Selecione um tipo");
        }

        if (empty($oPost->p108_rota) || $oPost->tipo == 1) {
            if (empty($_FILES["fileToUpload"]) || !file_exists($_FILES["fileToUpload"]['tmp_name'])) {
                throw new \Exception("Selecione um arquivo");
            }

            $ext = explode("/", $_FILES["fileToUpload"]['type'])[1];
            if ($ext != "json") {
                throw new \Exception("Arquivo permitido apenas formato json");
            }
            $conteudoArquivo = file_get_contents($_FILES["fileToUpload"]['tmp_name']);
        }

        $daoFormulario = new cl_tipoprocessoformulario();

        $daoFormulario->p108_tipoproc = $oPost->p51_codigo;
        $daoFormulario->p108_rota = $oPost->p108_rota;
        $daoFormulario->p108_formulario = "{$conteudoArquivo}";

        if (isset($formulario->p108_sequencial)) {
            $daoFormulario->p108_sequencial = $formulario->p108_sequencial;
            $daoFormulario->alterar($daoFormulario->p108_sequencial);
        } else {
            $daoFormulario->incluir();
        }

        if ($daoFormulario->erro_status == "0") {
            throw new \Exception($daoFormulario->erro_msg . "<br>" . $daoFormulario->erro_sql);
        }

        db_msgbox("Salvo com sucesso!");

        $formulario = getFormulario($iCod);

    } catch (\Exception $ex) {
        db_msgbox($ex->getMessage());
    }

    unset($_POST["saveFile"]);
}

/**
 * EFETUAR DOWLOAND
 */
if (isset($oPost->download)) {
    file_put_contents(FILE_PATH, $formulario->p108_formulario);
    download();
    unset($_POST["download"]);
}

function download()
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename(FILE_PATH));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize(FILE_PATH));
    ob_clean();
    flush();
    readfile(FILE_PATH);
    exit();
}

function getFormulario($tipoProcesso)
{
    $Formulario = new cl_tipoprocessoformulario();
    $sql = $Formulario->sql_query(
        null,
        "*",
        "",
        "p108_tipoproc = {$tipoProcesso}",
        "",
        "");
    $result = $Formulario->sql_record($sql);
    return pg_fetch_object($result);
}

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

    <style>
        #buttonFile {
            width: 60px;
            height: 15px;
            background-color: #f1f0ee;
            text-align: center;
            display: block;
            cursor: pointer;
            border-radius: 3.5px;
            border: solid 1px #999999;
            float: left;
            position: relative;
            top: 2px;
            padding-top: 1px;
            padding-bottom: 1px;
            padding-left: 8px;
            padding-right: 8px;
        }

        span {
            margin-left: 5px;
            height: 20px;
            display: inline-block;
            padding-top: 4px;
        }

        #download {
            height: 20px;
            background-color: #f1f0ee;
            text-align: center;
            display: block;
            cursor: pointer;
            border-radius: 3px;
            border: solid 1px #999999;
        }
    </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

<div class="container" style="width: 500px">

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="p51_codigo" value="<?= $iCod ?>">
        <fieldset>
            <legend>Arquivo Json Formulário</legend>
            <table>
                <tr>
                    <td>
                        <strong>Tipo:</strong>
                    </td>
                    <td>
                        <select name="tipo" id="tipo">
                            <option value="">Selecione</option>
                            <option value="0" <?= (!empty($formulario->p108_rota) ? "selected='selected'" : "") ?>>Rota</option>
                            <option value="1" <?= ((empty($formulario->p108_rota) && !empty($formulario->p108_formulario)) ? "selected='selected'" : "") ?>>JSON</option>
                        </select>
                    </td>
                </tr>
                <tr id="rota" style="display: none">
                    <td>
                        <strong>Rota:</strong>
                    </td>
                    <td style="width: 100%">
                        <input type="text" style="width: 100%" name="p108_rota" id="p108_rota" value="<?= @$formulario->p108_rota ?>">
                    </td>
                </tr>
                <tr id="arquivo" style="display: none">
                    <td>
                        <strong>Arquivo:</strong>
                    </td>
                    <?php if (empty($formulario->p108_rota) && !empty($formulario->p108_formulario)) : ?>
                        <td>
                            <input type="submit" value="Download" name="download" id="download">
                        </td>
                    <?php endif; ?>
                    <td>
                        <label for="fileToUpload" id="buttonFile">Selecionar</label>
                        <span id="fileName"></span>
                        <input type="file" name="fileToUpload" id="fileToUpload" style="display: none" accept=".json">
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="submit" value="salvar" name="saveFile">
    </form>

</div>
<script>
    document.getElementById("tipo").addEventListener("change", function(event){
        js_mostraCampo(event.target.value);
    });

    document.getElementById("fileToUpload").addEventListener("change", function(event){
        if (event.target.files.length > 0) {
            document.getElementById("fileName").innerText = event.target.files[0].name;
        }
    });

    function js_mostraCampo(codigo)
    {
        if (parseInt(codigo) == 0) {
            document.getElementById("arquivo").hide();
            document.getElementById("rota").show();
        } else {
            if (parseInt(codigo) == 1) {
                document.getElementById("rota").hide();
                document.getElementById("p108_rota").value = "";
                document.getElementById("arquivo").show();
            } else {
                document.getElementById("rota").hide();
                document.getElementById("p108_rota").value = "";
                document.getElementById("arquivo").hide();
            }
        }
    }

    <?php if (!empty($formulario->p108_rota)) : ?>
        js_mostraCampo(0);
    <?php elseif (!empty($formulario->p108_formulario)) :?>
        js_mostraCampo(1);
    <?php endif; ?>
</script>
</body>
</html>
