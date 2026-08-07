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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
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
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css">
    <style type="text/css">
        table#tabelaArquivo, table#tabelaArquivo tbody, table#tabelaArquivo tr, table#tabelaArquivo td {
            border-collapse: collapse;
            border: 1px solid #2c5676;
        }
        #tabelaArquivo td {
            padding: 5px 10px;
        }
        table#tabelaArquivo thead {
            background-color: #2c5676;
            color: #fff;
            font-weight: bold;
        }

        table#tabelaArquivo tbody tr:nth-child(odd) {
            background-color:  #d3d9df;
        }

        #listaColunas, dd::after {
            font-family: "Font Awesome 5 Free"; font-weight: 400; content: "\f070";

        }


        #listaColunas, dd::before {
            font-family: "Font Awesome 5 Free"; font-weight: 400; content: "\f06e";
        }

    </style>
</head>
<body bgcolor="#cccccc">
<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Importar XML Sigap Fiscal</legend>
            <fieldset class="separator">
                <legend>Clique no botão "Arquivo" e selecione o arquivo</legend>
                <div id="ctnImportacao"></div>
            </fieldset>
        </fieldset>

        <input type="button" id="btnProcessar" value="Processar">
    </form>
</div>
<div id="containerArquivo" style="">
    <dl id="listaColunas">

    </dl>

    <table id="tabelaArquivo">

    </table>
</div>
<?php db_menu(); ?>
</body>
<script type="text/javascript">

    const formulario = $('form-upload');
    const btnProcessar = $('btnProcessar');
    const divArquivo = $('containerArquivo');
    const tabelaArquivo = $('tabelaArquivo');
    const listaColunas = $('listaColunas');

    var linhasXML = [];

    function retornoEnvioArquivo(retorno) {
        if (retorno.error) {
            alert(retorno.error);
            btnProcessar.disabled = true;
            return false;
        }

        if (retorno.extension.toLowerCase() != 'xml') {
            alert('Arquivo inválido, extensão do arquivo não é "xml".');
            btnProcessar.disabled = true;
            return false;
        }

        btnProcessar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));

    btnProcessar.addEventListener('click', function () {
        if (empty(fileUpload.file)) {
            alert("Informe um arquivo.");
        }

        const formData = new FormData(formulario);
        formData.append('acao', 'validarXML');
        formData.append('file', JSON.stringify({
            "extension": fileUpload.extension,
            "name": fileUpload.file,
            "path": fileUpload.filePath
        }));

        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            console.log(response.csv, !empty(response.csv) );
            if (!empty(response.csv)) {
                dowloadArquivoCenso(response.csv, response.csv_nome);
            }

            linhasXML = response.linhas;
            createTable(response.linhas);
        });
    });

    const createTable = (linhas) => {
        const tBody = tabelaArquivo.createTBody();
        linhas.forEach(function (dados, input) {
            if (input === 0) {
                imprimeHeader(dados);
            } else {
                imprimeLinha(tBody, dados);
            }
        });
    };

    const imprimeHeader = (labelsCabecalho) => {
        const header = tabelaArquivo.createTHead();
        const row = header.insertRow();

        labelsCabecalho.forEach((label) => {
            const cell = row.insertCell();
            cell.innerHTML = label;
            const identificador = criaIdentificador(cell.cellIndex);
            cell.setAttribute('class', identificador);
            // createListaColuna(label, identificador);
        });
    };

    const imprimeLinha = (tBody, dados) => {
        const row = tBody.insertRow();

        dados.forEach((dado) => {
            const cell = row.insertCell();
            cell.innerHTML = dado;
            cell.setAttribute('class', criaIdentificador(cell.cellIndex));
        });
    };

    const criaIdentificador = (index) => {
        return `cell_${index}`;
    };

    const createListaColuna = (label, identificador) => {
        const item = document.createElement('dd');
        item.setAttribute('data-id', identificador);
        item.innerHTML = label;
        listaColunas.appendChild(item);
    };

    dowloadArquivoCenso = (arquivo, label) => {
        var oDownload = new DBDownload();
        oDownload.addFile(arquivo, label);
        oDownload.show();
    };
</script>
