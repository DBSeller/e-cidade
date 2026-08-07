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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("libs/db_utils.php"));


?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;">
<div class="container">

    <form method="POST" id="formImportarArquivo" class="form-container">
        <fieldset style="width: 400px">
            <legend class="bold">Importação do Saldo Inicial</legend>
            <table>
                <tr>
                    <td class="bold"><label for="arquivo_msc">Arquivo:</label></td>
                    <td>
                        <input id="arquivo_msc" name="arquivo_msc" value="" type="file"/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <p>
            <input type="button" value="Enviar Arquivo" id="btnEnviarArquivo" onclick="enviarArquivo()"/>&nbsp;
            <input type="button" value="Remover Arquivo Importado" id="btnRemoverArquivo" onclick="removerArquivo()"/>
        </p>
    </form>

</div>
</body>
</html>
<script>

    const ARQUIVO_RPC = 'con4_importacaosaldoinicialMSC.RPC.php';

    function enviarArquivo() {

        AjaxRequest.create(
            ARQUIVO_RPC,
            {
                'exec': 'enviarArquivo',
            },
            function (retorno, erro) {
                alert(retorno.mensagem);
                $('arquivo_msc').value = '';
            }
        ).addFileInput($('arquivo_msc')).execute();

    }

    function removerArquivo() {

        if (!confirm('Confirma a remoção das informações do arquivo importado?')) {
            return false;
        }

        AjaxRequest.create(
            ARQUIVO_RPC,
            {
                'exec': 'removerArquivo',
            },
            function (retorno, erro) {
                alert(retorno.mensagem);
            }
        ).execute();
    }


</script>
