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
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_pcmater_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcmater = new cl_pcmater;
$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script>
        function js_consulta() {
            if (document.form1.pc01_codmater.value != "") {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', 'com3_consultaitem002.php?pc01_codmater=' + document.form1.pc01_codmater.value, '..::Consulta Item::..', true);
            } else {
                alert('Informe um Item!!');
                document.form1.pc01_codmater.focus();
            }
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
    <form name='form1'>
        <fieldset>
            <legend>Consultar Item</legend>
            <table class="form-container">
                <tr>
                    <td nowrap title="<?= @$Tpc01_codmater ?>" align="right">
                        <?php
                        db_ancora(@$Lpc01_codmater, "js_pesquisa(true);", 1);
                        ?>
                    </td>
                    <td>
                        <?php
                        db_input('pc01_codmater', 8, $Ipc01_codmater, true, 'text', 1, " onchange='js_pesquisa(false);'")
                        ?>
                        <?php
                        db_input('pc01_descrmater', 50, @$Ipc01_descrmater, true, 'text', 3, '')
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
    <input type='button' name='consultar' value='Consultar' onclick='js_consulta();'>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    function js_pesquisa(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcmater', 'func_pcmater.php?funcao_js=parent.js_mostra1|pc01_codmater|pc01_descrmater', 'Pesquisa', true);
        } else {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcmater', 'func_pcmater.php?pesquisa_chave=' + document.form1.pc01_codmater.value + '&funcao_js=parent.js_mostra', 'Pesquisa', false);
        }
        if (document.form1.pc01_codmater.value == "") {
            document.form1.pc01_descrmater.value = "";
        }
    }

    function js_mostra(nome, erro) {
        document.form1.pc01_descrmater.value = nome;
        if (erro == true) {
            document.form1.pc01_codmater.value = "";
            document.form1.pc01_codmater.focus();
        }
    }

    function js_mostra1(cod, nome) {
        document.form1.pc01_codmater.value = cod;
        document.form1.pc01_descrmater.value = nome;
        db_iframe_pcmater.hide();
    }
</script>
