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
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcsuplem_classe.php"));

$clorcsuplem = new cl_orcsuplem;
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
    <div style="margin-top: 25px; width: 280px;">
        <form name="form1" method="post" action="">
            <fieldset>
                <legend><b>Emissão da Suplementação por template</b></legend>
                <table border="0" style="width: 270px; margin: 0px; padding: 0px;">
                    <tr>
                        <td nowrap title="<?= @$To46_codlei ?>">
                            <?php
                            db_ancora("<b>Projeto de Lei:</b>", "js_projeto(true);", 1);
                            ?>
                        </td>
                        <td nowrap>
                            <?php
                            db_input('o46_codlei', 8, "", true, 'text', 3);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Template:</b>
                        </td>
                        <td id="tdComboBoxTemplate">
                            <select id="oComboBoxTemplate" disabled="disabled">
                                <option value="0" id="option_null" selected="selected">Selecione o projeto de lei...
                                </option>
                            </select>
                            <input type="text" id='template_tipo' style="display: none;">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Formato de Impressão:</strong>
                        </td>
                        <td>
                            <?php
                            $x = array('1' => 'PDF',
                                '2' => 'DOC');
                            db_select('iFormatoSaida', $x, true, $db_opcao, "");
                            ?>

                        </td>
                    </tr>


                </table>
            </fieldset>
            <br/>
            <input type="button" id='imprimir' name='imprimir' value='Imprimir' onclick='js_imprimir();'
                   disabled="disabled"/>
        </form>
    </div>
</center>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

    var sUrlRpc = "orc2_suplementacaoprojeto.RPC.php";
    $('o46_codlei').value = '';

    var templates = [];

    /**
     * função de pesquisa para os projetos de lei
     */
    function js_projeto(lMostra) {

        var sUrl = "func_orcprojeto001.php?";
        if (lMostra) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprojeto',
                sUrl + 'funcao_js=parent.js_mostraProjeto|o39_codproj',
                'Pesquisa', true);
        }
    }

    function js_mostraProjeto(chave, erro) {

        $('o46_codlei').value = chave;
        db_iframe_orcprojeto.hide();
        if (erro) {

            $('o46_codlei').focus();
            $('o46_codlei').value = '';
        }
        js_verificaTipoTemplates(chave);
    }

    /**
     * Carrega os documentos templates para o tipo de suplementacao do projeto
     */
    function js_verificaTipoTemplates(iCodigoProjeto) {

        var oObject = new Object();
        oObject.exec = "buscaTemplates";
        oObject.iCodigoProjeto = iCodigoProjeto;
        js_divCarregando('Buscando ...', 'msgBox');
        var objAjax = new Ajax.Request(sUrlRpc, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oObject),
                onComplete: js_retornoTipoTemplates
            }
        );
    }

    /**
     * Retorno da busca pelos tipos de templates
     */
    function js_retornoTipoTemplates(oJson) {

        js_removeObj("msgBox");
        var oRetorno = JSON.parse(oJson.responseText);

        if (oRetorno.status == 2) {

            alert(oRetorno.message.urlDecode());
            return false;
        }

        $("oComboBoxTemplate").options.length = 0;

        oRetorno.dados.each(function (oObjeto, iLinha) {

            templates.push(oObjeto);
            var oOption = document.createElement("option");
            oOption.value = oObjeto.iCodigo;
            oOption.innerHTML = oObjeto.sDescricao.urlDecode();

            $("oComboBoxTemplate").appendChild(oOption);
        });
        $("oComboBoxTemplate").disabled = false;
        $('imprimir').disabled = false;
    }


    /**
     * função para imprimir
     */
    function js_imprimir() {

        if ($F('o46_codlei') == "") {

            alert("Selecione um Projeto.");
            return false;
        }

        iModeloImpressao = $F('oComboBoxTemplate');
        modeloSelecionado = templates.filter((data) => {
            return data.iCodigo == iModeloImpressao;
        }).shift();
        iTipoImpressao = modeloSelecionado.iTemplate;

        var sUrl = 'orc2_suplementacaoprojeto002.php?o46_codlei=' + $F('o46_codlei');
        sUrl += '&iModeloImpressao=' + iModeloImpressao;
        sUrl += '&iTipoImpressao=' + iTipoImpressao;
        sUrl += '&iFormatoSaida=' + $F("iFormatoSaida");

        jan = window.open(sUrl, '',
            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
        jan.moveTo(0, 0);
    }
</script>
