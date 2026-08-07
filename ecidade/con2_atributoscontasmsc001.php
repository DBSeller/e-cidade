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
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>
<body class="body-default">
<div class="alert alert-primary text-left " role="alert">
    <b>Se desejar filtrar um grupo de contas, informe-as separando por vírgula.</b>
</div>
<div class="container">
    <form id="formulario" method="post" action="">
        <fieldset>
            <legend>Atributos Plano Contas MSC</legend>
            <table class="form-container">
                <tr>
                    <td id="ctnInstituicao" colspan="2" style="font-weight: normal">
                        <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                    </td>
                </tr>
                <tr class="text-left">
                    <td><label class="bold field-size1" for="estrutural">Contas:</label></td>
                    <td><input type="text" name="estrutural" id="estrutural" class="field-size4" maxlength="15"></td>
                </tr>
            </table>
        </fieldset>
        <button id="emitir" type="button">
            <i class="fas fa-print"></i>
            Emitir
        </button>
    </form>
</div>
</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script>
    const inputEstrutural = document.getElementById('estrutural');

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', $('ctnInstituicao'));
    viewInstituicao.show();

    const rout = 'financeiro/contabilidade/relatorio/atributos-plano-conas'

    document.getElementById('emitir').addEventListener('click', () => {
        const formData = new FormData(document.getElementById('formulario'));

        formData.append('instituicoes', JSON.stringify(viewInstituicao.getInstituicoesSelecionadas()));

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rout}`, {body: formData}).then((response) => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const download = new DBDownload();
            download.addFile(response.data.pdf, "Atributos Plano Contas MSC");
            download.show();
        });
    });
</script>
