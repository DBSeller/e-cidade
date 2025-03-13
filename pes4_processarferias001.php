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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();
$oRotulo->label("rh01_regist");
$oRotulo->label("z01_nome");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form id="frmProcessarFerias">
        <fieldset>
          <legend>Processamento de Férias</legend>
          <table cellpadding="0" cellspacing="0" class="form-container">
            <tr>
              <td>
                <label>Competência: </label>
              </td>
              <td id="containerCompetencia">

              </td>
            </tr>
            <tr>
              <td>
                 <b>Data de Início:</b>
              </td>
              <td>
                <?php
                  db_inputdata("data_inicio_gozo", null, null, null, true, "text", 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label>
                  <?php
                  db_ancora($Lrh01_regist, "js_pesquisar_matricula(true);", 1);
                  ?>
                </label>
              </td>
              <td>
                <?php
                db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisar_matricula(false);'");
                db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3);
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <label for="opcoesProcessadas">Processadas:</label>
              </td>
              <td>
                <select id="opcoesProcessadas" style="width: 80px;">
                  <option value="0">TODAS</option>
                  <option value="1">SIM</option>
                  <option value="2" selected="selected">NÃO</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" value="Pesquisar" id="btnPesquisar">
        <fieldset style="width:800px">
          <legend>Férias Disponíveis</legend>
          <div id="ctnGridProcessamentoFerias">

          </div>
        </fieldset>
          <!-- Formulario Plugin FeriaTCERO -->
        <input type="button" id="btnProcessar" value="Processar">
        <input type="button" id="btnCancelar" value="Cancelar">
      </form>
    </div>
  <?php db_menu();?>
  </body>
</html>
<script>

  var sUrlRPC = 'pes4_processarferias.RPC.php';
  var MENSAGEM_PROCESSAMENTO_FERIAS = 'recursoshumanos.pessoal.pes4_processarferias001.';

  (function(window) {

    /**
     * Instancia o componente CompetenciaFolha com os campos ano/mes desabilitados
     */
    var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    oCompetenciaFolha.renderizaFormulario($('containerCompetencia'));
    oCompetenciaFolha.desabilitarFormulario();


    var oGridProcessamentoFerias = new DBGrid("gridProcessamentoFerias");
    oGridProcessamentoFerias.nameInstance = "oGridProcessamentoFerias";
    oGridProcessamentoFerias.setCheckbox(4);
    oGridProcessamentoFerias.setHeight("300");
    oGridProcessamentoFerias.setHeader(["Matricula", "Servidor", "Tipo", "Período", 'codigo_periodo_gozo', 'tipo_processamento', 'Processada']);
    oGridProcessamentoFerias.setCellAlign(["center", "left", "left", "left", "center", "center", "center"]);
    oGridProcessamentoFerias.setCellWidth(["9%", "43%", "18%", "19%", "1%", "1%", "10%"]);
    oGridProcessamentoFerias.aHeaders[5].lDisplayed = false;
    oGridProcessamentoFerias.aHeaders[6].lDisplayed = false;
    oGridProcessamentoFerias.show($('ctnGridProcessamentoFerias'));
    window.oGridProcessamentoFerias = oGridProcessamentoFerias;


    /**
     * Realiza o processamento dos dados de férias selecionados
     * @returns {boolean}
     */
    function processar() {

      var aLinhas = oGridProcessamentoFerias.getSelection("object");
      if (aLinhas.length == 0) {

        alert(_M(MENSAGEM_PROCESSAMENTO_FERIAS+'nenhum_periodo_selecionado'));
        return false;
      }

      var oParametro = {
        exec: 'processarFerias',
        anofolha: $F('ano'),
        mesfolha: $F('mes'),
        ferias: [],
        /* Parametro Tipo de Folha Plugin FeriaTCERO */
      };

      aLinhas.each(function (oLinha) {

        var oFerias = {
          codigo: oLinha.aCells[5].getValue(),
          tipo  : oLinha.aCells[6].getValue() == 3 ? 1 : oLinha.aCells[6].getValue()
        };
        oParametro.ferias.push(oFerias);
      });

      new AjaxRequest(sUrlRPC, oParametro, function (oResponse, lErro) {

        alert(oResponse.sMessage.urlDecode());
        if (lErro) {

          return;
        }
        getFeriasDisponiveis();
      }).setMessage('Aguarde, processando dados de férias...').execute();

    }

    $('btnProcessar').observe("click", function() {
       processar();
    }) ;

    $('btnPesquisar').observe("click", function() {
        getFeriasDisponiveis();
    });

    $('btnCancelar').observe('click', function() {
      removerPagamentoTerco();
    });
  })(window);

  /**
   * Realiza a pesquisa das férias disponiveis para processamento
   */
  function getFeriasDisponiveis() {

    oGridProcessamentoFerias.clearAll(true);

    var oParametro = {exec       :'getFeriasDisponiveis',
      'datainicio' : $F('data_inicio_gozo'),
      'servidor'   : $F('rh01_regist'),
      'processada' : $F('opcoesProcessadas')
    };

    new AjaxRequest(sUrlRPC, oParametro, function (oResponse, lErro) {

      if (lErro) {

        alert(oResponse.message.urlDecode());
        return;
      }

      if (oResponse.ferias.length == 0) {

        alert(_M(MENSAGEM_PROCESSAMENTO_FERIAS+'sem_ferias_para_processamento'));
        return;
      }
      oResponse.ferias.each(function(oFerias) {

        var sObservacao = '';
        switch  (oFerias.tipo_processamento) {

          case 1:
            sObservacao = 'Pagamento 1/3 férias';
            break;

          case 2:
            sObservacao = 'Gozo';
            break;

          case 3:
            sObservacao = 'Abono Constitucional';
            break;

          case 4:
              sObservacao = 'Dias em Pecúnia';
             break
        }
        var aLinha = [
          oFerias.matricula,
          oFerias.servidor.urlDecode(),
          sObservacao,
          oFerias.periodo.urlDecode(),
          oFerias.codigo_periodo_gozo,
          oFerias.tipo_processamento,
          oFerias.processada.urlDecode()
        ];
        oGridProcessamentoFerias.addRow(aLinha);
      });
      oGridProcessamentoFerias.renderRows();

    }).setMessage('Aguarde, carregando Férias...').execute();

  }

  function js_pesquisar_matricula(mostra){

    if (mostra) {

      js_OpenJanelaIframe('',
        'db_iframe_rhpessoal',
        'func_rhpessoal.php?testarescisao=ra&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>',
        'Pesquisa Servidores',
         mostra
      );
    } else {
      var iMatricula = $F('rh01_regist');
      if (iMatricula != '') {
        js_OpenJanelaIframe('',
                            'db_iframe_rhpessoal',
                            'func_rhpessoal.php?testarescisao=ra&pesquisa_chave='+iMatricula+
                            '&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>',
                            'Pesquisa',
                            false
                           );
      }else{

        $('rh01_regist').value = '';
        $('z01_nome').value    = '';
      }
    }
  }

  function js_mostrapessoal(chave, erro) {

    $('z01_nome').value = chave;
    if (erro) {

      $('rh01_regist').focus();
      $('rh01_regist').value = '';
    }
  }


  function js_mostrapessoal1(chave1, chave2) {

    db_iframe_rhpessoal.hide();
    $('rh01_regist').value  = chave1;
    $('z01_nome').value     = chave2;
  }

  function removerPagamentoTerco() {

    var linhas = oGridProcessamentoFerias.getSelection("object");

    if(linhas.length == 0) {

      alert('Nenhum registro de férias selecionado.');
      return false;
    }

    var ferias = [];
    linhas.each(function(linha) {

      var dadosFerias = {
        'matricula' : linha.aCells[1].getValue(),
        'codigoPeriodoGozoFerias' : linha.aCells[5].getValue()
      };

      ferias.push(dadosFerias);
    });

    var parametros = {
      'exec' : 'removerPagamentoTerco',
      'ferias' : ferias
    };

    new AjaxRequest(sUrlRPC, parametros, function(retorno, erro) {

      alert(retorno.sMessage.urlDecode());

      if(!erro) {
        getFeriasDisponiveis();
      }
    }).setMessage('Aguarde... Retornando para situação de pagamento...')
      .execute();
  }
</script>
