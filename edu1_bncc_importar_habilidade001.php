<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <style>
        input[type='file'] {
            display: none
        }
        .input-wrapper {
            padding-top: 3px;
        }
        .input-wrapper label {
            background-color: #3498db;
            border-radius: 5px;
            color: #fff;
            padding: 6px 10px
        }

        .input-wrapper label:hover {
            background-color: #2980b9
        }

    </style>
</head>
<body class="body-default">
<div class="container">
    <form id="frmImportarHabilidades" method="post" action="">
        <fieldset>
            <legend>Importa o arquivo de Habilidades.</legend>
            <div class="alert alert-primary text-left" role="alert">
                Informe a planilha em formato .csv
            </div>
            <table class="form-container">
                <tr>
                    <td><label for="tipo_planilha">Tipo da Planilha:</label></td>
                    <td>
                        <select id="tipo_planilha" name="tipo_planilha">
                            <option value="EI">Ensino Infantil</option>
                            <option value="EF">Ensino Fundamental</option>
                            <option value="EI_REFERENCIAL_GAUCHO">EI - Referencial Gaúcho</option>
                            <option value="EF_REFERENCIAL_GAUCHO">EF - Referencial Gaúcho</option>
                        </select>
                    </td>
                </tr>
                <tr class="input-wrapper">
                    <td><label for='input-file'>Selecionar um arquivo</label></td>
                    <td class="file-label">
                        <input id='input-file' name="planilha" type='file' value='' />
                        <input type="text" id='file-name' readonly>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button id="btnProcessar" type="button">
            <i class="fas fa-cogs"></i>
            Processar
        </button>
    </form>

</div>
</body>
<script>
    const formulario = document.getElementById('frmImportarHabilidades'),
          cboTipo = document.getElementById('tipo_planilha'),
          btnProcessar = document.getElementById('btnProcessar');

    const inputFile = document.getElementById('input-file'),
          fileName = document.getElementById('file-name');

    inputFile.addEventListener('change', function() {
        fileName.value = this.files[0].name;
    });

    cboTipo.addEventListener('change', () => {
        fileName.value = '';
    });

    btnProcessar.addEventListener('click', () => {
        const formData = new FormData(formulario);
        formData.append('acao', 'processar');
        HttpClient.post('edu4_planilha_habilidades.RPC.php', {body: formData}).then(response => {

            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            const ensino = cboTipo.options[cboTipo.selectedIndex].innerHTML;
            const download = new DBDownload();
            download.addFile(response.arquivo_dump, `Dump ${ensino}`);
            download.show();
        })
    });

</script>
