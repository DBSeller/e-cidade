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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/ConsistenciaSistema.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form>
        <fieldset>
            <legend>Selecione o tipo de Consistência que deseja realizar</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="tipos-inconsistencia">Consistência:</label>
                    </td>
                    <td>
                        <select id="tipos-inconsistencia">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="descricaoConsistencia">Descrição: </label>
                    </td>
                    <td>
                        <span id="help-consistencia">-</span>
                    </td>
                </tr>
                <tr style="display: none" id="rowAtributos">
                    <table id="tableAtributos">

                    </table>
                </tr>
            </table>
        </fieldset>
        <input id="processar" type="button" value="Processar" disabled="disabled"/>
    </form>

</div>
<?php
db_menu();
?>
</body>
<script type="text/javascript">
  var get = js_urlToObject();
  var dadosConsistencia = [];

  (function () {
    var parametros = {'exec': 'getArquivosConsistenciaPorTipo', 'tipo': get.tipo};
    new AjaxRequest('con4_consistencia.RPC.php', parametros, function (retorno, erro) {
      if (erro) {
        alert(retorno.sMessage);
        return;
      }
      dadosConsistencia = retorno.consistencia;

      $('tipos-inconsistencia').options.length = 0;
      $('tipos-inconsistencia').add(new Option('Selecione', ''));
      for (var opcao of retorno.consistencia) {
        $('tipos-inconsistencia').add(new Option(opcao.jsonConsistencia.nome, opcao.id));
      }


    }).setMessage('Carrregando os tipos de consistência, aguarde.').execute();
  })();

  $('tipos-inconsistencia').addEventListener('change', function () {
    $('processar').setAttribute('disabled', 'disabled');

    if (this.value == '') {
      $('help-consistencia').innerHTML = '-';
      return;
    }

    for (var consistencia of dadosConsistencia) {
      if (this.value == consistencia.id) {
        $('help-consistencia').innerHTML = consistencia.jsonConsistencia.descricao;
        escrevrerFormularioFiltro(consistencia.jsonConsistencia);
        break;
      }
    }

    if (this.value != '') {
      $('processar').removeAttribute('disabled');
    }
  });

  $('processar').addEventListener('click', function () {

    var consistenciaSelecionada = null;
    var idConsitencia = $F('tipos-inconsistencia');
    for (var consistencia of dadosConsistencia) {
      if (idConsitencia == consistencia.id) {
        consistenciaSelecionada = consistencia;
        break;
      }
    }

    var consistenciaSistema = new ConsistenciaSistema(consistenciaSelecionada);
    consistenciaSistema.abrir();

  });

  function escrevrerFormularioFiltro(json) {

    $('rowAtributos').style.display = 'none';
    $('tableAtributos').innerHTML = '';
    if (!empty(json.filtros)) {

      $('rowAtributos').style.display = '';
      for (var campo of json.filtros.campos) {

        var linha = document.createElement("tr");
        var celulaLabel = document.createElement("td");

        var label = document.createElement("label");
        label.htmlFor = campo.nome;
        label.innerHTML = '<b>' + campo.label + ':</b>';
        celulaLabel.appendChild(label);


        var celulaValor = document.createElement("td");
        var campoHtml = document.createElement("input");
        campoHtml.id = campo.nome;
        campoHtml.className = 'filtroDinamico';
        campoHtml.setAttribute('tipo', campo.tipo);
        celulaValor.appendChild(campoHtml);

        linha.appendChild(celulaLabel);
        linha.appendChild(celulaValor);
        $('tableAtributos').appendChild(linha);
        console.log(campo.tipo);
        switch (campo.tipo) {

          case 'data':

             new DBInputDate(campoHtml);
            break;
        }

      }

    }
  }
</script>
</html>
