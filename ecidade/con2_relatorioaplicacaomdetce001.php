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
<div class>
    <form id="form-upload" method="post" action="" enctype="multipart/form-data" class="container" >
        <fieldset>
            <legend>
                Relatório Gerencial de Aplicação em MDE na regra do TCE-PB
            </legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="mes" class="required">Mês: </label>
                    </td>
                    <td>
                        <select id="mes" style="width: 160px" onchange="buscar()">
                            <option value="0">Selecione</option>
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="adicaoAuditoria">Adição Auditoria: </label>
                    </td>
                    <td>
                        <input type="number" id="adicaoAuditoria" onkeypress="validarNumero(event)">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="exclusaoAuditoria">Exclusão Auditoria: </label>
                    </td>
                    <td>
                        <input type="number" id="exclusaoAuditoria" onkeypress="validarNumero(event)">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="restosPagar">Restos a Pagar: </label>
                    </td>
                    <td>
                        <input type="number" id="restosPagar" onkeypress="validarNumero(event)">
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" class="btn btn-light" id="btnrelatorio" onclick="gerarRelatorio()">
            Gerar relatório
        </button>
    </form>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script>

    const anousu = '<?php echo db_getsession('DB_anousu') ?>';

    const routs = {
        exportar: 'financeiro/contabilidade/relatorio-tce/exportar',
        buscar: 'financeiro/contabilidade/relatorio-tce/buscar'
    }

    const inputMes = document.getElementById('mes');
    const adicaoAuditoria = document.getElementById('adicaoAuditoria');
    const exclusaoAuditoria = document.getElementById('exclusaoAuditoria');
    const restosPagar = document.getElementById('restosPagar');

    function gerarRelatorio() {

        PHPSession.loadData().then(() => {

                if (empty(inputMes.value) || inputMes.value=='0') {
                    alert('Informe o mês!');
                    return;
                }

                const formData = new FormData();
                formData.append('mes', inputMes.value);
                formData.append('adicaoAuditoria',adicaoAuditoria.value);
                formData.append('exclusaoAuditoria',exclusaoAuditoria.value);
                formData.append('resto',restosPagar.value);
                formData.append('anousu',anousu);
                PHPSession.appendFormData(formData);
                HttpClient.post(`${PHPSession.requestApi}/${routs.exportar}`, {body: formData}).then(response => {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }
                    if (!response.data) {
                        location.reload();
                    }
                    window.open(response.data.path, 'relatorio-aplicacao-mde', 'popup');
                });
        });
    }

    function validarNumero(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    function buscar(){
        PHPSession.loadData().then(() => {
            const formData = new FormData();
            formData.append('mes', inputMes.value);
            formData.append('anousu', anousu);
            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.buscar}`, {body: formData}).then(response => {
                if (response.error) {
                    alert.response.error
                    return;
                }
                if (!response.data) {
                    location.reload();
                }
                if(response.data.length){
                    preencheCampos(response.data[0]);
                }else{
                    limparCampos();
                }
            });
        });
    }

    function preencheCampos(dados)
    {
        adicaoAuditoria.value = dados.c170_adicaoauditoria;
        exclusaoAuditoria.value = dados.c170_exclusaoauditoria
        restosPagar.value = dados.c170_resto;
    }

    function limparCampos(){
        adicaoAuditoria.value = '';
        exclusaoAuditoria.value = '';
        restosPagar.value = '';
    }

</script>
