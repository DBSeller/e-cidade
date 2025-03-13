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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($_POST);

$db_opcao   = 1;
$clrhferiasperiodo = new cl_rhferiasperiodo;
$clrhferiasperiodo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh110_datainicial");
$clrotulo->label("rh110_datafinal");
$clrotulo->label("rh109_regist");
$anofolha = db_anofolha();
$mesfolha = db_mesfolha();

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, strings.js, prototype.js, AjaxRequest.js, DBLookUp.widget.js, datagrid.widget.js, estilos.css");
    ?>
</head>

<body class="body-default">
  <div class="container">
    <form id="form1" name="form1" action="" method="POST" onsubmit="return js_validacampos()" class="container" style="margin-top: 0">
      <fieldset>
        <legend>Autorização de Férias</legend>
        <table class="form-container">
          <tr>
            <td nowrap title="<?php echo $Trh110_datainicial; ?>">
              <label id="lbl_rh110_datainicial" for="rh110_datainicial"><?php echo $Lrh110_datainicial; ?></label>
            </td>
            <td>
              <?php
                db_inputdata('rh110_datainicial', '', '', '', true, 'text', $db_opcao);
                ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Trh110_datafinal; ?>">
              <label id="lbl_rh110_datafinal" for="rh110_datafinal"><?php echo $Lrh110_datafinal; ?></label>
            </td>
            <td>
              <?php
                db_inputdata('rh110_datafinal', '', '', '', true, 'text', $db_opcao);
                ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Trh109_regist; ?>">
              <label for="rh109_regist"><a href="" id="lbl_rh109_regist"><?php echo $Lrh109_regist; ?></a></label>
            </td>
            <td>
              <?php
                db_input('rh109_regist', 10, $Irh109_regist, true, "text", $db_opcao, 'data="rh01_regist"');
                db_input('z01_nome', 40, '', true, "text", 3);
                ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="Digite o Ano / Mês de competência">
              <strong>Ano / Mês:</strong>
            </td>
            <td>
              <?php db_input('anofolha', 4, 1, true, "text", 1, '');
                echo "&nbsp;/&nbsp;";
                db_input('mesfolha', 2, 1, true, "text", 1, '');  ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="opcoesAutorizado">Autorizadas:</label>
            </td>
            <td>
              <select id="opcoesAutorizado" style="width: 84px;">
                <option value="0">TODAS</option>
                <option value="1">SIM</option>
                <option value="2" selected="selected">NÃO</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_carregarEscalasFerias()" />
    </form>
  </div>

  <div style="max-width: 60%; margin: 15px auto; display: block;">
    <fieldset>
      <legend>Férias</legend>
      <div id="gridEscalasFerias" rel="ignore-css"></div>
    </fieldset>

    <div style="text-align: center;">
      <input type="button" id="autorizar" name="autorizar" value="Autorizar" onclick="js_processarEscalas()" />
      <input type="button" id="cancelar" name="cancelar" value="Cancelar" onclick="cancelarAutorizacao();" />
      <input type="button" id='btnRelatorio' value='Relatório' hidden onclick="js_imprimir()" />
    </div>
  </div>

  <script type="text/javascript">

    var pDataInicio, pDataFinal, pMatricula, pAno, pMes,pAutorizado;

    function js_imprimir() {
      
      let object = {
        'iMatricula': pMatricula,
        'sDataInicio': pDataInicio,
        'sDataFinal': pDataFinal,
        'AnoFolha': pAno,
        'MesFolha': pMes,
        'autorizadas': pAutorizado
      }
      
      openWindowWithPost("rec2_autorizarferias001.php", {
        json: Object.toJSON(window.oGridEscalasFerias.getAll('array')),
        object: Object.toJSON(object)
      });
    }
    function openWindowWithPost(url, data) {
    var form = document.createElement("form");
    form.target = "_blank";
    form.method = "POST";
    form.action = url;
    form.style.display = "none";

    for (var key in data) {
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

    var MENSAGEM = 'recursoshumanos/rh/rec4_autorizarferias001.';
    var rpc = 'rec4_autorizarferias.RPC.php';

    (function(oWindow) {

      oWindow.oGridEscalasFerias = new DBGrid("escalasFerias");
      oWindow.oGridEscalasFerias.nameInstance = "window.oGridEscalasFerias";

      oWindow.oGridEscalasFerias.setCheckbox(0);
      oWindow.oGridEscalasFerias.setHeader(["Codigo", "Matrícula", "Servidor", "Data Início", "Data Término", "Dias de Gozo", "Dias Abono", "Dias Pecúnia", "Autorizada", "RhFerias"]);
      oWindow.oGridEscalasFerias.setCellWidth([null, "8%", "40%", "8%", "8%", "8%", "8%", "8%", "10%", "1%"]);
      oWindow.oGridEscalasFerias.setCellAlign(["center", "center", "left", "center", "center", "center", "center", "center", "center", "center"]);
      oWindow.oGridEscalasFerias.setHeight("350");
      oWindow.oGridEscalasFerias.aHeaders[1].lDisplayed = false;
      oWindow.oGridEscalasFerias.aHeaders[10].lDisplayed = false;
      oWindow.oGridEscalasFerias.show($('gridEscalasFerias'));

      /**
       * Criação da Ancora para a matrícula.
       * @type  {DBLookUp}
       */
      var oMatricula = new DBLookUp($('lbl_rh109_regist'), $('rh109_regist'), $('z01_nome'), {
        'sArquivo': 'func_rhpessoal.php',
        'sObjetoLookUp': 'db_iframe_pessoal',
        'sLabel': 'Pesquisar Matrícula',
        'aParametrosAdicionais': ['sAtivos=true']
      });

    })(window);

    function js_carregarEscalasFerias() {
      pDataInicio =null;
      pDataFinal = null; 
      pMatricula = null;
      pAno = null ;
      pMes =null;
      pAutorizado = null;
      var sDataInicio, sDataFinal, iMatricula, Ano, Mes = null;

      if (!validaDatas()) {
        return false;
      }

      if (document.form1.mesfolha.value != '') {
        Mes = document.form1.mesfolha.value;
        pMes = document.form1.mesfolha.value;
      }

      if (document.form1.anofolha.value != '') {
        Ano = document.form1.anofolha.value;
        pAno = document.form1.anofolha.value;
      }

      if (document.form1.rh110_datainicial.value != '') {
        sDataInicio = document.form1.rh110_datainicial.value;
        pDataInicio = document.form1.rh110_datainicial.value;
      }

      if (document.form1.rh110_datafinal.value != '') {
        sDataFinal = document.form1.rh110_datafinal.value;
        pDataFinal = document.form1.rh110_datafinal.value;
      }

      if (document.form1.rh109_regist.value.trim() != '') {
        iMatricula = document.form1.rh109_regist.value;
        pMatricula = document.form1.rh109_regist.value;
      }
      pAutorizado = $F('opcoesAutorizado');
      
      if (!sDataInicio && !sDataFinal && !iMatricula && !Ano && !Mes || Mes && !Ano || Ano && !Mes) {
        alert(_M(MENSAGEM + "informe_pelo_menos_um_filtro"));
        return false;
      }

      iMatricula = document.form1.rh109_regist.value;

      var oParametros = {
        'exec': 'getEscalasFerias',
        'iMatricula': iMatricula,
        'sDataInicio': sDataInicio,
        'sDataFinal': sDataFinal,
        'AnoFolha': Ano,
        'MesFolha': Mes,
        'autorizadas': $F('opcoesAutorizado')
      };

      var oAjaxRequest = new AjaxRequest(
        rpc,
        oParametros,
        function(oAjax, lErro) {

          if (lErro) {
            alert(oAjax.message.urlDecode());
          } else {
            if (oAjax.aEscalasFerias.length == 0) {
              alert(_M(MENSAGEM + "nenhum_registro_encontrado"));
            }
            document.getElementById("btnRelatorio").removeAttribute("hidden");
            js_carregarGridEscalaFerias(oAjax.aEscalasFerias);
          }
        }
      );

      oAjaxRequest.setMessage('Buscando Escalas de Férias...');
      oAjaxRequest.execute();
    }

    function js_carregarGridEscalaFerias(aEscalasFerias) {

      window.oGridEscalasFerias.clearAll(true);

      for (var i = 0; i < aEscalasFerias.length; i++) {

        var oEscala = aEscalasFerias[i];
        var aDadosEscala = [
          oEscala.iCodigo,
          oEscala.iMatricula,
          oEscala.sNome.urlDecode(),
          oEscala.sDataInicio,
          oEscala.sDataFinal,
          oEscala.nDiasGozo,
          oEscala.nDiasAbono,
          oEscala.nDiasPecunia,
          oEscala.temAssentamentoAutorizacao.urlDecode(),
          oEscala.codigoRhFerias
        ];
        window.oGridEscalasFerias.addRow(aDadosEscala);
      }
      window.oGridEscalasFerias.renderRows();

      document.getElementById('autorizar').disabled = false;
    }

    function js_processarEscalas() {

      document.getElementById('autorizar').disabled = true;
      var anofolha = document.getElementById('anofolha').value;
      var mesfolha = document.getElementById('mesfolha').value;

      if (anofolha == '' || anofolha < <?= $anofolha ?>) {
        alert('Ano Incorreto.')
        document.getElementById('autorizar').disabled = false;
        return
      }
      if (mesfolha == '' || mesfolha < <?= $mesfolha ?> && anofolha <= <?= $anofolha ?>) {
        alert('Mês Incorreto.')
        document.getElementById('autorizar').disabled = false;
        return
      }
      arraymesano = [anofolha, mesfolha]


      var oParametros = {
        'exec': 'processarEscalasFerias',
        'aEscalas': window.oGridEscalasFerias.getSelection('array'),
        'AnoMesfolha': arraymesano
      };

      var oAjaxRequest = new AjaxRequest(
        rpc,
        oParametros,
        function(oAjax, lErro) {

          alert(oAjax.sMessage.urlDecode());

          if (lErro) {
            document.getElementById('autorizar').disabled = false;
          } else {
            js_carregarEscalasFerias();
          }
        }
      );

      oAjaxRequest.setMessage('Processando Escalas de Férias...');
      oAjaxRequest.execute();
    }

    function cancelarAutorizacao() {

      var linhas = window.oGridEscalasFerias.getSelection("object");

      if (linhas.length == 0) {

        alert('Nenhum período de férias selecionado.');
        return false;
      }

      var periodosFerias = [];

      linhas.each(function(linha) {
        periodosFerias.push(linha.aCells[0].getValue());
      });

      var parametros = {
        'exec': 'excluirPeriodo',
        'periodosFerias': periodosFerias
      };

      new AjaxRequest(rpc, parametros, function(retorno, erro) {

        alert(retorno.sMessage.urlDecode());

        if (!erro) {
          js_carregarEscalasFerias();
        }
      }).execute();
    }

    $('rh110_datainicial').observe('change', function() {
      validaDatas();
    });

    $('rh110_datafinal').observe('change', function() {
      validaDatas();
    });

    function validaDatas() {

      if ($F('rh110_datainicial') == '' || $F('rh110_datafinal') == '') {
        return true;
      }

      var dataInicioParticionada = $F('rh110_datainicial').split('/');
      var dataFinalParticionada = $F('rh110_datafinal').split('/');

      var dataInicio = new Date(dataInicioParticionada[2], dataInicioParticionada[1], dataInicioParticionada[0]);
      var dataFinal = new Date(dataFinalParticionada[2], dataFinalParticionada[1], dataFinalParticionada[0]);

      if (dataInicio.getTime() > dataFinal.getTime()) {

        alert('Data Inicial deve ser maior que a Data Final.');
        return false;
      }

      return true;
    }
  </script>
  <?php db_menu() ?>
</body>

</html>