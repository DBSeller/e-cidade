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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();

$iPeriodoFinal = db_getsession('DB_datausu');
$iDiaPeriodoFinal = date('d', $iPeriodoFinal);
$iMesPeriodoFinal = date('m', $iPeriodoFinal);
$iAnoPeriodoFinal = date('Y', $iPeriodoFinal);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

    <div class="container">
      <fieldset>
        <legend>Controle de estoque</legend>
        <table class="form-container">

          <tr>
            <td><strong>Período:</strong></td>
            <td>
              <?php db_inputdata('periodoInicial', null, null, null, true, 'text', 1); ?>
              &nbsp;&nbsp;
              <?php db_inputdata('periodoFinal', $iDiaPeriodoFinal, $iMesPeriodoFinal, $iAnoPeriodoFinal, true, 'text', 1); ?>
            </td>
          </tr>

          <tr hidden>
            <td><strong>Quebra por Depósito:</strong></td>
            <td>
              <select id="quebraPorDeposito">
                <option value="1">Sim</option>
                <option value="0">Não</option>
              </select>
            </td>
          </tr>

          <tr>
            <td><strong>Ordem:</strong></td>
            <td>
              <select id="ordem">
                <option value="deposito">Depósito</option>
                <option value="alfabetica">Alfabética</option>
                <option value="codigo">Código</option>
              </select>
            </td>
          </tr>

          <tr>
            <td><strong>Somente itens com movimento:</strong></td>
            <td>
              <select id="somenteItensComMovimento">
                <option value="1">Sim</option>
                <option value="0" selected>Não</option>
              </select>
            </td>
          </tr>

          <tr>
            <td><strong>Tipo de Impressão:</strong></td>
            <td>
              <select id="tipoImpressao">
                <option value="sintetico">Sintético</option>
                <option value="conferencia">Conferência</option>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              <div id="ctnAlmoxarifado"></div>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              <div id="ctnMaterial"></div>
            </td>
          </tr>

        </table>
      </fieldset>

      <input type="button" onClick="js_imprimir();" value="Imprimir" />

    </div>

    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
  </body>
</html>
<script type="text/javascript">

require_once('scripts/widgets/DBLancador.widget.js');
require_once('scripts/widgets/DBToogle.widget.js');

var oLancadorDeposito = new DBLancador('LancadorAlmoxaridado');
oLancadorDeposito.setLabelAncora("Depósito");
oLancadorDeposito.setTextoFieldset("Depósitos");
oLancadorDeposito.setTituloJanela("Pesquisar Depósito");
oLancadorDeposito.setNomeInstancia("oLancadorDeposito");
oLancadorDeposito.setParametrosPesquisa("func_db_almox.php", ["m91_codigo", "descrdepto"], "sDescricaoDepartamento=false");
oLancadorDeposito.setGridHeight(150);
oLancadorDeposito.show($("ctnAlmoxarifado"));

var oLancadorMaterial = new DBLancador('LancadorMaterias');
oLancadorMaterial.setLabelAncora("Material");
oLancadorMaterial.setTextoFieldset("Materiais");
oLancadorMaterial.setTituloJanela("Pesquisar Material");
oLancadorMaterial.setNomeInstancia("oLancadorMaterial");
oLancadorMaterial.setParametrosPesquisa("func_matestoque.php", ["m60_codmater", "m60_descr"], 'servico=false&material');
oLancadorMaterial.setGridHeight(150);
oLancadorMaterial.show($("ctnMaterial"));

$("ctnAlmoxarifado").getElementsByTagName('fieldset')[0].id = 'fieldsetAlmoxarifado';
$("ctnMaterial").getElementsByTagName('fieldset')[0].id = 'fieldsetMaterial';

new DBToogle('fieldsetAlmoxarifado', false);
new DBToogle('fieldsetMaterial', false);

function js_validarFormulario() {

  var sPeriodoInicial = $('periodoInicial').value;
  var sPeriodoFinal = $('periodoFinal').value;

  if (!empty(sPeriodoInicial) && !empty(sPeriodoFinal)) {

    var mPeriodoInicialInvalido = js_diferenca_datas(js_formatar(sPeriodoInicial, 'd'), js_formatar(sPeriodoFinal, 'd'), 3);

    if (mPeriodoInicialInvalido && mPeriodoInicialInvalido != 'i') {

      alert('Período inicial maior que final.');
      return false;
    }
  }

  return true
}

function js_imprimir() {

  if (!js_validarFormulario()) {
    return false;
  }

  var sParametros    = '';
  var sDepositos = '';
  var sMateriais     = '';

  oLancadorDeposito.getRegistros().each(function(oDadosAlmoxarifado, iIndice) {

    if (iIndice > 0) {
      sDepositos += ',';
    }
    sDepositos += oDadosAlmoxarifado.sCodigo;
  });

  oLancadorMaterial.getRegistros().each(function(oDadosMaterial, iIndice) {

    if (iIndice > 0) {
      sMateriais += ',';
    }
    sMateriais += oDadosMaterial.sCodigo;
  });

  sParametros += 'periodoInicial=' + $('periodoInicial').value;
  sParametros += '&periodoFinal=' + $('periodoFinal').value;
  sParametros += '&quebraPorDeposito=' + $('quebraPorDeposito').value;
  sParametros += '&ordem=' + $('ordem').value;
  sParametros += '&somenteItensComMovimento=' + $('somenteItensComMovimento').value;
  sParametros += '&tipoImpressao=' + $('tipoImpressao').value;
  sParametros += '&sDepositos=' + sDepositos;
  sParametros += '&sMateriais=' + sMateriais;

  var janela = window.open('mat2_controleestoque002.php?' + sParametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  janela.moveTo(0,0);
}
</script>
