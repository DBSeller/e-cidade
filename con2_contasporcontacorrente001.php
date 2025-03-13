<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
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

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body class="body-default">
<div class="container">
    <form id="frmContasPorContaCorrente">
        <fieldset style="width: 550px;">
            <legend>Relação de Conta Corrente</legend>
            <table>
                <tr>
                    <td><a href="#" id="ancoraContaCorrente">Conta Corrente:</a></td>
                    <td>
                        <?php
                        db_input('c122_sequencial', 8, 1, true, 'text', 1);
                        db_input('c122_descricao', 60, 1, true, 'text', 3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="bold">Estrutural:</td>
                    <td>
                        <?php
                        $Sestrutural = 'Estrutural';
                        db_input('estrutural', 20, 1, true, 'text', 1);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" value="Emitir" id="btnEmitir" onclick="emitir()"/>
    </form>
</div>
<?php db_menu(); ?>
</body>
</html>
<script>

  var filtros = {
    'codigoContaCorrente' : document.getElementById('c122_sequencial'),
    'descricaoContaCorrente' : document.getElementById('c122_descricao'),
    'estrutural' : document.getElementById('estrutural')
  };

  var lookupContaCorrente = new DBLookUp($('ancoraContaCorrente'), $('c122_sequencial'), $('c122_descricao'), {
    "sArquivo"      : "func_conplanosistema.php",
    "sObjetoLookUp" : "db_iframe_conplanosistema",
    "sLabel"        : "Pesquisar Conta Corrente",
    'aParametrosAdicionais' : ['tipo=2']
  });


  function emitir() {

    if (filtros.codigoContaCorrente.value.trim() === '' && filtros.estrutural.value.trim() === '') {

      if (!confirm('Deseja prosseguir com a emissão sem ter informado filtros?')) {
        return false;
      }
    }

    var parametros = 'codigoContaCorrente='+filtros.codigoContaCorrente.value;
    parametros += '&estrutural='+filtros.estrutural.value;

    window.open('con2_contasporcontacorrente002.php?'+parametros);

  }
</script>
