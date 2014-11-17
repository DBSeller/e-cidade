<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
</head>
<body bgcolor="#CCCCCC" >

<div class="container">

  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>

  <fieldset style="width: 500px;">
    <legend>Relatório Professores por Escola</legend>

    <table class="form-container">
      <tr>
        <td nowrap="nowrap" >Escola:</td>
        <td nowrap="nowrap" id='listaEscola'></td>
      </tr>
      <tr>
        <td nowrap='nowrap'>Área de Trabalho:</td>
        <td nowrap='nowrap'>
          <select id="areaTrabalho" >
          </select>
        </td>
      </tr>
      <tr>
        <td nowrap='nowrap' colspan="2">
          <input type="checkbox" name="disciplina" id="disciplina" value="" checked>
          <label for="disciplina">Mostrar disciplinas que o professor leciona.</label>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Imprimir" id="imprimir" name="imprimir" disabled="disabled" />
</div>

</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>

<script type="text/javascript">

  const MSG_EDU2PROFESSORESCOLA = 'educacao.escola.edu2prosseforescola.';
  var oEscola = new DBViewFormularioEducacao.ListaEscola();

  var fFuncaoLoadEscola = function() {

    js_limpaAtividades();
    if (this.oCboEscola.options.length > 2) {
      this.oCboEscola.value = '';
    } else {
      js_buscaAreaTrabalho();
    }
  };

  var fFuncionChange = function() {

    var oEscolaSelecionada   = oEscola.getSelecionados();
    js_limpaAtividades();
    if (oEscolaSelecionada.codigo_escola != '') {
      js_buscaAreaTrabalho();
    }
  }

  oEscola.setCallBackLoad(fFuncaoLoadEscola);
  oEscola.setCallbackOnChange(fFuncionChange);
  oEscola.habilitarOpcaoTodas(false);
  oEscola.show($('listaEscola'));


  function js_limpaAtividades() {

    $('areaTrabalho').innerHTML = '';
    $('imprimir').setAttribute('disabled', 'disabled');
  }

  function js_buscaAreaTrabalho() {

    var oEscolaSelecionada = oEscola.getSelecionados();

    var oParametro     = {};
    oParametro.exec    = 'getAreasTrabalho';
    oParametro.iEscola = oEscolaSelecionada.codigo_escola;

    js_divCarregando( _M(MSG_EDU2PROFESSORESCOLA+'aguarde_buscando_areatrabalho'), "msgBox");

    var oObjeto        = {};
    oObjeto.method     = 'post';
    oObjeto.parameters = 'json='+Object.toJSON(oParametro);
    oObjeto.onComplete = js_retornoProfessorEscola;

    new Ajax.Request('edu_educacaobase.RPC.php', oObjeto);
  }

  function js_retornoProfessorEscola(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval( "(" + oAjax.responseText + ")" );

    $('areaTrabalho').add( new Option("TODAS", 0) );
    oRetorno.aAreaTrabalho.each( function (oAreaTrabalho) {

      var oOption = new
        $('areaTrabalho').add( new Option(oAreaTrabalho.ed25_c_descr, oAreaTrabalho.ed25_i_codigo) );
    });

    if (oRetorno.aAreaTrabalho.length > 0) {
      $('imprimir').removeAttribute('disabled');
    }
  }

  $('imprimir').observe('click', function() {

    var disciplina = "N";
    if ( $('disciplina').checked ) {
      disciplina = "S";
    }
    var oEscolaSelecionada = oEscola.getSelecionados();

    var sUrl  = 'edu2_professorescola002.php?escola='+oEscolaSelecionada.codigo_escola;
        sUrl += '&area='+$F('areaTrabalho')+'&disciplina='+disciplina;

    jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  });

</script>