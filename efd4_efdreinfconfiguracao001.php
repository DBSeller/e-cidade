<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Serviços de Informática Ltda</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container" style="width: 25%;">
    <form id="configForm">
        <fieldset>
            <legend>Configurações</legend>
            <table>
                <tbody>
                <tr>
                    <td>
                        <label for="efd07_filtraorgaounidade">
                            <strong>Filtro por Orgão Unidade: </strong>
                        </label>
                    </td>
                    <td>
                        <select name="efd07_filtraorgaounidade" id="efd07_filtraorgaounidade">
                            <option value="" selected disabled>Selecione</option>
                            <option value="true">Sim</option>
                            <option value="false">Não</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <input type="submit" value="Salvar">
    </form>
</div>
<script src="scripts/scripts.js"></script>
<script src="scripts/strings.js"></script>
<script src="scripts/prototype.js"></script>
<script src="scripts/classes/http/http.js"></script>
<script src="scripts/session.js"></script>
<script rel="script" type="text/javascript">

    // globals
    const url = "<?= ECIDADE_REQUEST_PATH ?>";
    const api = url + 'v4/api/integracoes/efd-reinf/configuracao/';

    // elements
    const configForm = document.querySelector('#configForm');
    const filtraorgaounidade = document.querySelector('#efd07_filtraorgaounidade');

    // listeners
    configForm.addEventListener('submit', sendConfigForm);

    // init
    PHPSession.loadData().then(() => {
        getConfig();
    })

    // methods
    async function sendConfigForm(event) {
        event.preventDefault();

        if (!validateConfigForm()) {
            return false;
        }

        let response = false;
        const action = api + 'save';
        const formData = new FormData(configForm);
        const body = {
            body: formData
        }

        PHPSession.appendFormData(formData);

        response = await HttpClient.post(action, body);
        if (response.error) {
            let msg = 'Erro ao salvar: ' + response.message;
            alert(msg);
            return false;
        }

        alert(response.message);
        getConfig();
    }

    async function getConfig() {
        const action = api + 'get';
        let response = false;
        const formData = new FormData();

        formData.append('get', true);
        PHPSession.appendFormData(formData);

        response = await HttpClient.post(action, {body: formData});
        if (response.error) {
            let msg = 'Erro ao buscar as configurações: ' + response.message;
            alert(msg);
            return false;
        }

        if (response.data) {
            fillConfigForm(response.data);
        }
    }

    // validations
    function validateConfigForm()
    {
        if (!filtraorgaounidade.value) {
            alert('Você deve informar <em>Filtro por Orgão Unidade</em>');
            return false;
        }

        return true;
    }

    // helpers
    function fillConfigForm(data) {
        filtraorgaounidade.value = data.efd07_filtraorgaounidade;
    }

</script>
</body>
</html>
