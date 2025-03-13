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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form-upload" method="post" action="" enctype="multipart/form-data">
    <div class="container">
        <fieldset>
            <legend><b>Importação Codigo INEP - Docente/ Aluno</b></legend>
            <table class="form-container">
                <tr>
                    <td>
                        <b>Ano:</b>
                    </td>
                    <td>
                        <input type="text" id="ano" name="ano" class="field-size2"/>
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Clique no botão "Arquivo" e selecione o arquivo</legend>
                <div id="ctnImportacao"></div>
            </fieldset>
        </fieldset>

        <input type="button" id="btnProcessar" name="btnProcessar" value="Importar"/>
    </div>
</form>

<?php
db_menu();
?>
<div id='uploadIframeBox' style='display:none'></div>
</body>
</html>
<script type="text/javascript">
    const dataAtual = new Date();
    $('ano').value = dataAtual.getFullYear();

    const formulario = $('form-upload');
    const btnProcessar = $('btnProcessar');

    function retornoEnvioArquivo(retorno) {

        if (retorno.error) {

            alert(retorno.error);
            btnProcessar.disabled = true;
            return false;
        }

        if (retorno.extension.toLowerCase() != 'txt') {
            alert('Arquivo inválido, extensão do arquivo não é "txt".');
            btnProcessar.disabled = true;
            return false;
        }

        btnProcessar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));

    document.querySelector(".inputUploadFile").addClassName('field-size8');

    btnProcessar.addEventListener('click', function () {
        const formData = new FormData(formulario);
        formData.append('acao', 'importarArquivoIdentificacao');
        formData.append('file', JSON.stringify({
            "extension": fileUpload.extension,
            "name": fileUpload.file,
            "path": fileUpload.filePath
        }));

        HttpClient.post('edu4_novoCenso.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }
        });
    });
</script>
