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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");
require_once("libs/db_utils.php");

$iAnoUsu            = db_getsession("DB_anousu");
$oGet               = db_utils::postMemory($_GET);
$sProgramaRelatorio = $oGet->sProgramaRelatorio;
$sProgramaRelatorio = "con2_{$sProgramaRelatorio}_2014.php";
$codigoRelatorio    = $oGet->codigoRelatorio;

$oRelatorio = new relatorioContabil($codigoRelatorio);
$clrotulo   = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$sTitulo    = $oRelatorio->getDescricao();

$aPeriodos         = $oRelatorio->getPeriodos();
$aListaPeriodos    = array();
$aListaPeriodos[0] = "Selecione";
foreach ($aPeriodos as $oPeriodo) {
  $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}

/**
 * Verifica se instituicao atual é prefeitura
 */
$iInstituicao = db_getsession('DB_instit');
$oInstituicao = new Instituicao($iInstituicao);
$isPrefeitura = $oInstituicao->isPrefeitura() === 't';

/**
 * - verifica se deve exibir filtro "Imprimir valores do exercicio anterior:"
 * - caso ano anterior nao tinha PCASP valor padrao é não
 * - não exibe o filtro para os relatorios do balanço orçamentario
 */
$iAnoInicioPCASP = ParametroPCASP::getAnoInicioPCASP();
$aCodigosBalancoOrcamentario = array(130, 137, 138);
$imprimirValorExercicioAnterior =  $iAnoUsu - 1 >= $iAnoInicioPCASP;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;">
<center>

  <table style="width: 400px;">
    <tr>
      <td class='table_header'>
        <?php echo $sTitulo; ?>
      </td>
    </tr>
  </table>

  <fieldset style="width: 400px;">

    <legend><strong>Filtros:</strong></legend>

    <div id="ctnGridInstituicao"></div>

    <p style="padding-left: 20px; text-align: left;">
      <b>Períodos:</b>
      <?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?>
    </p>

    <span id="spanValoresExercicio">
      <p style="padding-left: 20px; text-align: left;">
        <b>Imprimir valores do exercicio anterior:</b>
        <?php db_select('imprimirValorExercicioAnterior', array(true => 'Sim', false => 'Não'), true, 1); ?>
      </p>
    </span>

  </fieldset>

  <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
</center>
</body>
</html>
<script>

  var oSpanValoresExercicio = $('spanValoresExercicio');
  oSpanValoresExercicio.style.display = 'none';

  var sProgramaRelatorio    = '<?php echo $sProgramaRelatorio; ?>';
  var iAnoUsu               = '<?php echo $iAnoUsu; ?>';
  var iCodigoRelatorio      = '<?php echo $codigoRelatorio; ?>';
  var isPrefeitura          = <?php echo $isPrefeitura ? 'true' : 'false'; ?>;
  var iInstituicao          = <?php echo $iInstituicao; ?>;
  var lPcaspNoAnoAnterior   = <?php echo $imprimirValorExercicioAnterior ? 'true' : 'false'; ?>;
  var aRelatoriosNaoPermitidos = [130, 137, 138];
  if ( ! js_search_in_array(aRelatoriosNaoPermitidos, iCodigoRelatorio)) {

    oSpanValoresExercicio.style.display = '';
    $('imprimirValorExercicioAnterior').value = lPcaspNoAnoAnterior;
  }


  /**
   * Instituicao logada é prefeitura
   * - exibe componente com todas as instituições
   */
  if (isPrefeitura) {

    var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
    oViewInstituicao.setWidth(400);
    oViewInstituicao.setHeight(130);
    oViewInstituicao.show();
  }

  /**
   * Emite o relatório
   * @returns {boolean}
   */
  function js_emite() {

    if (empty(sProgramaRelatorio)) {
      return alert('Relatório não disponível para o exercício ' + iAnoUsu);
    }

    var iCodigoPeriodo = $F('o116_periodo');
    var lConsolidado = false;
    var sInstituicao = iInstituicao;

    if (iCodigoPeriodo == "0") {
      return alert("Selecione um periodo");
    }

    /**
     * Busca as instituicoes selecionadas
     * - caso exista o componente com as instituicoes, exibido somente na prefeitura
     */
    if (typeof(oViewInstituicao) != 'undefined') {

      var aInstituicoesSelecionadas = oViewInstituicao.getInstituicoesSelecionadas(true);

      if (aInstituicoesSelecionadas.length == 0) {
        return alert("Nenhuma instituição selecionada."); return false;
      }

      if (oViewInstituicao.getTotalInstituicoes() == aInstituicoesSelecionadas.length) {
        lConsolidado = true;
      }

      var sInstituicao = aInstituicoesSelecionadas.implode("-");
    }

    var query  = "?db_selinstit=" + sInstituicao;
    query += "&periodo=" + iCodigoPeriodo;
    query += "&consolidado=" + (lConsolidado ? 'true' : 'false');
    query += "&codrel=" + iCodigoRelatorio;
    query += "&imprimirValorExercicioAnterior=" + ($('imprimirValorExercicioAnterior').value == 1 ? 'true' : 'false');

    jan = window.open(sProgramaRelatorio + query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
</script>
