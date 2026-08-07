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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
    <head>
      <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="" quiv="Expires" CONTENT="0">
      <link href="estilos.css" rel="stylesheet" type="text/css">
      <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
      <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>

    </head>
    <body class="body-default">
        <div class="container">
            <form>
                    <table class="form-container">
                        <tr>
                            <td colspan="4">
                                <div id="lista-instituicoes">&nbsp;</div>
                            </td>
                        </tr>
                    </table>
                <input type="button" value="Salvar" id="salvar" name="salvar"  />
            </form>
        </div>
        <?php db_menu(); ?>
    </body>
</html>
<script>

    const RPC = 'con4_matrizsaldocontabil.RPC.php';

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', $('lista-instituicoes'));
    viewInstituicao.setLegenda("Configuração de Instituições");
    viewInstituicao.setMarcarSessao(false);
    viewInstituicao.show();

    function getInstituicoesConfiguradas() {
        var ajax = new AjaxRequest(RPC, {sExecucao: 'buscarInstituicoesConfiguradas'}, function (oRetorno, lErro) {

            if (lErro){
                alert('Ocorreu um erro ao buscar as instituições configuradas.');
            }

            var instituicoes = new Array();
            oRetorno.instituicoes.forEach(function (instituicao) {
                instituicoes.push(instituicao.codigo);
            });

            viewInstituicao.setInstituicoesSelecionadas(instituicoes);
        }).execute();
    }

    getInstituicoesConfiguradas();

    $('salvar').onclick = function()  {
        var instituicoesSelecionadas = viewInstituicao.getInstituicoesSelecionadas(true);

        if (instituicoesSelecionadas.length == 0){
            alert("Selecione ao menos uma instituição.");
            return;
        }
        var parametros = {sExecucao: 'salvarInstituicoesConfiguradas', instituicoes: instituicoesSelecionadas};

        var ajax = new AjaxRequest(RPC, parametros, function (oRetorno, lErro) {
            alert(oRetorno.sMessage);
        }).execute();
    };

</script>
