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

$aTiposOrigem = array(
  "numcgm" => "Numcgm",
  "matric" => "Matrícula",
  "inscr" => "Inscrição"
);

$aOrdenador = array(
  "id" => "Identificador",
  "nome" => "Nome"
);

$aOrdenacao = array(
  "asc" => "Ascendente",
  "desc" => "Descendente"
);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form>
      <fieldset>
        <legend>Relatório de Crédito</legend>
        <table>
          <tr>
            <td>
              <label for="tipo_origem" class="bold">Origem:</label>
            </td>
            <td>
              <?php db_select("tipo_origem", $aTiposOrigem, true, null); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="ordenacao" class="bold">Ordenação:</label>
            </td>
            <td>
              <?php
                db_select("ordenador", $aOrdenador, true, null);
                db_select("ordenacao", $aOrdenacao, true, null);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="button" id="emitir" value="Emitir" />

    </form>
  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">

    var sArquivoRelatorio = "cai2_relcredito006.php";
    var oInputTipoOrigem = $("tipo_origem");
    var oInputOrdenador = $("ordenador");
    var oInputOrdenacao = $("ordenacao");

    $("emitir").observe("click", function () {

      var sPathRelatorio = sArquivoRelatorio;
      sPathRelatorio += "?tipo_origem=" + oInputTipoOrigem.value;
      sPathRelatorio += "&ordenador="   + oInputOrdenador.value;
      sPathRelatorio += "&ordenacao="   + oInputOrdenacao.value;

      var oJanela = window.open(
        sPathRelatorio, '',
        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);
    });
  </script>
</body>
</html>