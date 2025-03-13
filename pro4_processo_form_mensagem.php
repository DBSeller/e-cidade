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

$oGet = db_utils::postMemory($_GET);
?>
<html lang="pt-br">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style>
        div.files {
            display: inline-block;
            background: #fff;
            padding: 5px;
            border-radius: 5px;
            border: solid #0a0a0a 1px;
        }

        div#arquivos-selecionados {
            width: 400px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body bgcolor="#CCCCCC" onLoad="a=1">
<div class="container">
    <form onsubmit="return false" id="form-mensagem">
        <input type="hidden" name="codigoAndamento" value="<?=$oGet->codigoAndamento ?>">
        <input type="hidden" name="codigoProcesso" value="<?=$oGet->codigoProcesso ?>">
        <b>Mensagem para o cidadão:</b><br>
        <textarea name="mensagem" style="width: 400px;height: 200px"></textarea>
        <br>
        <b>Arquivo:</b><br>
        <input type="file" name="arquivo" id="arquivo" multiple><br><br>
        <div id="arquivos-selecionados"></div>
        <button onclick="enviarMensagem()">Enviar Mensagem</button>
    </form>
</div>
<?php
try {
    if (empty($oGet->tipoMensagem)) {
        throw new \Exception("Tipo de mensagem não encontrado!");
    }

    if (!in_array($oGet->tipoMensagem, ["respostaPrefeitura", "mensagemPrefeitura"])) {
        throw new \Exception("Tipo de Mensagem Inválida!");
    }
} catch (\Exception $ex) {
    echo "<script>
              alert('{$ex->getMessage()}');
              parent.db_iframe_processo_form_mensagem.hide();
         </script>";
}
?>
<script>
    const action = '<?=$oGet->tipoMensagem?>';
    const FILE_RPC = 'pro4_andamento_processo.RPC.php';
    const fileInput = document.getElementById("arquivo");
    const divArquivosSelecionados = document.getElementById("arquivos-selecionados");
    var fileList = [];

    fileInput.addEventListener('change', function (e) {
        let i = 0;
        var data = new FormData();
        for (i; i < fileInput.files.length; i++) {
            data.append('anexos[]', fileInput.files[i]);
        }

        data.append('acao', 'prepararDocumentos');
        HttpClient.post(FILE_RPC, {body: data}).then(function (response) {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }
            response.documentos.map(function (documento) {
                fileList.push(Object.assign({}, documento));
                renderNamesFiles();
            });
        });
        fileInput.value = "";
    });

    function removeFile(indexFile) {
        fileList = fileList.filter(function (file, index) {
            console.log(index, indexFile, (index != indexFile))
            return index != indexFile;
        });
        renderNamesFiles();
    }

    function renderNamesFiles() {
        let html = [];

        fileList.forEach(function (file, index) {
            html.push(`
                  <div class='files'>${file.descricao} <a onclick="removeFile(${index})">X<a></div>
               `);
        });
        divArquivosSelecionados.innerHTML = html.join("");
    }


    function enviarMensagem() {

        const form = document.getElementById("form-mensagem");
        if(form.mensagem.value == ''){
            alert('Escreva uma mensagem!');
            return;
        }

        const data = new FormData();
        data.append('acao', action);
        data.append('codigoAndamento', form.codigoAndamento.value);
        data.append('codigoProcesso', form.codigoProcesso.value);
        data.append('mensagem', form.mensagem.value);
        if(form.codigoAndamento.value != ''){
            data.append('respostaOuvidoria', true);
        }

        var documentos = [];
        fileList.map(function (anexo) {
            documentos.push({
                'codigo': anexo.codigo,
                'descricao': anexo.descricao,
                'caminho': anexo.caminho
            });
        });

        data.append('despachoAnexos', JSON.stringify(documentos));
        HttpClient.post(FILE_RPC, {body: data}).then(function (response) {
            if(response.erro){
                alert(response.mensagem);
                return;
            }
            parent.db_iframe_processo_mensagem.janFrame.contentWindow.getMensagens();
            parent.db_iframe_processo_form_mensagem.hide();
        }).catch(mensagem => mensagem ? mensagem.message ? alert(mensagem.message) : alert(mensagem) : null);
    }

</script>
</body>
</html>
