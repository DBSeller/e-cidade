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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
?>

<style type="text/css">
.tamanhoInputHora {

  width:   70px;
  margin:  0;
  padding: 0;
  text-align: right;
}
</style>
  <div class="container">

    <form>
      <fieldset>
        <legend>Horários de Aula</legend>
        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class="bold">Turno:</td>
            <td>
              <select id='turnoEscola'>
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
        </table>

        <fieldset style='width:500px;'>
          <legend>Períodos</legend>
          <div id='ctnGridPeriodosAula'></div>
        </fieldset>

      </fieldset>

      <input type="button" id='processarHorarioAula'  value='Salvar'   acao    = 'I' />
      <input type="button" id='excluirVinculoPeriodo' value='Excluir'  style   = "display:none;" />
      <input type="button" id='cancelar'              value='Cancelar' onclick = "js_limpaGrade();" />

    </form>


  </div>
  <div class="subcontainer">

    <fieldset style='width:526px;'>
      <legend>Horários Inclusos</legend>
      <div id='cntGridHorariosInclusos'></div>
    </fieldset>

  </div>



<script type="text/javascript">

const MSG_HORARIOAULA = 'educacao.escola.edu1_horarioaula.';
const RPC_HORARIOAULA = 'edu4_horarioaula.RPC.php';

var aTurnosCadastrados         = [];
var aPeriodosEscolaCadastrados = []; // Períodos sem vínculo com a escola
var aPeriodosInclusos          = []; // Períodos de aula vínculados com a escola

var oGridPeriodosEscola          = new DBGrid('gridPeriodosEscola');
oGridPeriodosEscola.nameInstance = 'oGridPeriodosEscola';
oGridPeriodosEscola.setCheckbox(0);
oGridPeriodosEscola.setCellWidth( [ '0%', '49%', '17%', '17%', '17%' ] );
oGridPeriodosEscola.setHeader( [ 'codigo', 'Período', 'H. Início', 'H. Fim', 'Duração', 'periodo_aula' ] );
oGridPeriodosEscola.setCellAlign( [ 'left', 'left', 'left', 'left', 'left' ] );
oGridPeriodosEscola.setHeight(130);
oGridPeriodosEscola.aHeaders[1].lDisplayed = false;
oGridPeriodosEscola.aHeaders[6].lDisplayed = false;
oGridPeriodosEscola.show($("ctnGridPeriodosAula"));

var oGridHorariosInclusos          = new DBGrid('gridHorariosInclusos');
oGridHorariosInclusos.nameInstance = "oGridHorariosInclusos";
oGridHorariosInclusos.setCellWidth( [ '42.5%', '42.5%', '15%'] );
oGridHorariosInclusos.setHeader( [ 'Turno', 'Horário', 'Ação' ] );
oGridHorariosInclusos.setCellAlign( [ 'left', 'left', 'center' ] );
oGridHorariosInclusos.setHeight(130);
oGridHorariosInclusos.show($("cntGridHorariosInclusos"));

$(function () {

  js_divCarregando(_M(MSG_HORARIOAULA + "carregando_turnos"), "msgBox");
  js_buscaTurnos();
  js_buscaPeriodosAula();
  js_buscaPeriodosVinculados();
  js_removeObj("msgBox");

  for (var iTurno in aPeriodosInclusos) {

    for ( var iIndice in $('turnoEscola').options) {

      if ($('turnoEscola').options[iIndice].value == iTurno) {
        $('turnoEscola').options[iIndice].setAttribute('disabled', 'disabled');
      }
    }
  }
})();

/**
 * Busca os turnos cadastrados
 */
function js_buscaTurnos() {

  var oParametros       = { 'exec' : 'getTurnos' };
  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval('(' + oAjax.responseText + ')' );

    aTurnosCadastrados = oRetorno.aTurnos;
    js_montaTurnos();
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}


/**
 * Monta o combobox com os turnos cadastrados
 * @return
 */
function js_montaTurnos() {

  aTurnosCadastrados.each( function( oTurno ) {
    $('turnoEscola').add(new Option(oTurno.sDescricao.urlDecode(), oTurno.iCodigo));
  });
}


/**
 * Busca os períodos cadastrados na secretaria da educação
 * @return
 */
function js_buscaPeriodosAula () {

  if (aPeriodosEscolaCadastrados.length > 0 ) {

    js_renderizaPeriodos();
    return;
  }

  var oParametros = { 'exec' : 'getPeriodosEscola' };

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval('(' + oAjax.responseText + ')' );

    aPeriodosEscolaCadastrados = oRetorno.aPeriodos;
    js_renderizaPeriodos();
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}


function js_renderizaPeriodos () {

  oGridPeriodosEscola.clearAll(true);
  aPeriodosEscolaCadastrados.each (function ( oPeriodo ) {

    var aLinha = [];
    aLinha.push(oPeriodo.iCodigo);
    aLinha.push(oPeriodo.sDescricao.urlDecode());
    aLinha.push('');
    aLinha.push('');
    aLinha.push('');
    aLinha.push('');

    oGridPeriodosEscola.addRow(aLinha);
  });

  oGridPeriodosEscola.renderRows();

  aPeriodosEscolaCadastrados.each (function ( oPeriodo, i ) {

    var sIdDuracao        = 'duracao_'+oPeriodo.iCodigo;
    var sIdCodigoVinculo  = 'codigo_vinculo_periodo_'+oPeriodo.iCodigo;
    var oInputHoraDuracao = new Element('input', {'type':'text', 'class' : 'tamanhoInputHora readonly', 'id':sIdDuracao});
    oInputHoraDuracao.setAttribute('disabled', 'disabled');


    var oInputVinculo = new Element('input', {'type':'hidden', 'class' : 'readonly', 'id':sIdCodigoVinculo});


    var oHoraInicio = js_generateImput (oPeriodo, 'inicio');
    var oHoraFim    = js_generateImput (oPeriodo, 'fim');

    oHoraInicio.show( $(oGridPeriodosEscola.aRows[i].aCells[3].sId) );
    oHoraFim.show( $(oGridPeriodosEscola.aRows[i].aCells[4].sId) );
    $(oGridPeriodosEscola.aRows[i].aCells[5].sId).appendChild( oInputHoraDuracao );
    $(oGridPeriodosEscola.aRows[i].aCells[6].sId).appendChild( oInputVinculo );
  });
}


function js_generateImput (oDadosPeriodo, sTipo) {

  var sId    = sTipo + '_' +  oDadosPeriodo.iCodigo;
  var oInput = new DBInputHora( new Element('input', {'type':'text', 'id' : sId}) );
  oInput.getElement().setAttribute( 'tipo', sTipo );
  oInput.getElement().setAttribute( 'nome_periodo', oDadosPeriodo.sDescricao.urlDecode() );
  oInput.getElement().setAttribute( 'codigo_periodo', oDadosPeriodo.iCodigo );
  oInput.getElement().addClassName( 'tamanhoInputHora' );
  oInput.getElement().addClassName( 'readonly' );
  oInput.getElement().setAttribute('disabled', 'disabled');
  oInput.getElement().onchange = function (event) {
    js_calculaIntervalo(oInput.getElement(), event);
  };

  return oInput;
}


/**
 * Valida o horário informado para período e calcula a duração do período
 * @param  HTMLInputElement oElement Elemento que disparou o change
 * @param  event            oEvent   Evento disparado
 * @return {void}
 */
function js_calculaIntervalo(oElement, oEvent) {

  var iCodigo = oElement.getAttribute('codigo_periodo');

  var sIdDuracao = 'duracao_'+iCodigo;
  var sIdInicio  = 'inicio_'+iCodigo;
  var sIdFim     = 'fim_'+iCodigo;

  if (oElement.getAttribute('tipo') == 'fim' && $F(sIdInicio) == '') {

    $(sIdInicio).focus();
    alert( _M( MSG_HORARIOAULA + 'informe_hora_inicio'));
    return false;
  }

  if ($F(sIdInicio) == '' && $F(sIdFim) == '') {

    alert( _M( MSG_HORARIOAULA + 'informe_hora_inicio_fim'));
    return false;
  }

  if ($F(sIdInicio) != '' && $F(sIdFim) != '') {

    var aHoraInicio = $(sIdInicio).value.split(':');
    var aHoraFim    = $(sIdFim).value.split(':');
    var oDataInicio = new Date();
    var oDataFim    = new Date();
    oDataInicio.setHours(aHoraInicio[0], aHoraInicio[1]);
    oDataFim.setHours(aHoraFim[0], aHoraFim[1]);

    if (oDataFim.getTime() <= oDataInicio.getTime() ) {

      alert(_M( MSG_HORARIOAULA +'hora_final_menor_igual'));

      $(sIdFim).value     = "";
      $(sIdDuracao).value = "";

      oEvent.stopImmediatePropagation();
      setTimeout(function (event) {
        $(sIdFim).focus();
      }, 1);
      return false;
    }

    /**
     * Calcura a duração do período
     */
    var oDuracao = new Date();
    oDuracao.setTime(oDataFim.getTime() - oDataInicio.getTime());

    var iHoras   = js_strLeftPad(oDuracao.getUTCHours(), 2, '0');
    var iMinutos = js_strLeftPad(oDuracao.getUTCMinutes(), 2, '0');

    $(sIdDuracao).value = iHoras + ':' + iMinutos;
  }

  return true;
}


/**
 * Reescreve a função selectSingle da DBGrid
 */
var fGridSelectSingle = oGridPeriodosEscola.selectSingle;
oGridPeriodosEscola.selectSingle = function (oCheckbox, sRow, oRow) {

  var oInputInicio = $('inicio_'+oCheckbox.value);
  var oInputFim    = $('fim_'+oCheckbox.value);
  oInputInicio.setAttribute('disabled', 'disabled');
  oInputFim.setAttribute('disabled', 'disabled');
  oInputInicio.addClassName( 'readonly' );
  oInputFim.addClassName( 'readonly' );

  fGridSelectSingle.apply(this, arguments) ;

  if ( oCheckbox.checked ) {

    oInputInicio.removeClassName('readonly');
    oInputFim.removeClassName('readonly');
    oInputInicio.removeAttribute('disabled');
    oInputFim.removeAttribute('disabled');
  }

  return true;
};


function js_validaPeriodosSelecionados() {

  if ($F('turnoEscola') == '') {

    alert(_M(MSG_HORARIOAULA + "selecione_um_turno"));
    return false;
  }

  if ($$('#gridgridPeriodosEscola input[type="checkbox"]:checked').length == 0) {

    alert(_M(MSG_HORARIOAULA + "selecione_um_periodo"));
    return false;
  }

  var lErro = false;
  var aPeriodosSelecionados = [];

  $$('#gridgridPeriodosEscola input[type="checkbox"]:checked').each ( function(oElemento) {

    if( oElemento.id != 'mtodositensgridPeriodosEscola' ) {

      var iCodigoPeriodo = oElemento.value;

      var oHoraInicio  = $('inicio_'+iCodigoPeriodo);
      var oHoraFim     = $('fim_'+iCodigoPeriodo);
      var oHoraDuracao = $('duracao_'+iCodigoPeriodo);
      var oHoraVinculo = $('codigo_vinculo_periodo_'+iCodigoPeriodo);

      var sNomePeriodo = oHoraInicio.getAttribute('nome_periodo');

      var oMsgErro = {};
      if (oHoraInicio.value == '') {

        oMsgErro.periodo = sNomePeriodo;
        alert( _M( MSG_HORARIOAULA + "hora_inicio_nao_informada", oMsgErro ) );
        lErro = true;
        throw $break;
      }

      if (oHoraFim.value == '') {

        oMsgErro.periodo = sNomePeriodo;
        alert( _M( MSG_HORARIOAULA + "hora_final_nao_informada", oMsgErro) );
        lErro = true;
        throw $break;
      }

      if ( oHoraDuracao.value == '' ) {

        var event = new Event('change');

        if ( !js_calculaIntervalo( oHoraFim, event ) ){

          lErro = true;
          throw $break;
        }
      }

      var oPeriodoSelecionado = {'iPeriodo': iCodigoPeriodo, 'iCodigoVinculo' : oHoraVinculo.value,
        'sHoraInicio' : oHoraInicio.value, 'sHoraFim' : oHoraFim.value,
        'sDuracao' : oHoraDuracao.value};
      aPeriodosSelecionados.push(oPeriodoSelecionado);
    }
  });

  if (lErro) {
    return false;
  }
  return aPeriodosSelecionados;

}


$('processarHorarioAula').observe('click', function() {

  var aPeriodosValidados = js_validaPeriodosSelecionados();
  var aPeriodosExcluidos = [];

  if (typeof aPeriodosValidados == 'boolean') {
    return false;
  }

  var acao = $('processarHorarioAula').getAttribute('acao');

  if ( acao == 'A' ) {

    var aPeriodosSelecionado = aPeriodosInclusos[$F('turnoEscola')].aPeriodos;
    for ( var iIndice in aPeriodosSelecionado ) {

      if (typeof aPeriodosSelecionado[iIndice] == 'function') {
        continue;
      }

      var lPeriodoEncontrado = false;
      aPeriodosValidados.each(function (oPeriodo) {

        if (oPeriodo.iCodigoVinculo == aPeriodosSelecionado[iIndice].iCodigoVinculo) {

          lPeriodoEncontrado = true;
          throw $break;
        }
      });

      if ( !lPeriodoEncontrado ) {
        aPeriodosExcluidos.push(aPeriodosSelecionado[iIndice].iCodigoVinculo);
      }
    }
  }

  var iTurnoSelecionado          = $F('turnoEscola');
  var oParametros                = {'exec' : 'salvarPeriodoAula'};
  oParametros.iTurno             = iTurnoSelecionado;
  oParametros.aPeriodos          = aPeriodosValidados;
  oParametros.aPeriodosExcluidos = aPeriodosExcluidos;

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros)
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")");

    alert(oRetorno.sMessage.urlDecode());
    if ( parseInt(oRetorno.iStatus) == 2) {
      return false;
    }
    js_limpaGrade();
    js_atualizaSituacaoComboTurno(iTurnoSelecionado, true);
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
});


function js_limpaGrade() {

  $('processarHorarioAula').style.display  = '';
  $('excluirVinculoPeriodo').style.display = 'none';
  $('processarHorarioAula').setAttribute('acao', 'I');

  $('turnoEscola').value = '';
  $('turnoEscola').removeAttribute('disabled');

  js_renderizaPeriodos ();

  $$('#gridgridPeriodosEscola input[type="checkbox"]').each( function(oElement) {
    oElement.removeAttribute('disabled');
  });
  js_buscaPeriodosVinculados();
}


function js_buscaPeriodosVinculados() {

  var oParametros       = {'exec' : 'getPeriodosVinculados'};

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = js_retornoPeriodosVinculados;

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}


function js_retornoPeriodosVinculados(oAjax) {

  var oRetorno = eval('(' + oAjax.responseText + ')');

  oGridHorariosInclusos.clearAll(true);
  aPeriodosInclusos = oRetorno.aPeriodosEscola;

  var aCodigoTurno = [];
  for (var iTurno in aPeriodosInclusos ) {

    var aLinha   = [];
    var sHorario = aPeriodosInclusos[iTurno].sHoraInicio + ' às ' + aPeriodosInclusos[iTurno].sHoraFim;

    var oBtnAlterar = new Element('input', {'type':'button', 'id':'alertar'+iTurno, 'value': 'A'});
    var oBtnExcluir = new Element('input', {'type':'button', 'id':'excluir'+iTurno, 'value': 'E'});
    oBtnAlterar.setAttribute('codigo_turno', iTurno);
    oBtnExcluir.setAttribute('codigo_turno', iTurno);
    oBtnAlterar.setAttribute('descricao_turno', aPeriodosInclusos[iTurno].sTurno.urlDecode());
    oBtnExcluir.setAttribute('descricao_turno', aPeriodosInclusos[iTurno].sTurno.urlDecode());

    aLinha.push(aPeriodosInclusos[iTurno].sTurno.urlDecode());
    aLinha.push(sHorario);
    aLinha.push(oBtnAlterar.outerHTML+ ' ' + oBtnExcluir.outerHTML);

    aCodigoTurno.push(iTurno);
    oGridHorariosInclusos.addRow(aLinha);
  }
  oGridHorariosInclusos.renderRows();

  aCodigoTurno.each( function (iTurno){

    $('excluir'+iTurno).observe('click', function () {
      js_atualizaExclusao(iTurno);
    });

    $('alertar'+iTurno).observe('click', function () {
      js_atualizaAlteracao(iTurno);
    });
  });
}


function js_atualizaAlteracao (iTurno) {

  $('turnoEscola').value = iTurno;
  $('turnoEscola').setAttribute('disabled', 'disabled');
  js_renderizaPeriodos ();

  js_atualizaGradePeriodos(iTurno, true);

  $('processarHorarioAula').setAttribute('acao', 'A');
  $('processarHorarioAula').style.display  = '';
  $('excluirVinculoPeriodo').style.display = 'none';
}


function js_atualizaExclusao (iTurno) {

  $('turnoEscola').value = iTurno;
  $('turnoEscola').setAttribute('disabled', 'disabled');
  js_renderizaPeriodos ();

  js_atualizaGradePeriodos(iTurno, false);
  $('processarHorarioAula').style.display  = 'none';
  $('excluirVinculoPeriodo').style.display = '';

  $$('#gridgridPeriodosEscola input[type="checkbox"]').each( function(oElement) {
    oElement.setAttribute('disabled', 'disabled');
  });
}


function js_atualizaGradePeriodos (iTurno, lAlteracao) {

  var aPeriodosSelecionado = aPeriodosInclusos[iTurno].aPeriodos;

  for (var iIndice in aPeriodosSelecionado) {

    if (typeof aPeriodosSelecionado[iIndice] == 'function') {
      continue;
    }

    var iCodigoPeriodo = aPeriodosSelecionado[iIndice].iCodigoPeriodo;

    $('chkgridPeriodosEscola'+iCodigoPeriodo).checked  = true;

    var oInputInicio   = $('inicio_'+iCodigoPeriodo);
    var oInputFim      = $('fim_'+iCodigoPeriodo);
    var oInputDuracao  = $('duracao_'+iCodigoPeriodo);
    var oInputVinculo  = $('codigo_vinculo_periodo_'+iCodigoPeriodo);

    oInputInicio.value  = aPeriodosSelecionado[iIndice].sHoraInicio;
    oInputFim.value     = aPeriodosSelecionado[iIndice].sHoraFim;
    oInputDuracao.value = aPeriodosSelecionado[iIndice].sDuracao;
    oInputVinculo.value = aPeriodosSelecionado[iIndice].iCodigoVinculo;

    oInputInicio.setAttribute('disabled', 'disabled');
    oInputFim.setAttribute('disabled', 'disabled');

    oInputInicio.addClassName( 'readonly' );
    oInputFim.addClassName( 'readonly' );
    if (lAlteracao) {

      oInputInicio.removeAttribute('disabled', 'disabled');
      oInputFim.removeAttribute('disabled', 'disabled');

      oInputInicio.removeClassName( 'readonly' );
      oInputFim.removeClassName( 'readonly' );
    }
  }

  return true;
}


$('excluirVinculoPeriodo').observe('click', function() {

  var iTurnoSelecionado    = $F('turnoEscola');
  var aPeriodosVinculados  = [];
  var aPeriodosSelecionado = aPeriodosInclusos[iTurnoSelecionado].aPeriodos;
  for ( var iIndice in aPeriodosSelecionado ) {

    if (typeof aPeriodosSelecionado[iIndice] == 'function') {
      continue;
    }
    aPeriodosVinculados.push(aPeriodosSelecionado[iIndice].iCodigoVinculo);
  }

  var oParametros       = {'exec' : 'removerPeriodoAula'};
  oParametros.aPeriodos = aPeriodosVinculados;
  oParametros.iTurno    = $F('turnoEscola');

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")");

    alert(oRetorno.sMessage.urlDecode());
    if ( parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    js_limpaGrade();
    js_atualizaSituacaoComboTurno(iTurnoSelecionado, false);
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
});


function js_atualizaSituacaoComboTurno(iTurno, lBloqueia) {

  for ( var iIndice in $('turnoEscola').options) {

    if (lBloqueia && $('turnoEscola').options[iIndice].value == iTurno) {

      $('turnoEscola').options[iIndice].setAttribute('disabled', 'disabled');
      break;
    }

    if ( !lBloqueia && $('turnoEscola').options[iIndice].value == iTurno) {

      $('turnoEscola').options[iIndice].removeAttribute('disabled');
      break;
    }
  }
}

</script>