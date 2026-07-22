<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_app.utils.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$clrotulo->label("y27_codtipo");
$clrotulo->label("y27_descr");

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <form name="form1" method="post">
            <input type="hidden" name="geraDebito">
            <fieldset>
                <legend>Lançamento de Taxa</legend>
                <table class="form-container">
                    <tr>
                        <td title="<?= @$Tz01_numcgm ?>" style="width: 70px;">
                            <?= db_ancora("<strong>Nome/Razão Social:</strong>", "js_pesquisaCgm(true);", 4); ?>
                        </td>
                        <td>
                            <?
                            db_input("z01_numcgm", 5, @$Iz01_numcgm, true, "text", 1, "onchange='js_pesquisaCgm(false);'");
                            db_input("z01_nome", 30, false, true, "text");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td title="<?= @$Tj01_matric ?>" style="width: 70px;">
                            <?= db_ancora(@$Lj01_matric, "js_pesquisaMatricula(true);", 4); ?>
                        </td>
                        <td>
                            <?
                            db_input("j01_matric", 5, @$Ij01_matric, true, "text", 1, "onchange='js_pesquisaMatricula(false);'");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td title="<?= @$Tq02_inscr ?>" style="width: 70px;">
                            <?= db_ancora(@$Lq02_inscr, "js_pesquisaInscricao(true);", 4); ?>
                        </td>
                        <td>
                            <?
                            db_input("q02_inscr", 5, @$Iq02_inscr, true, "text", 1, "onchange='js_pesquisaInscricao(false);'");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70px;">
                            <strong>Taxas:</strong>
                        </td>
                        <td id="tdTaxas"></td>
                    </tr>
                    <tr>
                        <td style="width: 70px;">
                            <strong>Quantidade:</strong>
                        </td>
                        <td>
                            <input type="text" name="quantidade" id="quantidade" size="5" isMandatory="true" labelValidacao="Quantidade" onkeyup="js_calculaValor()">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70px;">
                            <strong>Valor Unitário R$:</strong>
                        </td>
                        <td>
                            <input type="text" name="valor" id="valor" isMandatory="true" labelValidacao="Valor" size="5" onkeypress="return js_adicionaMascaraMoeda(this,'.',',',event, 'js_calculaValor')">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70px;">
                            <strong>Valor Total R$:</strong>
                        </td>
                        <td>
                            <input type="text" name="valorFinal" id="valorFinal" disabled size="7">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70px;">
                            <strong>Data para Vencimento:</strong>
                        </td>
                        <td>
                            <?
                                db_inputdata("dataVencimento", "", "", "", true, 'text', 1)
                            ?>
                        </td>
                    </tr>
                    <tr id="trFiscalizacao" style="display: none;">
                        <td style="width: 70px;">
                            <?= db_ancora("<strong>Tipo de Fiscalização:</strong>", "js_pesquisaFiscalização(true);", 4); ?>
                        </td>
                        <td>
                            <?
                                db_input('y27_codtipo', 5, $Iy27_codtipo, true, 'text', 1, "onchange='js_pesquisaFiscalização(false);'");
                                db_input('y27_descr', 30, $Iy27_descr, true, 'text', 3, "");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <fieldset>
                                <legend><strong>Histórico</strong></legend>
                                <textarea name="historico" id="historico" cols="30" rows="10" labelValidacao="Histórico" isMandatory="true"></textarea>
                            </fieldset>
                        </td>
                    </tr>
                     <tr>
                        <td colspan="2" id="tdCamposDinamicos"></td>
                    </tr>
                </table>
            </fieldset>
            <input name="processar" id="processar" type="button" onclick="js_geraRecibo();" value="Processar">
        </form>
    </div>
    <? db_menu(); ?>
</body>

</html>
<script>
    document.getElementById("z01_numcgm").removeAttribute("isMandatory");
    document.getElementById("j01_matric").removeAttribute("isMandatory");
    document.getElementById("q02_inscr").removeAttribute("isMandatory");
    document.getElementById("dataVencimento").setAttribute("labelValidacao", "Data para Vencimento");
    document.getElementById("dataVencimento").setAttribute("isMandatory", "true");
    document.getElementById("y27_codtipo").setAttribute("labelValidacao", "Tipo de Fiscalização");

    var campoCalendario = "";

    function js_pesquisaCgm(mostra) {
        js_limpaCampos("C");

        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nome','func_nome.php?testanome=true&funcao_js=parent.js_mostraCgm1|0|1','Pesquisa',true);
        }else{
            if (document.form1.z01_numcgm.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nome','func_nome.php?testanome=true&pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostraCgm','Pesquisa',false);
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostraCgm(erro, chave) {
        document.form1.z01_nome.value = chave;

        if (erro == true) {
            document.form1.z01_numcgm.value = '';
            document.form1.z01_numcgm.focus();
        }
    }

    function js_mostraCgm1(chave1, chave2) {
        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_nome.hide();
    }

    function js_pesquisaMatricula(mostra) {
        js_limpaCampos("M");

        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matricula', 'func_iptubase.php?funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome', 'Pesquisa', true);
        } else {
            if (document.form1.j01_matric.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matricula', 'func_iptubase.php?pesquisa_chave=' + document.form1.j01_matric.value + '&funcao_js=parent.js_mostraMatricula1', 'Pesquisa', false);
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostraMatricula(chave1, chave2) {
        document.form1.j01_matric.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_matricula.hide();
    }

    function js_mostraMatricula1(chave, erro) {
        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.j01_matric.focus();
            document.form1.j01_matric.value = '';
        }
    }

    function js_pesquisaInscricao(mostra) {
        js_limpaCampos("I");

        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', 'func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome|q02_dtbaix', 'Pesquisa', true);
        } else {
            if (document.form1.q02_inscr.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', 'func_issbase.php?pesquisa_chave=' + document.form1.q02_inscr.value + '&funcao_js=parent.js_mostraInscricao', 'Pesquisa', false);
            } else {
                document.form1.q02_inscr.value = '';
            }
        }
    }

    function js_mostraInscricao(chave1, chave2, baixa) {
        if (chave2 != false) {
            document.form1.q02_inscr.value = chave1;
            document.form1.z01_nome.value = chave2;
            db_iframe.hide();
        } else {
            document.form1.z01_nome.value = chave1;
        }

        if (document.form1.q02_inscr.value == '') {
            document.form1.z01_nome.value = '';
        }

        if (typeof(baixa) == "undefined" && chave2 == true) {
            document.form1.z01_nome.value = chave1;
            document.form1.q02_inscr.value = '';
        }

        db_iframe.hide();
    }

    function js_pesquisaFiscalização(mostra)
    {
        if (mostra) {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipofiscaliza','func_tipofiscaliza.php?funcao_js=parent.js_preenchepesquisa|y27_codtipo|y27_descr','Pesquisa',true);
        } else {
            if (document.form1.y27_codtipo.value != "") {
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipofiscaliza','func_tipofiscaliza.php?funcao_js=parent.js_preenchepesquisa1&pesquisa_chave='+document.form1.y27_codtipo.value,'Pesquisa',false);
            } else {
                document.form1.y27_codtipo.value = "";
            }
        }
    }

    function js_preenchepesquisa(codigo, descricao)
    {
        document.form1.y27_codtipo.value = codigo;
        document.form1.y27_descr.value = descricao;
        db_iframe_tipofiscaliza.hide();
    }

    function js_preenchepesquisa1(descricao, erro)
    {
        document.form1.y27_descr.value = descricao;
        if (erro == true) {
            document.form1.y27_codtipo.focus();
            document.form1.y27_codtipo.value = '';
        }
    }

    function js_limpaCampos(campo)
    {
        const obj = document.form1;

        if (campo === "C") {
            obj.j01_matric.value = "";
            obj.q02_inscr.value = "";
        } else {
            if (campo === "M") {
                obj.z01_numcgm.value = "";
                obj.q02_inscr.value = "";
            } else {
                if (campo === "I") {
                    obj.z01_numcgm.value = "";
                    obj.j01_matric.value = "";
                }
            }
        }
    }

    js_carregaTaxas();

    function js_carregaTaxas()
    {
        var oParam = new Object();
		oParam.executa = "listar";
        oParam.isDepartamento = true;

		new AjaxRequest("arr4_taxaslancadas.RPC.php", oParam, js_getListarTaxas).execute();
    }

    function js_getListarTaxas(oRetorno)
    {
        if (oRetorno.erro) {
            alert(oRetorno.mensagem);
            document.getElementById("processar").disable();
			return;
        }

        const tdTaxas = document.getElementById("tdTaxas");
        const select = document.createElement("select");
        select.setAttribute("onchange", "js_buscarTaxa(this.value);");
        select.setAttribute("labelValidacao", "Taxas");
        select.setAttribute("isMandatory", "true");
        select.setAttribute("name", "taxa");
        select.setAttribute("style", "width: 228;");

        const option0 = document.createElement("option");
        option0.setAttribute("value", "");
        const text = document.createTextNode("Selecione");
        option0.appendChild(text);

        select.appendChild(option0);

        oRetorno.oTaxas.forEach(function (oTaxa){
            const option = document.createElement("option");
            option.setAttribute("value", oTaxa.ar44_sequencial);
            const text = document.createTextNode(oTaxa.ar44_sequencial + " - " + oTaxa.ar44_descricao);
            option.appendChild(text);

            select.appendChild(option);
        });

        tdTaxas.appendChild(select);
    }

    function js_buscarTaxa(sequencial)
    {
        const quantidade = document.getElementById("quantidade");
        quantidade.value = 1;

        const valor = document.getElementById("valor");
        valor.value = "";

        document.getElementById("valorFinal").value = "";

        if (sequencial == "") {
            montarCamposDinamicos([]);
            return false;
        }

        var oParam = new Object();
		oParam.executa = "buscar";
		oParam.ar44_sequencial = sequencial;

		new AjaxRequest("arr4_taxaslancadas.RPC.php", oParam, js_getBuscarTaxas).execute();
    }

    function js_getBuscarTaxas(oRetorno)
    {
        if (oRetorno.erro) {
            alert(oRetorno.mensagem);
			return;
        }

        const obj = document.form1;

        if (oRetorno.oTaxa.ar44_tipo == 1) {
            obj.valor.enable();
        } else {
            obj.valor.disable();
            obj.valor.value = oRetorno.oTaxa.i02_valor.toLocaleString('pt-BR', { maximumFractionDigits: 2});

            js_calculaValor();
        }

        const trFiscalizacao = document.getElementById("trFiscalizacao");
        const y27_codtipo = document.getElementById("y27_codtipo");
        const y27_descr = document.getElementById("y27_descr");

        if (oRetorno.oTaxa.ar44_recursoadm == "t") {
            trFiscalizacao.show();
            y27_codtipo.setAttribute("isMandatory", "true");
            y27_descr.setAttribute("isMandatory", "true");
        } else {
            trFiscalizacao.hide();
            y27_codtipo.removeAttribute("isMandatory");
            y27_descr.removeAttribute("isMandatory");
        }

        const dataCorrente = new Date();
        const dataVencimento = dataCorrente.setDate(dataCorrente.getDate() + parseInt(oRetorno.oTaxa.ar44_diasvencimento));

        obj.dataVencimento.value = new Date(dataVencimento).toLocaleDateString('pt-BR', {timeZone: 'UTC'});

        obj.geraDebito.value = oRetorno.oTaxa.geraDebito;

        montarCamposDinamicos(oRetorno.oTaxa.camposDinamicos);
    }

    function js_calculaValor()
    {
        const quantidade = document.getElementById("quantidade").value;
        const valor = document.getElementById("valor").value.replaceAll(".", "").replace(",", ".");

        const valorFinal = parseFloat(quantidade * valor);

        document.getElementById("valorFinal").value = valorFinal.toLocaleString('pt-BR', { maximumFractionDigits: 2});
    }

    function js_verificaCampo()
    {
        obj = document.form1;

        if (document.getElementById("z01_numcgm") == "" && document.getElementById("q02_inscr") == "" && document.getElementById("j01_matric") == "") {
            alert("Preencha o CGM, a Matricula ou a Inscrição.");
            return true;
        }

        const valor = obj.valorFinal.value.replace(",", ".").split(".");

        if ((valor.length == 2 && valor[0] == 0 && valor[1] == 0) || (valor.length == 1 && valor[0] == 0)) {
            alert("Valor deve ser maior que zero.");
            return true;
        }

        return false;
    }

    function js_ajustaCamposDinamicos ()
    {
        const oCampos = document.querySelectorAll('[campoDinamico="true"]');
        const aCampos = [];

        oCampos.forEach(function(oCampo){
            const oValores = new Object();
            oValores.codcam = oCampo.getAttribute("codcam");
            oValores.valor = oCampo.value;
            oValores.label = oCampo.getAttribute("labelvalidacao");
            oValores.tipoCampo = oCampo.getAttribute("tipocampo");
            aCampos.push(oValores);
        });

        return aCampos;
    }

    function js_geraRecibo()
    {
        if (js_verificaCampo()) {
            return false;
        }

        if (js_verificaCampoObrigatorio()) {
            return false;
        }

        const aCamposDinamicos = js_ajustaCamposDinamicos();

        const obj = document.form1;

        if (obj.geraDebito.value == "true") {
            if (!confirm("Deseja gerar débito?")) {
                alert("Taxa não incluída.");
                obj.reset();
                return false;
            }
        }

        var sUrl = 'arr4_taxaslancadas004.php?';
            sUrl += 'z01_numcgm='+obj.z01_numcgm.value+'&';
            sUrl += 'j01_matric='+obj.j01_matric.value+'&';
            sUrl += 'q02_inscr='+obj.q02_inscr.value+'&';
            sUrl += 'taxa='+obj.taxa.value+'&';
            sUrl += 'quantidade='+obj.quantidade.value+'&';
            sUrl += 'valor='+obj.valor.value.replaceAll(".", "").replace(",", ".")+'&';
            sUrl += 'valorFinal='+obj.valorFinal.value.replaceAll(".", "").replace(",", ".")+'&';
            sUrl += 'dataVencimento='+obj.dataVencimento.value+'&';
            sUrl += 'historico='+escape(obj.historico.value)+"&";
            sUrl += 'y27_codtipo='+obj.y27_codtipo.value+"&";
            sUrl += 'campoDinamicos='+JSON.stringify(aCamposDinamicos);

        jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0');
        jan.moveTo(0,0);

        obj.reset();
    }

    function montarCamposDinamicos(aCamposDinamicos)
    {
        const tdCamposDinamicos = document.getElementById("tdCamposDinamicos");
        tdCamposDinamicos.innerHTML = "";

        if (aCamposDinamicos.length > 0) {
            const fieldset = document.createElement("fieldset");

            const legend = document.createElement("legend");
            const strong = document.createElement("strong");
            strong.innerHTML = "Campos Adicionais";
            legend.appendChild(strong);
            fieldset.appendChild(legend);

            const table = document.createElement("table");

            aCamposDinamicos.forEach(function(oCampo){
                if (oCampo.ar47_tipocampo != 2) {
                    const tr = document.createElement("tr");
                    const tdLabel = document.createElement("td");
                    tdLabel.innerHTML = "<strong>"+oCampo.rotulo+":</strong>";
                    tr.appendChild(tdLabel);


                    const tdCampo = getCriaCampo(oCampo);

                    tr.appendChild(tdCampo);

                    table.appendChild(tr);
                } else {
                    const campo = getCriaCampo(oCampo);
                    table.appendChild(campo);
                }
            });

            fieldset.appendChild(table);


            tdCamposDinamicos.appendChild(fieldset);
        }
    }

    function getCriaCampo(oCampo)
    {
        const tdCampo = document.createElement("td");

        var campo = document.createElement("input");
        campo.setAttribute("type", "text");

        if (oCampo.ar47_tipocampo == 1) {
            //Texto

            campo = getAtributosPadraoCampo(oCampo, campo);

            campo.setAttribute("oninput", "js_ValidaCampos(this, 2, '"+oCampo.rotulo+"', '"+oCampo.nulo+"', '"+oCampo.maiusculo+"', event)");

            if (oCampo.maiusculo == "t")
            {
                campo.setAttribute("style", "text-transform:uppercase;");
            }

            tdCampo.appendChild(campo);
        }
        else if (oCampo.ar47_tipocampo == 2)
        {
            //Hidden

            campo = getAtributosPadraoCampo(oCampo, campo);

            campo.setAttribute("type", "hidden");
            campo.setAttribute("oninput", "js_ValidaCampos(this, 0, "+oCampo.nulo+", "+oCampo.maiusculo+", event)");

            tdCampo.appendChild(campo);
        }
        else if (oCampo.ar47_tipocampo == 3)
        {
            //Data

            campo = getAtributosPadraoCampo(oCampo, campo);

            campo.setAttribute("onblur", "js_validaDbData(this);");
            campo.setAttribute("onkeyup", "return js_mascaraData(this, event);");
            campo.setAttribute("onfocus", "return js_validaEntrada(this);");
            campo.setAttribute("onpaste", "return false");
            campo.setAttribute("ondrop", "return false");
            campo.setAttribute("style", "width: 80px; margin-right: 5px;");

            if (oCampo.ar47_valordefault != "") {
                const now = oCampo.ar47_valordefault.indexOf("now");

                if (now != -1) {
                    const soma = (oCampo.ar47_valordefault.indexOf("#") != -1);
                    var quantidade = "";
                    var quantidadeFinal = "";

                    if (soma) {
                        quantidade = oCampo.ar47_valordefault.split("#")[1];
                    } else {
                        var subtracao = (oCampo.ar47_valordefault.indexOf("|") != -1);

                        if (subtracao) {
                            quantidade = oCampo.ar47_valordefault.split("|")[1];
                        }
                    }

                    if (quantidade != "" && quantidade.match(/^[0-9]+$/)) {
                        quantidadeFinal = quantidade;
                    }

                    var dataCorrente = new Date();

                    if (quantidadeFinal != "") {
                        if (soma) {
                            dataCorrente = dataCorrente.setDate(dataCorrente.getDate() + parseInt(quantidadeFinal));
                        } else {
                            if (subtracao != undefined && subtracao) {
                                dataCorrente = dataCorrente.setDate(dataCorrente.getDate() - parseInt(quantidadeFinal));
                            }
                        }
                    }

                    campo.setAttribute("value", new Date(dataCorrente).toLocaleDateString('pt-BR', {timeZone: 'UTC'}));

                    if (oCampo.ar47_valordefault.length == 3) {

                    }
                }
            }

            tdCampo.appendChild(campo);

            const inputDia = document.createElement("input");
            inputDia.setAttribute("id", oCampo.nomecam+"_dia");
            inputDia.setAttribute("name", oCampo.nomecam+"_dia");
            inputDia.setAttribute("type", "hidden");
            inputDia.setAttribute("size", "2");
            inputDia.setAttribute("maxlength", "2");
            tdCampo.appendChild(inputDia);

            const inputMes = document.createElement("input");
            inputMes.setAttribute("id", oCampo.nomecam+"_mes");
            inputMes.setAttribute("name", oCampo.nomecam+"_mes");
            inputMes.setAttribute("type", "hidden");
            inputMes.setAttribute("size", "2");
            inputMes.setAttribute("maxlength", "2");
            tdCampo.appendChild(inputMes);

            const inputAno = document.createElement("input");
            inputAno.setAttribute("id", oCampo.nomecam+"_ano");
            inputAno.setAttribute("name", oCampo.nomecam+"_ano");
            inputAno.setAttribute("type", "hidden");
            inputAno.setAttribute("size", "4");
            inputAno.setAttribute("maxlength", "4");
            tdCampo.appendChild(inputAno);

            const btnCalendario = document.createElement("input");
            btnCalendario.setAttribute("value", "D");
            btnCalendario.setAttribute("id", "dtjs_"+oCampo.nomecam);
            btnCalendario.setAttribute("name", "dtjs_"+oCampo.nomecam);
            btnCalendario.setAttribute("type", "button");
            btnCalendario.setAttribute("onclick", "pegaPosMouse(event); show_calendar('"+oCampo.nomecam+"', 'none'); createFunction('"+oCampo.nomecam+"');");

            tdCampo.appendChild(btnCalendario);
        }
        else if (oCampo.ar47_tipocampo == 4)
        {
            //Verdadeiro / Falso

            campo = document.createElement("select");

            campo = getAtributosPadraoCampo(oCampo, campo);

            const optionFalse = document.createElement("option");
            optionFalse.setAttribute("value", "f");
            if (oCampo.ar47_valordefault == "f" || oCampo.ar47_valordefault == "0") {
                optionFalse.setAttribute("selected", "selected");
            }
            optionFalse.innerHTML = "Não";
            campo.appendChild(optionFalse);

            const optionTrue = document.createElement("option");
            optionTrue.setAttribute("value", "t");
            if (oCampo.ar47_valordefault == "t" || oCampo.ar47_valordefault == "1") {
                optionTrue.setAttribute("selected", "selected");
            }
            optionTrue.innerHTML = "Sim";
            campo.appendChild(optionTrue);

            tdCampo.appendChild(campo);
        }
        else if (oCampo.ar47_tipocampo == 5)
        {
            //Número sem casas decimais

            campo = getAtributosPadraoCampo(oCampo, campo);

            campo.setAttribute("oninput", "js_ValidaCampos(this, 1, '"+oCampo.rotulo+"', '"+oCampo.nulo+"', '"+oCampo.maiusculo+"', event)");

            tdCampo.appendChild(campo);
        }
        else if (oCampo.ar47_tipocampo == 6)
        {
            //Número com casas decimais

            campo = getAtributosPadraoCampo(oCampo, campo);

            campo.setAttribute("oninput", "return js_validaNumeroCasasDecimais(this, '"+oCampo.rotulo+"')");

            tdCampo.appendChild(campo);
        }
        else if (oCampo.ar47_tipocampo == 7)
        {
            //Combo
            campo = document.createElement("select");
            campo = getAtributosPadraoCampo(oCampo, campo);

            const opcoes = oCampo.ar47_valordefault.split("|");

            opcoes.forEach(function (opcao){
                const aOpcao = opcao.split("#");

                const option = document.createElement("option");
                option.setAttribute("value", aOpcao[1]);
                option.innerHTML = aOpcao[0];
                campo.appendChild(option);

                tdCampo.appendChild(campo);
            });
        }

        return tdCampo;
    }

    function getAtributosPadraoCampo(oCampo, campo)
    {
        var tamanho = oCampo.tamanho;

        if (oCampo.ar47_tipocampo == 3) {
            tamanho = 10;
        }

        campo.setAttribute("name", oCampo.nomecam+"_2");
        campo.setAttribute("id", oCampo.nomecam+"_2");
        campo.setAttribute("title", oCampo.descricao+"\n\nCampo: "+oCampo.nomecam);
        campo.setAttribute("maxlength", tamanho);
        campo.setAttribute("value", oCampo.ar47_valordefault);
        campo.setAttribute("labelvalidacao", oCampo.rotulo);
        campo.setAttribute("campoDinamico", "true");
        campo.setAttribute("tipoCampo", oCampo.ar47_tipocampo);
        campo.setAttribute("codcam", oCampo.ar47_codcam);

        if (oCampo.ar47_obrigatorio == "t") {
            campo.setAttribute("isMandatory", "true");
        }

        return campo;
    }

    function createFunction(campo)
    {
        const script = document.createElement('script');

        script.innerText = "var js_comparaDatas"+campo+" = function (dia, mes, ano) { const campo = document.getElementById('"+campo+"'); campo.value = dia+'/'+mes+'/'+ano;}";

        document.body.appendChild(script);
    }

    function js_validaNumeroCasasDecimais (oCampo, label)
    {
        if (!(oCampo.value.match(/^[\d,.?!]+$/)))
        {
            oCampo.value = oCampo.value.substring(0,(oCampo.value.length - 1));
            alert("Campo "+label+" deve ser preenchido somente com numeros, ponto ou virgula");
            return false;
        }
    }
</script>
