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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_app.utils.php");
require_once modification("classes/db_tipoasse_classe.php");

$iInstituicao = db_getsession("DB_instit");
?>

<html>
<head>
  <?php
  db_app::load('
    scripts.js,
    strings.js,
    prototype.js,
    estilos.css,
    datagrid.widget.js,
    AjaxRequest.js,
    DBLancador.widget.js,
    DBLookUp.widget.js,
    DBDownload.widget.js
  ');
  ?>
  <script type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
<form name="form1" id="form1">
    <div id="container" class="container">
        <fieldset>
            <legend>Reabertura do Período de Efetividade</legend>
            <table class="form-container">
                <tbody>
                <tr>
                    <td class="field-size2">
                        <strong>
                            <label for="exercicio">Exercício:</label>
                        </strong>
                    </td>
                    <td>
                        <?php

                        $sSqlExercicios = "select distinct rh186_exercicio::varchar as anousu";
                        $sSqlExercicios .= "           from configuracoesdatasefetividade";
                        $sSqlExercicios .= "          where rh186_instituicao = {$iInstituicao}";
                        $sSqlExercicios .= "          order by anousu desc";
                        $rsExercicios = db_query($sSqlExercicios);

                        $aExercicios[db_getsession('DB_anousu')] = db_getsession('DB_anousu');
                        for ($i = 0; $i < pg_num_rows($rsExercicios); $i++) {
                            $iExercicio = db_utils::fieldsmemory($rsExercicios, $i)->anousu;
                            $aExercicios[$iExercicio] = $iExercicio;
                        }

                        $iExercicio = db_getsession('DB_anousu');
                        db_select('iExercicio', $aExercicios, true, 1);

                        ?>
                    </td>
                </tr>
                <tr>
                  <td class="field-size2">
                    <label for="filtrarMatricula">Filtrar por matrícula:</label>
                  </td>
                  <td>
                    <select id="filtrarMatricula" >
                      <option value="f">Não</option>
                      <option value="t">Sim</option>
                    </select>
                  </td>
                </tr>
                <tr id="trFiltroMatriculas" style="display: none;">
                  <td colspan="2">
                    <div id="ctnLancador"></div>
                  </td>
                </tr>
                </tbody>
            </table>
            <div id="grid_registros" style="margin-top: 10px; width:650px"></div>
            <div id="data" style="display: none;">
                <?php db_inputdata('data_modelo', null, null, null, true, 'text', 1); ?>
            </div>
        </fieldset>
        <input type="button" name="btnSalvar" id="btnSalvar" value="Reabrir" onclick="salvar()"/>
    </div>
    <?php db_menu(); ?>
</form>
</body>
<script type="text/javascript">

  $('iExercicio').setAttribute('rel', 'ignore-css');
  $('iExercicio').addClassName("field-size2");
  $('filtrarMatricula').setAttribute('rel', 'ignore-css');
  $('filtrarMatricula').addClassName("field-size2");

  var lancadorMatriculas = new DBLancador('lancadorMatriculas');
  lancadorMatriculas.setNomeInstancia('lancadorMatriculas');
  lancadorMatriculas.setTextoFieldset('Matrículas');
  lancadorMatriculas.setLabelAncora('Matrícula: ');
  lancadorMatriculas.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist','z01_nome']);
  lancadorMatriculas.show($('ctnLancador'));
  //lancadorMatriculas.getRegistros();

  var sUrl = "rec4_encerramentoefetividade.RPC.php";

  var oGridRegistros              = new DBGrid("dataGridRegistros");
  oGridRegistros.nameInstance = "oGridRegistros";
  oGridRegistros.setSelectAll(false);
  oGridRegistros.setHeight(305);
  oGridRegistros.setCheckbox(0);
  oGridRegistros.setHeader(["Competência","Data Início", "Data Fechamento", "Data Entrega"]);
  oGridRegistros.setCellWidth(["30%","35%", "35%"]);
  oGridRegistros.setCellAlign(["center","center", "center", "center"]);
  oGridRegistros.aHeaders[4].lDisplayed = false;
  oGridRegistros.show( $('grid_registros') );

  $('filtrarMatricula').onchange = function() {
    $('trFiltroMatriculas').style.display = 'none';
    
    if ($F('filtrarMatricula') == 't') {
      $('trFiltroMatriculas').style.display = '';
    }
  }

  $('iExercicio').onchange = function() {
    js_carregarRegistros();
  }

  function js_carregarRegistros() {

    var iExercicio   = $F('iExercicio');
    var oParametros  = { 'exec' : 'carregarConfiguracoes', 'iExercicio' : $F('iExercicio')};
    var oAjax = new AjaxRequest( sUrl, oParametros, function (oRetorno, lErro) {

      if ( lErro ) {
        alert(oRetorno.message);
      }

      oGridRegistros.clearAll(true);
      oRetorno.aConfiguracoes.each( function (oCompetencia) {

        var aLinha = [];
        aLinha.push(oCompetencia.sCompetencia);
        aLinha.push(oCompetencia.dDataInicioEfetividade);
        aLinha.push(oCompetencia.dDataFechamentoEfetividade);
        aLinha.push(oCompetencia.dDataEntregaEfetividade);

        var lLiberaLinha = !oCompetencia.lProcessado;
        oGridRegistros.addRow( aLinha, false, lLiberaLinha );
      });

      oGridRegistros.renderRows();
      desabilitarLinhasCheckbox();
    });

    oAjax.setMessage('Carregando competências do exercício...');
    oAjax.execute();
  }

  js_carregarRegistros();

  function salvar() {

    var aLinhasSelecionadas = oGridRegistros.getSelection();

    if (aLinhasSelecionadas.length == 0 ) {

      alert('Nenhuma competência selecionada para o exercício informado.');
      return;
    }

    var aCompetenciasReabrir = [];
    aLinhasSelecionadas.each(function (aCells) {
      aCompetenciasReabrir.push(aCells[0]);
    });

    var iExercicio   = $F('iExercicio');
    var formData = new FormData();
    var parametros = {};

    parametros.exec = 'reabrirCompetencia';
    parametros.iExercicio = $F('iExercicio');
    parametros.filtrarMatricula = $F('filtrarMatricula');
    parametros.aCompetencias = aCompetenciasReabrir;
    parametros.matriculas = new Array();

    if ($F('filtrarMatricula') == 't') {
      parametros.matriculas = lancadorMatriculas.getRegistros(); 

      if (parametros.matriculas.length == 0) {
        alert('Nenhuma matrícula selecionada.');
        return false;
      } 
    }

    formData.append('json', JSON.stringify(parametros));
    HttpClient.post(sUrl, {body: formData, reportMessage: 'Reabrindo competências...'})
              .then(function(retorno){

                  

                  if (retorno.erro) {
                    alert(retorno.message)
                    return;
                  }

                  if (retorno.possuiInconsistencia) {
                    if (confirm(retorno.message)) {
                      var oDownload = new DBDownload();
                      oDownload.addFile(retorno.caminhoArquivo, retorno.nomeArquivo);
                      oDownload.fDownload(retorno.caminhoArquivo);
                    }
                    return;
                  }

                  alert(retorno.message)

                  $$('input:checkbox:checked').forEach(function (element) {

                    element.parentElement.parentElement.removeClassName('marcado');
                    element.disabled = true;
                    element.checked  = false;

                  });
                  js_carregarRegistros();                
              });
  }

  function desabilitarLinhasCheckbox() {
    oGridRegistros.getRows().each( function (linha, indice) {
      if (linha.lDisabled) {
        linha.sStyle = ' height: 1.5em; background-color: #e9ecef;';
        linha.aCells[0].content = "";
      }
    });

     oGridRegistros.renderRows();
  }
</script>
</html>
