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
    <script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body class="body-default">
<div class="alert alert-primary text-left" role="alert">
    Importe o arquivo gerado no sistema do Tramita.
</div>
<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Clique em <kbd><i class="fas fa-upload"></i> Arquivo</kbd> para importar</legend>
            <div id="ctnImportacao"></div>
        </fieldset>
        <button type="button" class="btn btn-light" id="importar" disabled>
            <i class="fa fa-save" aria-hidden="true"></i>
            Salvar
        </button>
    </form>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script>
    const btnImportar = document.getElementById('importar');
    const routs = {
        importar: 'patrimonial/licitacoes/tramita/importar'
    }

    const erroRetornoArquivo = mensagem => {
        alert(mensagem);
        btnImportar.disabled = true;
        fileUpload.clear();
    }

    function retornoEnvioArquivo(retorno) {
        if (retorno.error) {
            erroRetornoArquivo(retorno.error);
            return false;
        }

        if (retorno.extension.toLowerCase() != 'txt') {
            erroRetornoArquivo('Arquivo inválido! O arquivo deve ser um "txt".');
            return false;
        }

        btnImportar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));
    document.querySelector(".inputUploadFile").addClassName('field-size8');

    PHPSession.loadData().then(() => {
        btnImportar.addEventListener('click', () => {
            const formData = new FormData();
            formData.append('file', JSON.stringify({
                "extension": fileUpload.extension,
                "name": fileUpload.file,
                "path": fileUpload.filePath
            }));

            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.importar}`, {body: formData}).then(response => {
                if (response.error) {
                    alert(response.message);
                    return;
                }

                const download = new DBDownload();
                download.addFile(response.data.pdf, response.message);
                download.show();
            });
        });
    });
</script>
