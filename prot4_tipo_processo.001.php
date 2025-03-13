<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php
    db_app::load("
         estilos.css,
         grid.style.css,
         scripts.js
     ");
    ?>
</head>
<body>
<div style="width:600px;margin: auto;margin-top: 30px;">
    <fieldset>
        <legend>Alteração tipo de processo</legend>
        <form id="formulario" onsubmit="return false">
            <table>
                <tr>
                    <td style="text-align: right"><?php db_ancora('Processo', 'openPesquisa()') ?>:</td>
                    <td><input name="codigoProcesso"></td>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Tipo Processo :</b></td>
                    <td>
                        <select name="tipo_processo" id="tipo_processo">
                            <option>--</option>
                        </select>
                    </td>
                </tr>


            </table>

            <div id="dados-processo"></div>
            <div style="width: 100%;text-align: right">
                <input type="submit" value="Alterar" onclick="atualizar()"/>
            </div>
    </fieldset>
</div>
<script>
    const URL_RPC = 'prot4_tipo_processo.RPC.php';
    const formulario = document.querySelector("#formulario");
    const dadosDoProcesso = document.querySelector("#dados-processo");

    function openPesquisa() {
        let url = 'func_protprocesso_protocolo.php?todas_instituicoes=1&grupo=1,2';
        url += '&funcao_js=parent.callbackPesquisa|dl_codigo_do_processo|p58_numero|dl_nome_ou_razão_social';
        js_OpenJanelaIframe('', 'db_iframe_consulta_processo', url, 'Pesquisa de Processos', true);
    }

    function callbackPesquisa(codigoProcesso, numero, nome) {
        formulario.codigoProcesso.value = codigoProcesso;
        findProcesso(codigoProcesso);
        db_iframe_consulta_processo.hide();
    }

    async function findProcesso(codigoProcesso) {

        js_divCarregando('Buscando Dados do processo...', 'loading_message');
        try {
            let form = {};
            form.action = 'find';
            form.codigoProcesso = codigoProcesso;
            const response = await fetch(URL_RPC, {
                method: "POST",
                body: JSON.stringify(form),
            });

            result = await response.json();
            if (!result.success) {
                alert(result.message);
                return;
            }

            formulario.tipo_processo.value = result.data.p58_tipoprocesso;

            let html = `
              <table>
               <tr>
                 <td style="text-align: right"><b>Requerente:</b></td>
                 <td>${result.data.p58_requer}</td>
               </tr>
               <tr>
                 <td style="text-align: right"><b>Ano:</b></td>
                 <td>${result.data.p58_ano}</td>
               </tr>
              </table>
            `;
            dadosDoProcesso.innerHTML = html;
            js_removeObj('loading_message');
        } catch (e) {
            js_removeObj('loading_message');
        }

    }

    async function getTiposProcesso() {

        js_divCarregando('Buscando tipos de processo...', 'loading_message');
        try {
            let form = {};
            form.action = 'tipos-processo';
            const response = await fetch(URL_RPC, {
                method: "POST",
                body: JSON.stringify(form),
            });

            result = await response.json();
            if (!result.success) {
                alert(result.message);
                return;
            }

            let html = [];
            result.data.forEach(function (el, index, array) {
                html.push(`<option value="${el.p109_sequencial}">${el.p109_nome}</option>`)
            });

            formulario.tipo_processo.innerHTML = html.join("");
            js_removeObj('loading_message');
        } catch (e) {
            js_removeObj('loading_message');
        }

    }

    async function atualizar() {
        js_divCarregando('Atualizando tipo de processo...', 'loading_message');
        try {
            let form = {};
            form.action = 'update';
            form.codigoProcesso = formulario.codigoProcesso.value;
            form.tipo_processo = formulario.tipo_processo.value;
            const response = await fetch(URL_RPC, {
                method: "POST",
                body: JSON.stringify(form),
            });

            result = await response.json();
            if (!result.success) {
                alert(result.message);
                return;
            }
            js_removeObj('loading_message');
            inicializar();
            alert(result.message);


        } catch (e) {
            js_removeObj('loading_message');
            alert("Ocorreu um erro ao atualizar");
        }

    }

    function inicializar() {
        formulario.codigoProcesso.value = "";
        formulario.tipo_processo.innerHTML = "";
        dadosDoProcesso.innerHTML = "";
        getTiposProcesso();
    }

    window.onload = function () {
        inicializar();
    }


</script>
</body>
</html>




