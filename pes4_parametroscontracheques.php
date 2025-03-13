<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$mesFolha = DBPessoal::getMesFolha() - 1;
$anoFolha = DBPessoal::getMesFolha() == '01' ? DBPessoal::getAnoFolha() - 1 : DBPessoal::getAnoFolha();
?>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body class="body-default">
<div class="container">
    <fieldset>
        <legend>Emissão Contra cheques</legend>
        <table class="form-container" style="border-collapse: separate;">
            <tr>
                <td>
                    <label for="">Período: </label>
                </td>
                <td>
                    <input type="text" id="mes" value="<?=$mesFolha?>" size="3" /> /
                    <input type="text" id="ano" value="<?=$anoFolha?>" size="6" />
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" class="btn btn-light" id="button_emitir">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>

<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    var urlApi;
    window.addEventListener('load', () => {
        PHPSession.loadData().then(() => {
            urlApi = PHPSession.requestApi;
        });
    });
    const inputMes = document.getElementById("mes");
    const inputAno = document.getElementById("ano");
    const btnEmitir = document.getElementById("button_emitir");

    btnEmitir.addEventListener('click', () => {
        if (empty(inputMes.value)) {
            alert("Informe o Mês.");
            return;
        }
        if (empty(inputAno.value)) {
            alert("Informe o Ano.");
            return;
        }

        const formData = new FormData();
        formData.append('ano', inputAno.value);
        formData.append('mes', inputMes.value);
        PHPSession.appendFormData(formData);
        HttpClient.post(`${urlApi}/recursos-humanos/pessoal/contra-cheques/processar-competencia`, { body: formData })
            .then((response) => {
                alert(response.message);
            });
    });
</script>
</body>
</html>
