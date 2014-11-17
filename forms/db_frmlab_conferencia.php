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

//MODULO: Laboratório
$cllab_conferencia->rotulo->label();
$clrotulo = new rotulocampo ( );

//procedimentos
$clrotulo->label( "sd63_c_procedimento" );
$clrotulo->label( "sd63_c_nome" );
$clrotulo->label( "sd70_c_cid" );
$clrotulo->label( "sd70_c_nome" );
$clrotulo->label( "la52_diagnostico" );
$clrotulo->label( "la22_i_codigo" );
$clrotulo->label( "z01_v_nome" );

?>

<form name="form1" method="post" action="">
  <div class="container">
    <fieldset >
      <legend>Conferência de Exames</legend>
      <table class="form-container">
        <tr>
          <td nowrap="nowrap" title="<?=@$Tla22_i_codigo?>" class="field-size3">
            <?php db_ancora( '<b>Requisição</b>', "js_pesquisaRequisicao(true);", "" );?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('la22_i_codigo', 10, $Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisaRequisicao(false);'" );
              db_input('z01_v_nome', 75, $Iz01_v_nome, true, 'text', 3, '' )
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?=@$Tla47_i_resultado?>" class="field-size3">
            <?=@$Lla47_i_resultado?>
          </td>
          <td>
            <?php
              $aX = array('1'=>'SIM', '2'=>'NÃO');
              db_select('la47_i_resultado', $aX, true, $db_opcao);
            ?>
          </td>
        </tr>
      </table>

      <fieldset>
        <legend> Considerações </legend>
        <?php
          db_textarea('la47_t_observacao', 0, 100, @$Ila47_t_observacao,true,'text',$db_opcao,"")
        ?>
      </fieldset>

    </fieldset>
  </div>

  <div class="subcontainer">

    <fieldset  style="width: 1000px">

      <legend>Exames Realizados</legend>
      <div id="ctnGridExame" > </div>

    </fieldset>

    <input name="btnSalvar" type="button" id="btnSalvar" value="Confirmar Resultado" />
    <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='lab4_confresult001.php';" />
  </div>
</form>
<script>
const MSG_FRMLABCONFERENCIA = 'saude.laboratorio.db_frmlab_conferencia.'

var oLaboratorio = null;

var oGridExames          = new DBGrid("dbGridExames");
oGridExames.nameInstance = "oGridExames";
oGridExames.setCheckbox(3);
oGridExames.setCellWidth(["35%", "15%", "50%"]);
oGridExames.setCellAlign(["left", "center", "left"]);
oGridExames.setHeader(["Exame", "Procedimento", "CID", "codigo_exame", "codigo_procedimento"]);
oGridExames.setHeight(250);
oGridExames.aHeaders[4].lDisplayed = false;
oGridExames.aHeaders[5].lDisplayed = false;
oGridExames.show($("ctnGridExame"));


( function () {

  if ( !Laboratorio.departamentoIsLaboratorio() && !Laboratorio.usuarioIsTecnicoLaboratorio() ) {

    alert( _M(MSG_FRMLABCONFERENCIA+"departamento_usuario_invalido") );
    $('btnSalvar').setAttribute("disabled", "disabled");
  } else {
    oLaboratorio = Laboratorio.getLaboratorioByDepartamento();
  }
})();


/**
 * Pesquisa a requisição
 */
function js_pesquisaRequisicao( lMostra ) {

  var sUrl  = "func_lab_requisicao.php?iLaboratorio=" +oLaboratorio.iLaboratorio;

  if ( lMostra ) {

    sUrl += "&funcao_js=parent.js_mostraRequisicao|la22_i_codigo|z01_v_nome";
    js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisições', true);
  } else if ( !lMostra && $F('la22_i_codigo') != '') {

    sUrl += "&pesquisa_chave=" + $F('la22_i_codigo') + "&funcao_js=parent.js_mostraRequisicao";
    js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisições', false);
  } else {
    $('z01_v_nome').value = '';
  }
}
/**
 * Mostra o retorno da pesquisa pela requisição
 */
function js_mostraRequisicao() {

  /**
   * Quando valor de arguments[1] for um boolean, significa que o código foi digitado
   */
  if ( typeof arguments[1] == "boolean" ) {

    $('z01_v_nome').value = arguments[0];
    if ( arguments[1] ) {
      $('la22_i_codigo').value = '';
    }

  } else {

    $('la22_i_codigo').value = arguments[0];
    $('z01_v_nome').value    = arguments[1];
    db_iframe_lab_requisicao.hide();
  }

  if ( $F('la22_i_codigo') != '' ) {
    js_buscaExames();
  }
}

/**
 * Busca os exames da requisição selecionada
 */
function js_buscaExames() {

  var oParametro      = {};
  oParametro.exec     = 'getExamesRequisicao';
  oParametro.iCodigo  = $F('la22_i_codigo');

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = js_retornoBuscaExames;
  oRequest.asynchronous = false;

  js_divCarregando( _M(MSG_FRMLABCONFERENCIA+"aguarde_busca_exames"), "msgBoxA");
  new Ajax.Request( 'lab4_conferencia.RPC.php' , oRequest );

}

function js_retornoBuscaExames( oAjax ) {

  js_removeObj("msgBoxA");
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if ( parseInt(oRetorno.iStatus) == 2 ) {

    alert( _M(MSG_FRMLABCONFERENCIA + "erro_buscar_exames") );
    return;
  }
  if ( oRetorno.aExames.length == 0 ) {

    alert( _M(MSG_FRMLABCONFERENCIA + "requisicao_sem_exames") );
    return;
  }

  oGridExames.clearAll(true);
  oRetorno.aExames.each( function (oExame, iLinha) {

    var oLinkExame = new Element( 'a', {'href':'#', 'onclick': 'js_consultaResultados('+oExame.iExame+')'} ).update( oExame.sExame.urlDecode() );
    var oCboCID    = new Element( 'select', {'id' : 'cidLinha'+iLinha} );
    oExame.aCID.each( function( oCID ) {

      var oOpion = new Option( oCID.sCID + ' - ' + oCID.sNome.urlDecode(), oCID.iCodigo );
      oOpion.style.width = '100%';
      if ( oCID.lPrincipal ) {
        oOpion.setAttribute("selected", "selected");
      }
      oCboCID.add( oOpion );
    });

    var aLinha = [];
    aLinha.push(oLinkExame.outerHTML);
    aLinha.push(oExame.sProcedimentoEstrutural);

    if ( oExame.aCID.length > 0 ) {
      aLinha.push(oCboCID.outerHTML);
    } else {
      aLinha.push( '' );
    }
    aLinha.push(oExame.iExame);
    aLinha.push(oExame.iProcedimento);

    oGridExames.addRow( aLinha );
  });

  oGridExames.renderRows();

  /**
   * Colocamos o hint nos procedimentos na grid
   */
  oRetorno.aExames.each( function (oExame, iLinha) {

    if (oExame.sProcedimento != '') {

      var oParametros = {iWidth:'200', oPosition : {sVertical : 'B', sHorizontal : 'R'}};
      var sHint       = oExame.sProcedimento.urlDecode();

      oGridExames.setHint(iLinha, 2, sHint, oParametros);
    }
  });

};

/**
 * Salva a conferencia
 */
function js_salvarConferencia() {

  if ( !js_validaCampos() ) {
    return false;
  }

  var oParametro           = {};
  oParametro.exec          = 'salvarConferencia';
  oParametro.iCodigo       = $F('la22_i_codigo');
  oParametro.lConferido    = $F('la47_i_resultado') == 1;
  oParametro.sConsideracao = encodeURIComponent( tagString( $F('la47_t_observacao') ) );
  oParametro.aExames       = [];

  var aSelecionadoGrid = oGridExames.getSelection('array');

  aSelecionadoGrid.each( function ($aLinha) {

    var oExame                    = {};
    oExame.iCodigoRequisicaoExame = $aLinha[0];
    oExame.iCodigoCID             = $aLinha[3].trim();
    oExame.iProcedimento          = $aLinha[5].trim();
    oParametro.aExames.push( oExame );
  });

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function( oAjax ) {

    js_removeObj("msgBoxB");

    var oRetorno = eval( "(" + oAjax.responseText + ")" );
    alert( oRetorno.sMensagem.urlDecode() );
    if (parseInt(oRetorno.iStatus) == 1) {
      location.href = 'lab4_confresult001.php';
    }
  };
  js_divCarregando( _M(MSG_FRMLABCONFERENCIA+"aguarde_salvando_conferencia"), "msgBoxB");
  new Ajax.Request( 'lab4_conferencia.RPC.php' , oRequest );
}

$('btnSalvar').observe( 'click', function() {
  js_salvarConferencia();
});

function js_validaCampos() {

  if ( $F('la22_i_codigo') == 1 && $F('la47_t_observacao') ) {

    alert(_M(MSG_FRMLABCONFERENCIA+"escreva_uma_observacao"));
    return false
  }

  if ( oGridExames.getSelection('array').length == 0) {

    alert(_M(MSG_FRMLABCONFERENCIA+"selecione_exame"));
    return false
  }
  return true;
}

function js_consultaResultados(iItemExame) {

  var oResultadoExame = new LancamentoExameLaboratorio('resultadoExame');
  oResultadoExame.abrirComoJanela(iItemExame);
  oResultadoExame.setReadOnly( true );

}

</script>