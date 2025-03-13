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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

?>
    <!doctype html>
    <html lang="pt-BR">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>DBSeller Informática Ltda - Página Inicial</title>
        <link rel="stylesheet" href="estilos.css">
        <style>
            .container-duplica {
                padding-top: 2.5%;
                width: 700px;
                padding-right: 15px;
                padding-left: 15px;
                margin-right: auto;
                margin-left: auto;
            }
        </style>
        <script src="scripts/scripts.js"></script>
        <script src="scripts/prototype.js"></script>
        <script src="scripts/strings.js"></script>
        <script src="scripts/widgets/DBLookUp.widget.js"></script>
        <script rel="script" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script src="scripts/classes/http/http.js"></script>
    </head>
    <body class="container-duplica">
    <form id="form1" name="form1" method="post" onsubmit="return false;">
        <fieldset>
            <legend>Duplicar Relatório</legend>
            <table>
                <tr>
                    <td style="width: 20%">
                        <label for="o42_codparrel">
                            <a href="#" id="aconraRelatorio">Relatório:</a>
                        </label>
                    </td>
                    <td style="width: 15%">
                        <input name="o42_codparrel" id="o42_codparrel" class="field-size2 form-control"
                               style="width: 100%"/>
                    </td>
                    <td style="width: 65%">
                        <input name="o42_descrrel" id="o42_descrrel" class="field-size6 form-control" disabled
                               style="width: 100%"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="novoNome">
                            <strong>Nova Descrição:</strong>
                        </label>
                    </td>
                    <td colspan="2">
                        <input name="novoNome" id="novoNome" class="field-size8 form-control" style="width: 100%"/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="text-center">
            <input type="button" id="gerar" value="Gerar" style="margin-top: 1%"/>
        </div>
    </form>
    <script type="text/javascript">
        var lookUpRelatorio = new DBLookUp($('aconraRelatorio'), $('o42_codparrel'), $('o42_descrrel'), {
            'sArquivo': 'func_orcparamrel.php',
            'sLabel': 'Pesquisa de Relatórios Legais',
            'oObjetoLookUp': 'db_iframe_orcparamrel'
        });

        lookUpRelatorio.setCallBack('onClick', defineNome);
        lookUpRelatorio.setCallBack('onChange', defineNome);

        function defineNome() {
            $('novoNome').value = $F('o42_descrrel');
        }

        $('gerar').onclick = function() {
            if ($F('o42_codparrel') == '') {
                alert('Informe um relatório.');
                return;
            }

            const formData = new FormData();
            formData.append('acao', 'duplicarRelatorio');
            formData.append('relatorio', $F('o42_codparrel'));
            formData.append('nomeNovo', $F('novoNome'));

            HttpClient.post('con4_gerarrealtoriolegal.RPC.php', {body: formData}).then(response => {
                alert(response.mensagem);
            });
        };
    </script>
    </body>
    </html>
<?php db_menu();
