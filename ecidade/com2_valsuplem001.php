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
require_once(modification("classes/db_solicita_classe.php"));
$clsolicita = new cl_solicita;
$clsolicita->rotulo->label();
db_postmemory($_POST);
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body onload="document.form1.pc10_numero.focus();">
<div class="container">
    <fieldset>
        <legend>Valores a suplementar</legend>
        <form name="form1">
            <table border='0'>
                <tr>
                    <td align="left" nowrap
                        title="<?= $Tpc10_numero ?>"> <?php db_ancora(@$Lpc10_numero, "js_pesquisapc10_numero(true);", 1); ?></td>
                    <td align="left" nowrap>
                        <?php
                        db_input('pc10_numero', 8, $Ipc10_numero, true, "text", 1, "onchange='js_pesquisapc10_numero(false);'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="left" nowrap title="Se tiver orçamentos, selecione o tipo"><b>Mostrar:</b></td>
                    <td align="left" nowrap>
                        <?php
                        $arr_comorc = array("0" => "Somente valores dos itens", "s" => "Valores orçados por solicitação", "p" => "Valores orçados por PC");
                        db_select("comorcam", $arr_comorc, true, 1);
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>

    <input name="enviar" type="button" id="enviar" value="Enviar dados" onclick='js_abre();'>
</div>
</body>
<?php db_menu(); ?>
</html>
<script>
    function js_abre() {
        qry = "";
        if (document.form1.comorcam.value != 0) {
            qry = "&comorcam=" + document.form1.comorcam.value;
        }
        jan = window.open('com2_valsuplem002.php?pc10_numero=' + document.form1.pc10_numero.value + qry, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }

    function js_pesquisapc10_numero(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_solicita', 'func_solicita.php?funcao_js=parent.js_mostrasolicita1|pc10_numero&nada=true', 'Pesquisa', true);
        } else {
            if (document.form1.pc10_numero.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_solicita', 'func_solicita.php?pesquisa_chave=' + document.form1.pc10_numero.value + '&funcao_js=parent.js_mostrasolicita&nada=true', 'Pesquisa', false);
            } else {
                document.form1.pc10_numero.value = '';
            }
        }
    }

    function js_mostrasolicita(chave, erro) {
        if (erro == true) {
            document.form1.pc10_numero.focus();
            document.form1.pc10_numero.value = '';
        }
    }

    function js_mostrasolicita1(chave1) {
        document.form1.pc10_numero.value = chave1;
        db_iframe_solicita.hide();
    }
</script>
