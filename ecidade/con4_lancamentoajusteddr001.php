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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<style>

  .dimensoes {
    width: 100%;
    height: 100%;
  }

  .destaque {
      background-color: #ffffdd !important;
  }

</style>
<body>

<div class="container">
    <fieldset>
        <legend>Lançamento(s) por fonte de Recursos</legend>

        <table class="form-container">
          <tr class="text-left">
            <td><label class="bold" for="dataInicial">Data Inicial:</label></td>
            <td>
               <?php db_inputdata("txtDataInicial", null, null, null, true, null, 1) ?>
            </td>
          </tr>
          <tr class="text-left">
             <td><label class="bold" for="dataFinal">Data Final:</label></td>
             <td>
               <?php db_inputdata("txtDataFinal", null, null, null, true, null, 1) ?>
            </td>
          </tr>
          <tr>
            <td id="ctnInstituicao" colspan="2" style="font-weight: normal">
                <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
            </td>
          </tr>
        </table>
    </fieldset>
    <br />
    <button id="btnCarregar" type="button">
        <i class="fas fa-search"></i>
        Carregar Dados
    </button>
</div>
<div class="container">
    <div id="div_grid_conferenciarecurso" style="width: 800px;margin-bottom:10px;"></div>
    <button id="btnProcessar" type="button" disabled>
        <i class="fas fa-save"></i>
        Processar
    </button>
</div>
</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
<script>

(function(oWindow){

oWindow.oGridRecursos   = new DBGrid("recursos");
oWindow.oGridRecursos.nameInstance = "window.oGridRecursos";
oWindow.oGridRecursos.setCheckbox(3);
oWindow.oGridRecursos.setHeader(["Recurso", "Descrição", "Valor", ""]);
oWindow.oGridRecursos.setCellWidth(["10%","72%","18%", "0%"]);
oWindow.oGridRecursos.setCellAlign(["left", "left", "left", "left"]);
oWindow.oGridRecursos.setHeight(250);
oWindow.oGridRecursos.show( $('div_grid_conferenciarecurso') );

})(window);

const sApiUrl = "<?= ECIDADE_REQUEST_PATH ?>v4/api/financeiro/contabilidade/";
const routs = {
    processar : sApiUrl + "obter-dados-conferencia-por-recurso"
};
const rpcFile = 'con4_lancamentoajusteddr.RPC.php';

const dataInicial = $("txtDataInicial");
const dataFinal =   $("txtDataFinal");

btnCarregar.addEventListener('click', () => {

    const formData = new FormData();

    formData.append('dataInicial', js_formatar(dataInicial.value, 'd'));
    formData.append('dataFinal', js_formatar(dataFinal.value, 'd'));

    if (dataInicial.value == "" || dataFinal.value == "") {

        alert("Selecione um Intervalo de Datas.");
        return false;
    }

    formData.append('instituicoes[]', <?=db_getsession("DB_instit") ?>);

    PHPSession.appendFormData(formData);

    HttpClient.post(`${routs.processar}`, {body: formData}).then(response => {

        if (response.data.error) {
            alert(response.data.message);
            return;
        }

        window.oGridRecursos.clearAll(true);
        const dados = response.data.registros;
        for (var i = 0; i < dados.length; i++) {

            if(
                js_formatar(dados[i].diferenca, 'f').trim() == '0' ||
                js_formatar(dados[i].diferenca, 'f').trim() == '0.00' ||
                js_formatar(dados[i].diferenca, 'f').trim() == '0,00'
            )
            {
                continue; // Exibindo apenas valores com diferença
            }

            var sCampoTipoSaldo = "<select class='dimensoes' id='comboBox_"+dados[i].recurso+"'>";
            sCampoTipoSaldo    += "  <option value='D'>Débito</option>";
            sCampoTipoSaldo    += "  <option value='C'>Crédito</option>";
            sCampoTipoSaldo    += "</select>";

            var aLinha          = new Array();
            aLinha[0]           = dados[i].recurso;
            aLinha[1]           = dados[i].o15_descr.urlDecode();
            aLinha[2]           = eval("qtditem"+i+" = new DBTextField('qtditem"+i+"','qtditem"+i+"','"+ js_formatar(dados[i].diferenca, 'f') +"')");
            aLinha[2].iMaxLength = 12;
            aLinha[2].addStyle("text-align","right");
            aLinha[2].addStyle("float","right");
            aLinha[2].addStyle("width","100%");
            aLinha[2].addStyle("height","100%");
            aLinha[2].addEvent("onBlur","this.value = js_formatar(this.value, 'f', 2);qtditem"+i+".sValue=this.value");
            aLinha[2].addEvent("onFocus",";this.value = js_strToFloat(this.value);");
            aLinha[2].addEvent("onInput",";this.value = this.value.replace(/[^0-9\.]/g, '');");
            aLinha[2].setReadOnly(true);
            var objDados = {};
            objDados.recurso = dados[i].recurso;
            objDados.diferenca = js_formatar(dados[i].diferenca, 'f');
            aLinha[3] = JSON.stringify(objDados);

            var classeRow = '';
            window.oGridRecursos.addRow(aLinha, null, null, null, classeRow);
        }

        window.oGridRecursos.renderRows();
        document.querySelector('#btnProcessar').disabled = false;

    });

});

btnProcessar.addEventListener('click', () => {

    var linhas = window.oGridRecursos.getSelection("array");
    var linhas_sel = linhas.length;

    if(linhas_sel == 0){
      alert('É preciso selecionar pelo menos uma opção antes de processar.');
      return false;
    }

    if(!confirm('Deseja realmente fazer o(s) lançamento(s) com o(s) valor(es) especificado(s) ?'))
    {
        return false;
    }

    var linhasRecursos  = [];
    for(var i = 0;i<linhas_sel;i++){
        linhasRecursos.push(js_formatar(linhas[i][0].replace(/\s/g, ''), 'f', 2));
    }

    const parametros = new FormData();
    parametros.append('exec', 'salvarLancamentos');

    for(var i = 0; i < linhas_sel; i++)
    {
        parametros.append('linhasRecursos[]', linhasRecursos[i]);
    }

    HttpClient.post(rpcFile, {body: parametros}).then((response) => {

        if (response.erro) {
            if(response.mensagem)
            {
                alert(response.mensagem.urlDecode());
            }
            return false;
        }

        alert(response.mensagem);
        window.oGridRecursos.clearAll(true);
        document.querySelector('#btnProcessar').disabled = true;
        btnCarregar.click();

    });

    return false;
});

document.querySelector('#btnProcessar').disabled = true;

</script>
