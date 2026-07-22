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
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
    <div class="container">
        <form id="configuracaoDatasEnvioEfd">
            <fieldset>
                <legend>Configuração de Datas para Envio</legend>

                <fieldset class="separator" id="r2010">
                    <legend>R-2010 - Retenção Contribuição Previdenciária - Serviços Tomados</legend>
                    <table>
                        <tr>
                            <td class="bold">
                                <label for="dataInicioServicosTomados">Data de Início:</label>
                            </td>
                            <td>
                                <input type="text" id="dataInicioServicosTomados" style="width: 70px" />
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </fieldset>
            <input type="button" value="Salvar" id="btnSalvar" onclick="salvar();"/>
        </form>
    </div>
</body>
</html>
<?php db_menu();?>
<script>

    const RPC = "efd4_configuracaoenvio.RPC.php";
    var dataServicosTomados = DBInputDate.create($('dataInicioServicosTomados'));

    function salvar() {

        var eventos = {
            r2010 : {data : dataServicosTomados.getValue()}
        };

        AjaxRequest.create(
            RPC,
            {
                "execucao" : "salvar",
                "eventos"  : eventos
            },
            function (retorno, erro) {

                alert(retorno.mensagem);

                if(erro) {
                    return false;
                }
            }
        ).execute();
    }

    function buscarConfiguracoes() {

        AjaxRequest.create(
            RPC,
            {
                "execucao" : "buscar"
            },
            function (retorno, erro) {

                if(erro) {
                    alert(retorno.mensagem);
                    return false;
                }

                for(var evento of retorno.eventos){

                    var fieldSet = $(evento.efd06_arquivo);
                    if(!!fieldSet) {
                        var fieldData = fieldSet.querySelector('#dataInicioServicosTomados');
                        fieldData.setValue(js_formatar(evento.efd06_dataenvio, 'd'));
                    }
                }
            }
        ).execute();
    }

    buscarConfiguracoes();
</script>
