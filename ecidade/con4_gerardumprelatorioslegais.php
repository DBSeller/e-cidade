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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta_plugin.php";
require_once "libs/db_sessoes.php";
require_once "dbforms/db_funcoes.php";

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Gerar Dump Relatório Legal</legend>

            <table class="informacoes-relatorio" data-type="atualiza">
                <tr>
                    <td>
                        <label class="bold" for="codigo_relatorio">
                            <?php db_ancora('Código do Relatório:', 'pesquisarRelatorio()', 1); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        db_input('codigo_relatorio', 6, 1, true, 'text', 3);
                        db_input('descricao_relatorio', 30, 1, true, 'text', 3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="bold" for="codigo_relatorio">Código Destino:</label>
                    </td>
                    <td>
                        <?php db_input('codigo_relatorio_destino', 6, 1, true, 'text', 1); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="gerar" id="gerar" type="button" value="Gerar"/>
        <input name="json" id="json" type="button" value="JSON"/>
    </form>
</div>

<?php db_menu(); ?>
</body>
<script type="text/javascript">
    (function(exports) {
        const RPC = 'con4_importacaorelatorioslegais.RPC.php';

        var jsonButtonElement = document.getElementById('json');
        jsonButtonElement.addEventListener('click', function() {
            var codigoRelatorioElement = document.getElementById('codigo_relatorio');
            var codigoRelatorio = codigoRelatorioElement.value;
            var formData = new FormData();
            var request = new XMLHttpRequest();
            var parametros = {
                'exec': 'gerarJson',
                'codigo_relatorio': codigoRelatorio
            };

            formData.append('json', JSON.stringify(parametros));

            request.open('POST', RPC);
            request.onload = function() {
                var response = JSON.parse(request.response);

                if (response.erro) {
                    alert(response.message.urlDecode());
                    return false;
                }

                oDBDownload = new DBDownload();
                oDBDownload.addFile(response.caminhoArquivo, 'JSON Relatório');
                oDBDownload.show();
            };
            request.send(formData);
        });

        function pesquisarRelatorio() {
            js_OpenJanelaIframe(
                '',
                'db_iframe_orcparamrel',
                'func_orcparamrel.php?funcao_js=parent.preencheRelatorio|o42_codparrel|o42_descrrel',
                'Pesquisa de Relatório',
                true
            );
        }

        function preencheRelatorio(iCodigo, sDescricao) {

            db_iframe_orcparamrel.hide();

            $('codigo_relatorio').value = iCodigo;
            $('descricao_relatorio').value = sDescricao;
        }

        $('gerar').observe('click', function() {

            var oParametros = {
                exec: 'gerarDump',
                iCodigoRelatorioOrigem: $('codigo_relatorio').value,
                iCodigoRelatorioDestino: $('codigo_relatorio_destino').value
            };

            new AjaxRequest(RPC, oParametros, function(oResponse, lErro) {

                if (lErro) {
                    return alert(oResponse.message.urlDecode());
                }

                oDBDownload = new DBDownload();
                oDBDownload.addFile(oResponse.sArquivoDump, 'Dump Relatório');
                oDBDownload.show();

            }).setMessage('Gerando dump.').execute();
        });

        $('codigo_relatorio').value = '';
        $('descricao_relatorio').value = '';
        $('codigo_relatorio_destino').value = '';

        exports.pesquisarRelatorio = pesquisarRelatorio;
        exports.preencheRelatorio = preencheRelatorio;
    })(this);
</script>
</html>
