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
require_once(modification("libs/db_utils.php"));

$clrotulo    = new rotulocampo();
$clrotulo->label('ar28_sequencial');
$clrotulo->label('ar28_usuario');
$clrotulo->label('ar28_clientid');
$clrotulo->label('ar28_clientsecret');
$clrotulo->label('ar28_codban');
$clrotulo->label('ar28_chavej');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <form action="" method="post" class="container" id="form">
    <input type="hidden" id="ar28_sequencial">
    <fieldset>
        <legend>Webservice</legend>
        <table class="form-container">
          <tr>
              <td><label for="ar28_codban"><?= $Lar28_codban ?></label></td>
              <td>
                <select id="ar28_codban" style="width: 233px;" onchange="js_criaCampos(this.value)" title="<?= $Tar28_codban ?>">
                    <option value="">SELECIONE</option>
                    <option value="104">CAIXA</option>
                    <option value="001">BANCO DO BRASIL</option>
                </select>
              </td>
              <tr id="trCampos" style="display: none;">
                <td colspan="2">
                    <fieldset>
                    <legend>Campos</legend>
                    <table class="form-container" id="tableCampos">
                    </table>
                </fieldset>
                </td>
              </tr>
          </tr>
        </table>
    </fieldset>
    <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar();" style="display: none;"/>
  </form>
</center>
<script>
    function js_criaCampos(codban)
    {
        const tableCampos = document.getElementById("tableCampos");
        tableCampos.innerHTML = "";

        const campos = js_carregaCampos(codban);

        var input = null;

        for (campo in campos) {
            if (campos[campo].label != undefined) {
                const tr = document.createElement("tr");

                const tdLabel = document.createElement("td");
                tdLabel.setAttribute("style", "font-weight: bold;");
                
                const label = document.createTextNode(campos[campo].label+":");
                tdLabel.appendChild(label);

                tr.appendChild(tdLabel);

                const tdInput = document.createElement("td");

                input = document.createElement(String(campos[campo].type));

                input.setAttribute("name", campos[campo].name);
                input.setAttribute("id", campos[campo].name);
                input.setAttribute("title", "Campo: "+campos[campo].name);
                input.setAttribute("style", "width: 100%");

                tdInput.appendChild(input);
                tr.appendChild(tdInput);

                tableCampos.appendChild(tr);
            } else {
                break;
            }
        }

        js_buscar();
    }

    function js_carregaCampos(codban)
    {
        var dados = null;

        const campos = {
            "104" : [
                {"name" : "ar28_usuario", "label" : String("<?= $LSar28_usuario ?>"), "type" : "input"}
            ],
            "001" : [
                {"name" : "ar28_clientid", "label" : String("<?= $LSar28_clientid ?>"), "type" : "textarea"},
                {"name" : "ar28_clientsecret", "label" : String("<?= $LSar28_clientsecret ?>"), "type" : "textarea"},
                {"name" : "ar28_chavej", "label" : String("<?= $LSar28_chavej ?>"), "type" : "input"}
            ]
        };

        for(codigo in campos) {
            if (parseInt(codigo) == parseInt(codban)) {
                dados = campos[codigo];
                break;
            }
        }

        return dados;
    }

    function js_buscar()
    {
		obj = document.getElementById("form");

		var oParam = new Object();
		oParam.executa = "buscar";
		oParam.ar28_codban = obj.ar28_codban.value;

		new AjaxRequest("arr4_parametroscobrancaregistrada001.RPC.php", oParam, js_getBuscar).execute();
    }

    function js_getBuscar(oRetorno)
    {
        if (oRetorno.mensagem != "") {
            alert(oRetorno.mensagem);
        }

		if (oRetorno.erro) {
			return;
		}

        obj = document.getElementById("form");

		obj.ar28_sequencial !== undefined ? obj.ar28_sequencial.value = oRetorno.ar28_sequencial : null;
		obj.ar28_usuario !== undefined ? obj.ar28_usuario.value = oRetorno.ar28_usuario : null;
		obj.ar28_clientid !== undefined ? obj.ar28_clientid.value = oRetorno.ar28_clientid : null;
		obj.ar28_clientsecret !== undefined ? obj.ar28_clientsecret.value = oRetorno.ar28_clientsecret : null;
		obj.ar28_chavej !== undefined ? obj.ar28_chavej.value = oRetorno.ar28_chavej : null;

        if (obj.ar28_codban.value === "") {
            $("trCampos").hide();
            $("salvar").hide();
        } else {
            $("trCampos").show();
            $("salvar").show();
        }
    }

    function js_salvar() {
		obj = document.getElementById("form");

        if (!js_validaCampos(obj.ar28_codban.value)) {
            return;
        }

		var oParam = new Object();
		oParam.executa = "salvar";
		oParam.ar28_sequencial = obj.ar28_sequencial.value;
		oParam.ar28_usuario = (obj.ar28_usuario !== undefined ? obj.ar28_usuario.value : null);
		oParam.ar28_clientid = (obj.ar28_clientid !== undefined ? obj.ar28_clientid.value.replace('\n', '') : null);
		oParam.ar28_clientsecret = (obj.ar28_clientsecret !== undefined ? obj.ar28_clientsecret.value.replace('\n', '') : null);
		oParam.ar28_chavej = (obj.ar28_chavej !== undefined ? obj.ar28_chavej.value.replace('\n', '') : null);
		oParam.ar28_codban = obj.ar28_codban.value;

		new AjaxRequest("arr4_parametroscobrancaregistrada001.RPC.php", oParam, js_getSalvar).execute();
	}

    function js_getSalvar(oRetorno) {
		alert(oRetorno.mensagem);

		if (oRetorno.erro) {
			return;
		}
	}

    function js_validaCampos(codban)
    {
        obj = document.getElementById("form");

        var erro = false;

        if (codban == "001") {
            if (obj.ar28_clientid.value !== "" && obj.ar28_clientsecret.value === "") {			
                erro = true;
            } else {
                if (obj.ar28_clientid.value === "" && obj.ar28_clientsecret.value !== "") {
                    erro = true;
                } else {
                    if ((obj.ar28_clientid !== undefined && obj.ar28_clientsecret !== undefined) && (obj.ar28_clientid.value === "" && obj.ar28_clientsecret.value === "")) {
                        erro = true;
                    }
                }
            }

            if (erro) {
                alert("Campo ClientID e Cliente Secret devem ser preenchidos juntos!");
                return false;
            }

            if (obj.ar28_chavej.value === "") {
                alert("Campo Chave J deve ser preenchido!");
                return false;
            }
        } else if (codban == "104") {
            if (obj.ar28_usuario !== undefined && obj.ar28_usuario.value === "") {			
                alert("Campo Usuário deve ser preenchido!");
                return false;
            }
        }

        return true;
    }
</script>
</body>
</html>
