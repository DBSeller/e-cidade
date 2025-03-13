<?PHP
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
?>

<html lang="">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container" style="max-height: 600px; overflow: scroll;">

    <fieldset>
        <legend class="bold">Nota de Lançamento</legend>
        <table>
            <tr>
                <td>
                    <?php
                    db_ancora("Lançamento: ", "pesquisaLancamento()", 1);
                    ?>
                </td>
                <td>
                    <?php
                    db_input("codigo_lancamento", 10, 1, true, 'text', 3);
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <p>
        <input type="button" id="btnEmitirNota" onclick="emitirNota();" value="Emitir" />
    </p>
</div>
</body>
</html>
<?php db_menu(); ?>

<script>

    var inputLancamento = document.getElementById('codigo_lancamento');
    function emitirNota() {

        if (inputLancamento.value.trim() === '') {
            return alert("Lançamento é de preenchimento obrigatório.");
        }
        window.open('con2_notadelancamento002.php?lancamentos='+inputLancamento.value);
    }

    function pesquisaLancamento() {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conlancamlan','func_conlancamlan.php?funcao_js=parent.preencheLancamento|c70_codlan&codigoTipoDocumento=3000','Pesquisa Lançamentos',true);
    }

    function preencheLancamento(codigoLancamento) {

        inputLancamento.value = codigoLancamento;
        db_iframe_conlancamlan.hide();
    }
</script>



