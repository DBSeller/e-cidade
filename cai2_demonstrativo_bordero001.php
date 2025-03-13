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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_saltes_classe.php"));
require_once(modification("classes/db_corrente_classe.php"));

$oGet = db_utils::postMemory($_GET);

$clsaltes = new cl_saltes;
$clcorrente = new cl_corrente;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js");
    db_app::load("estilos.css");
    ?>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body class="body-default" onLoad="a=1">
<div class="container">
    <div style="margin-top: 30px; width: 650px;">
        <label><b>Demonstrativo de Borderô</b></label>
        <form name="form1" enctype="multipart/form-data" method="post" action="">
            <fieldset>
                <legend><strong> Dados para Emissão:</strong></legend>
                <table>
                    <tr>
                        <td nowrap="nowrap"><b>Conta: </b></td>
                        <td nowrap="nowrap" id="ctnCboContas"></td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap"><label for="dataInicial">Data Inicial: </label></td>
                        <td nowrap="nowrap">
                            <input type="text" id="dataInicial" name="dataInicial">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap"><label for="dataFinal">Data Final: </label></td>
                        <td nowrap="nowrap">
                            <input type="text" id="dataFinal" name="dataFinal">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap"><b>Tipo:</b></td>
                        <td nowrap="nowrap" id="ctnTipoRelatorio">
                            <select name="tipoRelatorio" id="tipoRelatorio">
                                <option value="1">Analítico</option>
                                <option value="0">Sintético</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="continuar" type="Button" id="continuar" value="Imprimir" onClick='js_abreConciliacao();'>
        </form>
    </div>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    var oUrl = js_urlToObject();
    var sRpc = 'con1_contabancaria.RPC.php';

    var oCboContas = new DBComboBox("cboContas", "oCboContas", null, "400px");
    oCboContas.addItem("", "Selecione uma Conta");
    oCboContas.show($('ctnCboContas'));

    const dataInicial = new DBInputDate(document.getElementById('dataInicial')),
        inputDataInicial = document.getElementById('dataInicial'),
        dataFinal = new DBInputDate(document.getElementById('dataFinal')),
        inputDataFinal = document.getElementById('dataFinal'),
        cboTipoRelatorio = document.getElementById('tipoRelatorio');
    js_pesquisaContas();

    function js_pesquisaContas() {

        var oObject = new Object();
        oObject.exec = "buscaContasComArquivos";

        if (oUrl.concilia && oUrl.concilia != "") {
            oObject.concilia = oUrl.concilia;
        }

        new Ajax.Request(sRpc, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oObject),
                onComplete: js_retornoContas
            }
        );
    }

    function js_retornoContas(oJson) {
        var oRetorno = JSON.parse(oJson.responseText);

        oCboContas.clearItens();
        oCboContas.addItem("", "Selecione uma Conta");
        oCboContas.addItem("", "TODAS AS CONTAS");
        oRetorno.contas.each(function (conta) {
            const contaBancaria = `${conta.sequencial} - ${conta.descricao}`;
            oCboContas.addItem(conta.sequencial, contaBancaria);
        });

        if (oRetorno.dados.length === 1) {
            oCboContas.setValue(oRetorno.dados[0].sequencial);
            oCboContas.setDisable(true);
        }
    }

    /**
     * Se selecionado a forma analitica de impresao do relatorio, apresenta opcao de apresentar justificativa
     */
    function js_getUltimoDiaMes(iMes, iAno) {
        if (checkleapyear(iAno)) {
            var fev = 29;
        } else {
            var fev = 28;
        }
        var dia = new Array(31, fev, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return dia[iMes - 1];
    }

    /**
     * Imprime o relatorio
     */
    function js_abreConciliacao() {
        var sData = dataInicial.__toLocaleDateString();
        var sDataf = dataFinal.__toLocaleDateString();

        if (empty(sData) || empty(sDataf)) {
            alert('Selecione uma conta e uma data');
            return false;
        }

        var iConta = oCboContas.getValue();

        var sUrl = 'cai2_demonstrativo_bordero002.php?';
        var sParametro = 'sDataIniConciliacao=' + sData + '&iConta=' + iConta;
        sParametro += '&sDataFimConciliacao=' + sDataf;
        sParametro += '&tipoRelatorio=' + cboTipoRelatorio.value;
        var oJanela = window.open(sUrl + sParametro, '', 'location=0');
    }
</script>
