<div class="container">
  <form>
    <fieldset style= "width:450px">
      <legend>Horários da Escola</legend>
      <input type="hidden" value="" id="iHorarioEscola">
      <table class="form-container">
        <tr>
          <td class="bold">
            Turno:
          </td>
          <td>
            <select id='iTurno'>
              <option value='0' > Selecione... </option>
              <option value='1' > Manhã </option>
              <option value='2' > Tarde </option>
              <option value='3' > Noite </option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="bold">
            Hora:
          </td>
          <td class="bold">
            <input type='text' id='sHoraInicio' name='sHoraInicio' maxlength='5' />
            às
            <input type='text' id='sHorarioFim' name='sHorarioFim' maxlength='5' />
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Salvar" id="btnSalvar" onclick="salvarHorarioEscola()">
    <input type="button" value="Cancelar" id="btnCancelar" onclick="limpaFormularioHorarioEscola()">
  </form>

  <fieldset>
    <legend>Turnos Cadastrados</legend>
    <div id='ctnGradeHorario'></div>
  </fieldset>
</div>
<script>
const MENSAGEM_HORARIOESCOLA001 = "educacao.escola.edu1_horariosfuncionamento.";

var oGet         = js_urlToObject();
var sUrlRpc      = 'edu4_horariosescola.RPC.php';
var oGridHorario = new DBGrid('gridHorario');
var aTurnos      = new Array();
    aTurnos[1]   = "Manhã";
    aTurnos[2]   = "Tarde";
    aTurnos[3]   = "Noite";

new DBInputHora( $('sHoraInicio') );
new DBInputHora( $('sHorarioFim') );
$('sHoraInicio').addClassName('field-size2');
$('sHorarioFim').addClassName('field-size2');

/**
 * Monta a grid contendo os turnos já cadastrados para a escola
 */
function montaGrid() {

  oGridHorario.nameInstance = "gridHorario";
  oGridHorario.setCellAlign(new Array("center","center","center", "right"));
  oGridHorario.setCellWidth(new Array("20%","60%", "20%", '0%'));
  oGridHorario.setHeader(new Array("Turno","Horário", "Ação", "Código"));
  oGridHorario.setHeight('200px;');
  oGridHorario.aHeaders[3].lDisplayed = false;
  oGridHorario.show($('ctnGradeHorario'));
}
montaGrid();

/**
 * Busca os horários já cadastrados para a escola e os adiciona a Grid
 */
function buscaHorariosEscola() {

  js_divCarregando( _M( MENSAGEM_HORARIOESCOLA001 + 'buscando_horarios'), 'msgBoxA');

  var oParametros           = new Object();
      oParametros.sExecucao = 'buscaHorariosEscola';
      oParametros.iEscola   = oGet.ed17_i_escola;

  var oAjax = new Ajax.Request(sUrlRpc,
                            {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametros),
                                onComplete: retornoBuscaHorariosEscola
                            });
}

/**
 * Adiciona os dados retornados do horário da escola e os adiciona a grid
 * @param  Object oResponse 
 */
function retornoBuscaHorariosEscola( oResponse ) {

  js_removeObj('msgBoxA');

  var oRetorno = eval("("+oResponse.responseText+")");
  var iLinha   = 0;
  var aOptions = $$('select#iTurno option');

  /**
   * Habilita novamente todas a opções de turno
   */
  for( var iContador = 0; iContador < aOptions.length; iContador++ ){
    aOptions[iContador].disabled = '';
  }

  oGridHorario.clearAll(true);

  /**
   * Botão para alterar o horário da escola
   */
  oInputAlterar           = document.createElement( 'input' );
  oInputAlterar.addClassName( 'bold' );
  oInputAlterar.setAttribute( 'name', 'oInputAlterar' );
  oInputAlterar.setAttribute( 'value', 'A' );
  oInputAlterar.setAttribute( 'type', 'button' );
  oInputAlterar.setAttribute( 'id', 'oInputAlterar' );
  oInputAlterar.setAttribute( 'onclick', 'alterarHorarioEscola()' );

  /**
   * Botão para excluir o horário da escola
   */
  oInputExcluir           = document.createElement( 'input' );
  oInputExcluir.addClassName( 'bold' );
  oInputExcluir.setAttribute( 'name', 'oInputExcluir' );
  oInputExcluir.setAttribute( 'value', 'E' );
  oInputExcluir.setAttribute( 'type', 'button' );
  oInputExcluir.setAttribute( 'id', 'oInputExcluir' );

  oRetorno.aHorariosEscola.each( function ( oHorarioEscola ) {

    var aLinhas = new Array();
    aOptions[oHorarioEscola.iTurno].disabled = 'disabled';

    oInputExcluir.setAttribute( 'onclick', 'excluirHorarioEscola('+ oHorarioEscola.iCodigo +')' );
    oInputAlterar.setAttribute( 'onclick', 'alterarHorarioEscola('+ iLinha +')' );

    aLinhas.push( aTurnos[oHorarioEscola.iTurno] );
    aLinhas.push( oHorarioEscola.sHoraInicio + ' às ' + oHorarioEscola.sHorarioFim );
    aLinhas.push( oInputAlterar.outerHTML + oInputExcluir.outerHTML );
    aLinhas.push( oHorarioEscola.iCodigo );
    oGridHorario.addRow(aLinhas, null, null, null);

    iLinha++;
  });

  oGridHorario.renderRows();
}
buscaHorariosEscola();

/**
 * Salva/Altera o Horário de funcionado da escola
 */
function salvarHorarioEscola() {

  if ( !validaHorarioEscola() ) {
    return;
  }

  js_divCarregando( _M( MENSAGEM_HORARIOESCOLA001 + 'salvando_horarios'), 'msgBoxB');

  var oParametros                = new Object();
      oParametros.sExecucao      = 'salvaHorarioEscola';
      oParametros.iHorarioEscola = $F('iHorarioEscola');
      oParametros.iEscola        = oGet.ed17_i_escola;
      oParametros.iTurno         = $F('iTurno');
      oParametros.sHoraInicio    = $F('sHoraInicio');
      oParametros.sHorarioFim    = $F('sHorarioFim');

  var oAjax = new Ajax.Request(sUrlRpc,
                            {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametros),
                                onComplete: retornoSalvarHorarioEscola
                            });
}

/**
 * Após salvar/alterar o horário da escola, limpa o formulário e recarrega a grid dos horários atualizando as informações
 * @param  {integer} oResponse
 */
function retornoSalvarHorarioEscola( oResponse ) {

  js_removeObj('msgBoxB');

  var oRetorno = eval( "("+ oResponse.responseText +")" );
  alert(oRetorno.sMensagem.urlDecode());

  limpaFormularioHorarioEscola();
  buscaHorariosEscola();
}

/**
 * Validações necessárias para incluir/alterar algum horário para escola
 * @return boolean
 */
function validaHorarioEscola() {

  if ( $F('iTurno') == 0 ) {

    alert( _M( MENSAGEM_HORARIOESCOLA001 + 'informe_turno') );
    return false;
  }

  if ( $F('sHoraInicio') == '' ) {

    alert( _M( MENSAGEM_HORARIOESCOLA001 + 'informe_horario_inicial') );
    return false;
  }

  if ( $F('sHorarioFim') == '' ) {

    alert( _M( MENSAGEM_HORARIOESCOLA001 + 'informe_horario_final') );
    return false;
  }

  if ( $F('sHorarioFim') < $F('sHoraInicio') ) {

    alert( _M( MENSAGEM_HORARIOESCOLA001 + 'horario_final_maior_inicial') );
    return false;
  }

  return true;
}

/**
 * Limpa os campos existentes no formulário
 */
function limpaFormularioHorarioEscola() {

  $('iHorarioEscola').value = 0;
  $('iTurno').value         = 0; 
  $('sHoraInicio').value    = '';
  $('sHorarioFim').value    = '';
  $('iTurno').disabled      = '';
}

/**
 * Exclui um horário já existente na Escola
 * @param  {integer} iHorarioEscola Código do horarioescola
 */
function excluirHorarioEscola( iHorarioEscola ) {

  if ( !confirm( _M( MENSAGEM_HORARIOESCOLA001 + 'confirma_exclusao') ) ){
    return;
  }

  js_divCarregando( _M( MENSAGEM_HORARIOESCOLA001 + 'excluindo_horario') , "msgBoxC");

  var oParametros                = new Object();
    oParametros.sExecucao      = 'excluiHoraEscola';
    oParametros.iHorarioEscola = iHorarioEscola;

  var oAjax = new Ajax.Request(sUrlRpc,
                            {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametros),
                                onComplete: retornoExcluirHorarioEscola
                            });
}

/**
 * Após excluir, lança mensagem de confirmação e recarrega a grid com os horários, atualizando os valores.
 * @param  {Object} oResponse 
 */
function retornoExcluirHorarioEscola( oResponse ) {

  js_removeObj( "msgBoxC" );

  var oRetorno = eval( '('+ oResponse.responseText +')' );
  alert(oRetorno.sMensagem.urlDecode());
  limpaFormularioHorarioEscola();
  buscaHorariosEscola();
}

/**
 * Altera um horário já cadastrado para a escola
 * @param  integer iLinha 
 */
function alterarHorarioEscola( iLinha ) {

  var aColunas  = oGridHorario.getRowById('gridHorariorowgridHorario'+iLinha);
  var aHorarios = aColunas.aCells[1].content.split(' às ');

  $('iTurno').value         = aTurnos.indexOf(aColunas.aCells[0].content);
  $('iTurno').disabled      = 'disabled';
  $('sHoraInicio').value    = aHorarios[0];
  $('sHorarioFim').value    = aHorarios[1];
  $('iHorarioEscola').value = aColunas.aCells[3].content
}
</script>