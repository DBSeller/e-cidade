<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body class="body-default">
<div class="container">
    <fieldset>
        <legend>Parâmetros de Notificações por Email</legend>
        <input type="hidden" id="codigo" />
        <table class="form-container">
            <tr>
                <td><input type="checkbox" id="notificar-escolas"/></td>
                <td><label for="notificar-escolas">Enviar email para Escolas (Origem/Destino)</label></td>
            </tr>
            <tr>
                <td><input type="checkbox" id="notificar-secretaria"/></td>
                <td><label for="notificar-secretaria">Enviar email para Secretaría de Educação</label></td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <table class="form-container">
            <tr>
                <td><label for="email-secretaria">E-mail da Secretaria de Educação: </label></td>
                <td><input type="text" id="email-secretaria"/></td>
            </tr>
        </table>
    </fieldset>
    <button type="button" class="btn btn-light" id="btn-salvar">
        <i class="fa fa-save"></i>
        Salvar
    </button>
</div>
<?php db_menu() ?>
<script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    const inputCodigo = document.querySelector('#codigo');
    const checkNotificarEscolas = document.querySelector('#notificar-escolas');
    const checkNotificarSecretaria = document.querySelector('#notificar-secretaria');
    const inputEmailSecretaria = document.querySelector('#email-secretaria');
    const btnSalvar = document.querySelector('#btn-salvar');

    PHPSession.loadData().then(() => {
        HttpClient.get(`${PHPSession.requestApi}/educacao/secretaria/parametros/notificacoes`).then(response => {
            let parametros = response.data;
            inputCodigo.value = parametros.ed177_codigo;
            checkNotificarEscolas.checked = parametros.ed177_notificar_escolas;
            checkNotificarSecretaria.checked = parametros.ed177_notificar_secretaria;
            inputEmailSecretaria.value = parametros.ed177_email_secretaria;
        });
    });

    btnSalvar.addEventListener('click', () => {
        if (checkNotificarSecretaria.checked && empty(inputEmailSecretaria.value)) {
            alert("Para notificar a secretaría de educação, deve ser preenchido o e-mail.");
            return;
        }

        const formData = new FormData;
        formData.append('codigo', inputCodigo.value);
        formData.append('notificar-escolas', checkNotificarEscolas.checked);
        formData.append('notificar-secretaria', checkNotificarSecretaria.checked);
        formData.append('email-secretaria', inputEmailSecretaria.value);
        PHPSession.appendFormData(formData);

        let url = `${PHPSession.requestApi}/educacao/secretaria/parametros/notificacoes`;
        HttpClient.post(url, {body: formData}).then(response => {
            alert(response.message);
        });
    });
</script>
</body>
</html>
