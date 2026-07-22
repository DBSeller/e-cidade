<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
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
        <legend>Controle de Estoque (Movimentações)</legend>
        <table class="form-container" style="border-collapse: separate;">
            <tr>
                <td><label for="material_codigo" id="material_ancora">Código do Material: </label></td>
                <td>
                    <input type="text" id="m60_codmater" />
                    <input type="text" id="m60_descr" />
                </td>
            </tr>
            <tr>
                <td><label for="deposito_codigo" id="deposito_ancora">Depósito: </label></td>
                <td>
                    <input type="text" id="m91_codigo" />
                    <input type="text" id="descrdepto" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Período: </label>
                </td>
                <td>
                    <input type="date" id="data_inicial"> a
                    <input type="date" id="data_final">
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" class="btn btn-light" id="button_emitir">
        <i class="fas fa-print"></i>
        Emitir relatório
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

    const materialAncora = document.getElementById("material_ancora");
    const materialCodigo = document.getElementById("m60_codmater");
    const materialDescricao = document.getElementById("m60_descr");
    const depositoAncora = document.getElementById("deposito_ancora");
    const depositoCodigo = document.getElementById("m91_codigo");
    const depositoDescricao = document.getElementById("descrdepto");
    const inputDataInicial = new DBInputDate(document.getElementById("data_inicial"));
    const inputDataFinal = new DBInputDate(document.getElementById("data_final"));
    const btnEmitir = document.getElementById("button_emitir");

    new DBLookUp(materialAncora, materialCodigo, materialDescricao, {
        'sArquivo': 'func_matmater.php',
        'sLabel': 'Pesquisar Materiais',
        'sObjetoLookUp': "db_iframe_materiais"
    });

    const retornoPesquisaDeposito = (campo1, campo2, codigo, descricao) => {
        if (empty(codigo) && empty(descricao)) {
            return;
        }
        depositoCodigo.value = codigo;
        depositoDescricao.value = descricao;
    };

    new DBLookUp(depositoAncora, depositoCodigo, depositoDescricao, {
        'sArquivo': 'func_db_almox.php',
        'sLabel': 'Pesquisar Depósitos',
        'sObjetoLookUp': "db_iframe_depositos",
        'aCamposAdicionais': ['m91_codigo', 'descrdepto'],
        'fCallBack': retornoPesquisaDeposito
    });



    btnEmitir.addEventListener('click', () => {
        if (empty(materialCodigo.value)) {
            alert("Código do material inválido.");
            return;
        }
        if (inputDataInicial.value != null &&
            inputDataFinal.value != null &&
            inputDataInicial.value > inputDataFinal.value) {
            alert('Data inicial não pode ser maior que a data final.');
            return;
        }

        const formData = new FormData();
        formData.append('materialCodigo', materialCodigo.value);
        formData.append('depositoCodigo', depositoCodigo.value);
        formData.append(
            'dataInicial',
            inputDataInicial.__toLocaleDateString() ? js_formatar(inputDataInicial.__toLocaleDateString(), 'd') : ''
        );
        formData.append(
            'dataFinal',
            inputDataFinal.__toLocaleDateString() ? js_formatar(inputDataFinal.__toLocaleDateString(), 'd') : ''
        );
        PHPSession.appendFormData(formData);
        HttpClient.post(`${urlApi}/patrimonial/material/relatorios/controle-estoque`, { body: formData })
            .then((response) => {
            if (response.error) {
                alert(response.message);
                return;
            }
            window.open(
                response.data.file,
                '',
                'height=' + screen.height + ',width=' + screen.width + 'scrollbars=1,location=0'
            );
        });
    });
</script>
</body>
</html>
