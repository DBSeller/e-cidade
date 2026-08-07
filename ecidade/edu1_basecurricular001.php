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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>
  <form class='container'>
    <fieldset>
      <legend>Base</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="cursos">Cursos:</label>
          </td>
          <td>
            <select id='cursos' class="field-size-max">
              <option value="">Selecione</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipo">Tipo:</label>
          </td>
          <td>
            <select id='tipo' class="field-size-max">
              <option value="1">INICIAL</option>
              <option value="2">FINAL</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="descricao">Nome:</label>
          </td>
          <td>
            <input type="text" name="descricao" id='descricao' onblur="js_ValidaMaiusculo(this, 't', event);"
                   class="field-size-max" oninput="js_ValidaCampos(this, 0, 'Nome da Base', 'f', 't', event);" />
          </td>
        </tr>
      </table>
      <fieldset class=" separator " style="width:  300px;">
        <legend>Selecione as etapas da Base</legend>
        <div id='ctnGrid'></div>
      </fieldset>
    </fieldset>
    <input type="button" name="salvar"  id='btnSalvar'  value="Salvar" />
    <input type="hidden" name="codigo"  id='id_base'    value="" />
  </form>
<?php
  db_menu();
?>
</body>
<script type="text/javascript">

var sMsg = 'educacao.secretariaeducacao.edu1_basecurricular001.';
// edu4_transferiralunoencerrado001.php
var oCollection = new Collection().setId("iEtapa");
var oGridEtapas = new DatagridCollection(oCollection).configure({
  order    : false,
  height   : 160
});

oGridEtapas.getGrid().setCheckbox(1);
oGridEtapas.addColumn("sEtapa", {
  label : "Etapa",
  align : "left",
  width : "100%"
});

oGridEtapas.addColumn("iEtapa", {
  label : "codigo",
  align : "left",
  width : ""
});

oGridEtapas.hideColumns([2]); // esconde coluna com código
oGridEtapas.show($('ctnGrid'));

/**
 * Busca os cursos equivalentes
 */
(function () {

  new AjaxRequest('edu4_cursoequivalencia.RPC.php', {exec: 'buscarCursos', onlyActive: true},
    function (retorno, erro) {

      if ( erro ) {

        alert(retorno.sMessage);
        return ;
      }

      for (var curso of retorno.aCursos) {
        $('cursos').add( new Option(curso.sCurso, curso.iCurso ) );
      }
    }
  ).setMessage( _M(sMsg + 'buscando_cursos') ).execute();
})();


$('cursos').addEventListener('change', function(elemento) {
  validaCursoSelecionado();
});

$('tipo').addEventListener('change', function() {
  validaCursoSelecionado();
});

/**
 * valida se o curso esta selecionado e realiza busca das etapas
 */
function validaCursoSelecionado() {

  if ( $F('cursos') == '' ) {

    limparDados();
    return;
  }

  buscarEtapas();
}

function limparDados() {

  oGridEtapas.clear();
  oGridEtapas.reload();
  $('id_base').value   = '';
  $('descricao').value = '';
}

/**
 * Busca as etapas do curso selecionado, caso exista uma base
 * @return {void}
 */
function buscarEtapas() {

  limparDados();
  new AjaxRequest('edu4_base.RPC.php', {exec: 'etapasPorCursoBaseCurricular', iCurso: $F('cursos'), iTipo : $F('tipo')},
    function (retorno, erro) {

      if ( erro ) {

        alert(retorno.sMessage);
        return ;
      }
      $('id_base').value   = retorno.iBase;
      $('descricao').value = retorno.sBase;

      if ( !empty(retorno.iBase) ) {
      }
      for (var oEtapa of retorno.aEtapas ) {

        oCollection.add(oEtapa);
        if (oEtapa.lCheck) {
          oGridEtapas.addSelectedItens(oEtapa.iEtapa)
        }
      }

      oGridEtapas.reload();
    }
  ).setMessage( _M(sMsg + 'buscando_cursos') ).execute();
}

function validaSalvar() {

  if ( empty($F('descricao')) ) {

    alert( _M(sMsg + 'informe_nome') );
    return false;
  }

  return true;
}

$('btnSalvar').addEventListener('click', function() {

  // console.log(oGridEtapas.getSelectedItens());
  if ( !validaSalvar() ) {
    return;
  }
  var aGridSelecionados   = oGridEtapas.getGrid().getSelection();
  var aEtapasSelecionadas = [];
  for ( var etapa of aGridSelecionados ) {
    aEtapasSelecionadas.push(etapa[0]);
  }
  // // console.log(aGridSelecionados);
  // return;

  var oParametros = {
    exec    : 'salvar',
    iCurso  : $F('cursos'),
    iTipo   : $F('tipo'),
    sNome   : $F('descricao'),
    aEtapas : aEtapasSelecionadas,
    iBase   : $F('id_base')
  }
  new AjaxRequest('edu4_base.RPC.php', oParametros,
    function (retorno, erro) {

      alert(retorno.sMessage);
      if ( erro ) {
        return;
      }
      $('cursos').value    = '';
      limparDados();
    }
  ).setMessage( _M(sMsg + 'salvando_base') ).execute();
});
</script>
</html>
