<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <style>
        .required:after {
            content:" *";
            color: red;
            font-size: large;
        }
    </style>
</head>
<body class="body-default">
<div>
    <form id="form-upload" method="post" action="" enctype="multipart/form-data" class="container">
        <fieldset>
            <legend style="font-size: 13px">
                Relatório Conferência Receita Extraorçamentária
            </legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="exercicio1">Data: </label>
                    </td>
                    <td>
                        <input id="exercicio1" name="exercicio1">
                    </td>
                    <td>
                        <label for="exercicio2">ate:</label>
                    </td>
                    <td>
                        <input id="exercicio2" name="exercicio2">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="estorno">Considerar estorno: </label>
                    </td>
                    <td>
                        <select id="estorno">
                            <option value="TRUE">Sim</option>
                            <option value="FALSE">Não</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" class="btn btn-light" id="btnexportar" onclick="gerarRelatorio()" style="font-size: 12px">
            Gerar Relatório
        </button>
    </form>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script>

    const data = new DBInputDate(document.getElementById('exercicio1'));
    const data2 = new DBInputDate(document.getElementById('exercicio2'));

    const routs = {
        exportar: 'financeiro/empenho/conferencia-extra-orcamentaria/exportar',
    }

    function gerarRelatorio() {
        PHPSession.loadData().then(() => {

            const formData = new FormData();
            formData.append('data1', data.__toLocaleDateString());
            formData.append('data2',data2.__toLocaleDateString());
            formData.append('tipo','Receita');
            formData.append('estorno',document.getElementById('estorno').value);
            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.exportar}`, {body: formData}).then(response => {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                if (!response.data) {
                    location.reload();
                }
                window.open(response.data.path, 'relatorio-conferencia-receita-extraorcamentaria', 'popup');
            });
        });
    }
</script>
