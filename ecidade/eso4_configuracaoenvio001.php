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
$oRotulo->label("h12_assent");
$oRotulo->label("h12_descr");
$oRotulo->label("eso10_sequencial");
$oRotulo->label("eso10_descricao");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">


</head>
<body class="body-default">
<div class="container">
    <form id="frmConfiguracaoDatasEnvio">
        <fieldset style="width: 600px;">
            <legend>Configuração de Datas para Envio</legend>
            <fieldset class="separator">
                <legend class="bold">S-2230 - Afastamento Temporário</legend>
                <table>
                    <tr>
                        <td class="bold">
                            <label for="dtAfastamentoTemporario">Data de Início:</label>
                        </td>
                        <td>
                            <input id="dtAfastamentoTemporario" type="text" />
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator">
                <legend class="bold">S-2299 / S-2399 - Desligamento</legend>
                <table>
                    <tr>
                        <td class="bold">
                            <label for="dtDesligamento">Data de Início:</label>
                        </td>
                        <td>
                            <input id="dtDesligamento" type="text" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </fieldset>
        <input type="button" value="Salvar" id="btnSalvar" onclick="salvar();"/>
    </form>
</div>
<?php db_menu();?>
</body>
</html>
<script>

  var input = {
    s2230 : {data_inicio : document.getElementById('dtAfastamentoTemporario')},
    s2229 : {data_inicio : document.getElementById('dtDesligamento')}
  };

  var dataAfastamentoTemporario = DBInputDate.create($('dtAfastamentoTemporario'));
  var dataDesligamento = DBInputDate.create($('dtDesligamento'));
  const rpc = "eso4_configuracaoenvio.RPC.php";

  function salvar() {

    var request = {
      "s2230" : {"data_inicio" : input.s2230.data_inicio.value},
      "s2229" : {"data_inicio" : input.s2229.data_inicio.value}
    };

    AjaxRequest.create(
      rpc,
      {"exec": "salvar", "data" : request},
      function (retorno, erro) {
        alert(retorno.mensagem);
      }
    ).execute();

  }

  function getConfiguracao() {

    AjaxRequest.create(
      rpc,
      {"exec" : "getConfiguracao"},
      function (retorno, erro) {

        if (erro) {

          alert(retorno.mensagem);
          return false;
        }

        input.s2230.data_inicio.value = js_formatar(retorno.arquivo.s2230.data_envio, 'd');
        input.s2229.data_inicio.value = js_formatar(retorno.arquivo.s2229.data_envio, 'd');
      }
    ).execute();
  }
  getConfiguracao();
</script>