<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));


?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
    .atencao {
        background-color: yellow;
        border: 1px solid grey;
        padding: 3px;
        text-align: left;
    }
</style>
<body>
<div class="container">
    <fieldset>
        <legend class="bold">Importar Complementos</legend>
        <table>
            <tr>
                <td class="bold" style="width: 100px;"><label for="arquivo">Arquivo:</label></td>
                <td><input type="file" name="arquivo" id="arquivo" value="" /></td>
            </tr>
        </table>
        <div class="atencao">O arquivo deve ser do tipo <b>.csv</b> e com separador <b>, (vírgula)</b>.</div>
    </fieldset>
    <p>
        <input type="button" name="btnImportar" id="btnImportar" value="Importar" onclick="importar()" />
    </p>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
    db_getsession("DB_instit"));
?>
</body>
</html>

<script>

    var botaoImportar = document.querySelector('#btnImportar');

    function importar() {

        if ($F("arquivo") === '') {
            alert("Informe um arquivo.");
            return false;
        }

        if (!confirm('Confirma a importação do arquivo selecionado?')) {
            return false;
        }
        botaoImportar.disabled = true;

        AjaxRequest.create(
            'con4_importacaoplanilhacomplemento002.php',
            {'exec' : 'importar' },
            function (response, erro) {

                botaoImportar.disabled = false;
                alert(response.mensagem);
                $("arquivo").value = '';
            }
        ).addFileInput($("arquivo")).execute();
    }
</script>
