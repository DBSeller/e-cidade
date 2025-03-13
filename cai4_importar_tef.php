<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
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
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form-upload" method="post" action="" enctype="multipart/form-data">
    <div class="container">
        <fieldset>
            <legend>Clique no botão "Arquivo" e selecione o arquivo do TEF</legend>
            <div id="ctnImportacao"></div>
        </fieldset>
        <button type="button" id="btnImportar" name="btnImportar" disabled class="btn btn-light">
            <i class="fas fa-upload"></i>
            Importar
        </button>
    </div>
</form>

<?php
db_menu();
?>
</body>
</html>
<script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript">

    const rota = 'financeiro/tesouraria/importar';
    const btnImportar = document.getElementById('btnImportar');
    const formulario = document.getElementById('form-upload');

    function retornoEnvioArquivo(retorno) {

        if (retorno.error) {
            alert(retorno.error);
            btnImportar.disabled = true;
            return false;
        }

        if (retorno.extension.toLowerCase() != 'csv') {
            alert('Arquivo inválido, extensão do arquivo não é "csv".');
            btnImportar.disabled = true;
            return false;
        }

        btnImportar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show(document.getElementById('ctnImportacao'));

    document.querySelector('input[type="text"]').style.width = '450px';

    PHPSession.loadData().then(() => {
        btnImportar.addEventListener('click', function () {

            if (fileUpload.file == undefined) {
                alert('Selecione um arquivo.');
                return;
            }

            const formData = new FormData(formulario);
            formData.append('extension', fileUpload.extension);
            formData.append('file', fileUpload.file);
            formData.append('path', fileUpload.filePath);
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then((response) => {
                alert(response.message);
                if (response.error) {
                    return;
                }

                location.href = 'cai4_importar_tef.php';
            });
        });
    });
</script>
