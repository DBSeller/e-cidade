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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

$oGet                       = db_utils::postMemory($_GET);
$oRotuloEmpEmpenho          = new rotulo("empempenho");
$oRotuloEmpenhoOutrosDados  = new rotulo("empempenhooutrosdados");
$oRotuloCgm  = new rotulo("cgm");

$oRotuloEmpEmpenho->label();
$oRotuloEmpenhoOutrosDados->label();
$oRotuloCgm->label();
$oGet->db_opcao = 1;

db_app::load('scripts.js,estilos.css,prototype.js, dbmessageBoard.widget.js, windowAux.widget.js');
db_app::load('dbtextField.widget.js, dbcomboBox.widget.js, DBViewGeracaoAutorizacao.classe.js, grid.style.css');
db_app::load('datagrid.widget.js, strings.js, arrays.js, DBHint.widget.js, ');
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
      <script type="text/javascript" src="scripts/prototype.js"></script>
      <script src="scripts/classes/http/http.js" rel="script" type="text/javascript"></script>


      <style>

    </style>
  </head>

  <body bgcolor="#CCCCCC">

    <form class="container" id="form1" name="form1">

      <fieldset style="width: 600px;">
        <legend>Manutenção de Agrupamento de Folha de Pagamento</legend>
        <table border='0' class="form-container">
          <!-- Inventário / inventario / t75-->
          <tr>
            <td width="30%" nowrap title="Numcgm">
              <?php
                db_ancora(@$Lz01_numcgm, "js_pesquisa_cgm(true);", 1);
                ?>
            </td>
            <td>
              <?php
                db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 4, "onchange='js_pesquisa_cgm(false);'");
                db_input("z01_nome2", 30, "", true, "text", 3);
                ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap title="<?=$Te60_emiss?>">
              <?php db_ancora(@$Le60_emiss, "", 3);?>
            </td>
            <td align="left" nowrap>
              <?php
                db_inputdata('e60_emiss1', @$e60_emiss_dia, @$e60_emiss_mes, @$e60_emiss_ano, true, 'text', 1, "");
                db_inputdata('e60_emiss2', @$e60_emiss_dia, @$e60_emiss_mes, @$e60_emiss_ano, true, 'text', 1, "");
                ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap title="<?=$Te60_emiss?>">
              <?php db_ancora("Intervalo de Empenhos:", "", 3);?>
            </td>
            <td align="left" nowrap>
              <?php
                db_input("intervaloEmpDe", 10, "", true, "text", 1);
                ?>
                até
                <?php
                db_input("intervaloEmpAte", 10, "", true, "text", 1);
                ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input name="exibir" id = "pesquisa" type="button" onclick='js_exibirEmpenhos();'  value="Pesquisa">
      <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">
    </form>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>


<script>

var sUrlRpc                 = "emp1_empempenhoagrupfolha.RPC.php";

function js_abre(){

   obj = document.form1;
   if (    (obj.e60_emiss1_dia.value !='')
        && (obj.e60_emiss2_dia.value !='')
        && (obj.e60_emiss1_mes.value !='')
        && (obj.e60_emiss2_mes.value !='')
        && (obj.e60_emiss1_ano.value !='')
        && (obj.e60_emiss1_ano.value !='')) {
    dt1 = obj.e60_emiss1_ano.value+'-'+obj.e60_emiss1_mes.value+'-'+obj.e60_emiss1_dia.value ;
    dt2 = obj.e60_emiss2_ano.value+'-'+obj.e60_emiss2_mes.value+'-'+obj.e60_emiss2_dia.value ;
   } else {
      dt1='';
      dt2='';
   }

  var intervaloEmpenhoDe = document.form1.intervaloEmpDe.value > 0 ? document.form1.intervaloEmpDe.value : 0;
  var intervaloEmpenhoAte = document.form1.intervaloEmpAte.value > 0 ? document.form1.intervaloEmpAte.value : 0;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empconsulta002','emp1_empconsulta002.php?z01_numcgm='+document.form1.z01_numcgm.value+'&dt1='+dt1+'&dt2='+dt2+'&intervaloEmpDe='+intervaloEmpenhoDe+'&intervaloEmpAte='+intervaloEmpenhoAte+'&funcao_js=parent.js_consulta002','Pesquisa',true);
}

function js_limpa(){
   location.href='emp1_empempenhoagrupfolha001.php';
}

function js_exibirEmpenhos() {


  if($F("e60_emiss1") != "" && $F("e60_emiss2") != ""){
    if(js_comparadata($F("e60_emiss1"), $F("e60_emiss2"), ">")){
      alert("Data final maior que data inicial")
      return false;
    }
  }
  if(($F("e60_emiss1") != "" && $F("e60_emiss2") == "") || ($F("e60_emiss1") == "" && $F("e60_emiss2") != "")){
      alert("As duas datas devem estar preenchidas")
      return false;
  }

  if($F("intervaloEmpDe") != "" && $F("intervaloEmpAte") != "") {
      if ($F("intervaloEmpDe") > $F("intervaloEmpAte")) {
          $("intervaloEmpAte").value = "";
          $("intervaloEmpDe").value = "";
          alert("Intervalo de empenho final menor que intervalo inicial")
          return false;
      }
  }
    if(($F("intervaloEmpDe") != "" && $F("intervaloEmpAte") == "") ||
        ($F("intervaloEmpDe") == "" && $F("intervaloEmpAte") != "")){
        alert("Os dois campos do intervalo de empenho devem ser preenchidos")
        return false;
    }

  const formData = new FormData();
  formData.append('numeroCgm', $F("z01_numcgm"));
  formData.append('dataInicial', $F("e60_emiss1"));
  formData.append('dataFinal', $F("e60_emiss2"));
  formData.append('empenhoInicial', $F("intervaloEmpDe"));
  formData.append('empenhoFinal', $F("intervaloEmpAte"));
  formData.append('acao','getEmpenhoFiltroManutencao');


  HttpClient.post(sUrlRpc,{body:formData}).then(response => {
      if(response.erro){
          alert(response.mensagem);
          return;
      }
      if(response.empenhos){
          js_montaWindowGridItens(response.empenhos)
      }
  });
}

function js_montaWindowGridItens (empenhos) {

  if (empenhos.length == 0) {
    alert("Nenhum empenho encontrado" ,"msgbox");
    return false;
  }

  var iHeight   = document.body.clientHeight-100;
  var iWidth    = document.body.clientWidth-50;
  var iWidthContainer = (iWidth-30);
  oWindowAux    = new windowAux('oWindowAux', 'Empenhos', iWidth, iHeight);
  var sContent  = "<div style='width: "+iWidthContainer+"px;' id='cntGrid'></div>";
      sContent += "<p align='center'><input type='button' value='Fechar' onclick='js_fecharWindow();' /></p>";
  oWindowAux.setContent(sContent);
  var aHeader     = new Array();
      aHeader[0]  = "Código Empenho ";
      aHeader[1]  = "Seq. Empenho";
      aHeader[2]  = "Cod. Ordem Pagamento";
      aHeader[3]  = "Data Liquidação";
      aHeader[4]  = "Valor Liquidação";
      aHeader[5]  = "Código Agrupamento";

  var aCellWidth     = new Array();
      aCellWidth[0]  = "20";
      aCellWidth[1]  = "20";
      aCellWidth[2]  = "20";
      aCellWidth[3]  = "20";
      aCellWidth[4]  = "20";
      aCellWidth[5]  = "20";


  var aCellAlign     = new Array();
      aCellAlign[0]  = "center";
      aCellAlign[1]  = "center";
      aCellAlign[2]  = "center";
      aCellAlign[3]  = "center";
      aCellAlign[4]  = "center";
      aCellAlign[5]  = "center";


  oGridEmpenhos = new DBGrid('cntGrid');
  oGridEmpenhos.nameInstance = 'oGridBens';
  oGridEmpenhos.allowSelectColumns(true);
  oGridEmpenhos.setHeader(aHeader);
  oGridEmpenhos.setCellWidth(aCellWidth);
  oGridEmpenhos.setCellAlign(aCellAlign);
  oGridEmpenhos.setHeight(300);
  oGridEmpenhos.show($('cntGrid'));

  oWindowAux.show();
  oWindowAux.setShutDownFunction(function(){
    js_fecharWindow();
  });

  js_preencheGrid(empenhos);

}

function js_salvaDadosLinhaSelecionada(oRow) {

  let dados = {};
  dados.codigo_agrupamento =  oRow.aCells[5].getValue();
  const formData = new FormData();
  formData.append('codOrd',oRow.aCells[2].getValue());
  formData.append('dados',js_objectToJson(dados));
  formData.append('acao',"updateEmpenhoFiltroManutencao")          ;
  HttpClient.post(sUrlRpc,{body :formData}).then(response =>{
      if (response.erro) {
          alert(response.mensagem)
      }
  })
}


function js_preencheGrid(aEmpenho) {

  js_divCarregando("Carregando dados...", "oDivDados");

  oGridEmpenhos.clearAll(true);
  aItensGrid = {};

  aEmpenho.each(function (oItem, iIndice) {

     /* INPUT e172_dados  */
     var oe172_dados = window["oTxte172_dados" + oItem.e60_numemp] = new DBTextField("oTxte172_dados"+oItem.e50_codord, "oTxte172_dados"+oItem.e50_codord, oItem.e172_dados, 10);
     oe172_dados.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
     oe172_dados.addStyle('width', '100%');
     oe172_dados.addStyle('height', '100%');
     oe172_dados.addStyle('text-align', 'center');
     oe172_dados.addStyle('border', '1px solid black');
     oe172_dados.addEvent("onFocus", "js_liberaDigitacao(this);");
     oe172_dados.addEvent("onBlur", "js_bloqueiaDigitacao(this);js_atualizaValor(this, event);");
     oe172_dados.addEvent("onChange",";js_valorAtualizado(this,"+oItem.e60_numemp+","+iIndice+"); js_atualizaValor(this, event);");
     oe172_dados.addEvent("onKeyUp",';js_ValidaValor(this, event);');
     oe172_dados.setReadOnly(false);

    // criado array com os objetos
    aItensGrid[iIndice] = {
      e172_dados  : oe172_dados,
     };

    var aLinha     = new Array();
        aLinha[0]  = oItem.e60_codemp.urlDecode() + "/" + oItem.e60_anousu.urlDecode();
        aLinha[1]  = oItem.e60_numemp.urlDecode();
        aLinha[2]  = oItem.e50_codord.urlDecode();
        aLinha[3]  = oItem.e50_data.urlDecode();
        aLinha[4]  = js_formatar(oItem.c70_valor, 'f');
        aLinha[5]  = '';

    oGridEmpenhos.addRow(aLinha, false, true);

  });

  oGridEmpenhos.renderRows();

  for (var iIndice = 0; iIndice < oGridEmpenhos.aRows.length; iIndice++) {
    var oItem = aItensGrid[iIndice],
        oRow  = oGridEmpenhos.aRows[iIndice],
        tde172_dados = document.getElementById(oRow.aCells[5].sId);
    oItem.e172_dados.show(tde172_dados);
  }

  js_removeObj("oDivDados");
}

/**
 * Atualiza o campo CÓDIGO AGRUPAMENTO
 */
function js_valorAtualizado(oInputValorAtualizado, iCodigoBem, iCodigoLinha) {

  js_salvaDadosLinhaSelecionada(oGridEmpenhos.aRows[iCodigoLinha]);

}

function js_bloqueiaDigitacao(oObject) {

  oObject.readOnly         = true;
  oObject.style.border     ='1px solid black';
  oObject.style.fontWeight = "normal";
  oObject.value            = oObject.value;
}

function js_liberaDigitacao (object) {

  nValorObjeto            = js_strToFloat(object.value).valueOf();
  object.value            = nValorObjeto;
  object.style.border     = '1px solid black';
  object.readOnly         = false;

  object.select();

  nValorAntigo = object.value;
}


function js_atualizaValor(object, event) {


  var teclaPressionada = event.which;

  if (teclaPressionada == 27) {
    object.value = nValorAntigo;
  }
}

function js_ValidaValor(obj, event) {

if ( js_countOccurs(obj.value, '.') > 1 ) {

  obj.value = js_getInputValue(obj.name);
  obj.focus();
  return false;
}
}

function js_fecharWindow() {
  oWindowAux.destroy();
}

function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm_empenho.php?funcao_js=parent.js_mostracgm1|e60_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm_empenho.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = '';
     }
  }
}

function js_mostracgm(chave,erro){
  document.form1.z01_nome2.value = chave;
  if(erro==true){
    document.form1.z01_nome2.value = '';
    document.form1.z01_numcgm.focus();
  }
}

function js_mostracgm1(chave1,chave2){
   document.form1.z01_numcgm.value = chave1;
   document.form1.z01_nome2.value = chave2;
   db_iframe_cgm.hide();
}

</script>

