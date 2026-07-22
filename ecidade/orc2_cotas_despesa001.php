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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>
<body>
<div id='ctnAbas'></div>
<div id='ctnAbaEmissao' class='subcontainer'>
    <form name="formulario" id="formulario">
        <fieldset>
            <legend>Cotas Mensais da Despesa</legend>
            <fieldset class="separator">
                <legend>Filtros para impressão</legend>
                <table class="form-container">
                    <tr>
                        <td class="field-size3"><label for="periodicidade">Periodicidade:</label></td>
                        <td>
                            <select id="periodicidade" name="periodicidade">
                                <option value="mensal" selected>Mensal</option>
                                <option value="bimestral">Bimestral</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="agruparPor">Agrupar Por:</label></td>
                        <td>
                            <select id="agruparPor" name="agruparPor">
                                <option value="orgao">Orgão</option>
                                <option value="unidade">Unidade</option>
                                <option value="funcao">Função</option>
                                <option value="subfuncao">Subfunção</option>
                                <option value="programa">Programa</option>
                                <option value="iniciativa">Projeto/Atividade</option>
                                <option value="elemento">Elemento</option>
                                <option value="recurso">Recurso</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td id="ctnInstituicao" colspan="4" style="font-weight: normal" class="field-size-max">
                            <input type="hidden" id="db_selinstit" value="">
                        </td>
                    </tr>
                </table>
            </fieldset>
        </fieldset>
    </form>
</div>
<div id='ctnAbaFiltros' style="display: none">
    <?php
    $_GET['iCodigoRelatorio'] = 250;
    require_once 'con2_filtrosrelatorios.php';
    ?>
    <br>
    <br>
</div>


<div id="cntAbaNotasExplicativas" style="display: none">
    <iframe name="iframe_processapad" src="con2_conrelnotas.php?c83_codrel=78" width="100%" height="750px">
    </iframe>
</div>
<div class="subcontainer">
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script type="text/javascript">
    var viewInstituicao = {};

    $.noConflict();
    jQuery(document).ready(function (jQuery) {
        let ctnAbaFiltros = document.getElementById('ctnAbaFiltros');
        let cntAbaNotasExplicativas = document.getElementById('cntAbaNotasExplicativas');
        const dBAba = new DBAbas(document.getElementById('ctnAbas'));
        dBAba.adicionarAba("Relatório", document.getElementById('ctnAbaEmissao'));
        dBAba.adicionarAba("Filtros", ctnAbaFiltros);
        dBAba.adicionarAba("Notas Explicativas", cntAbaNotasExplicativas);

        rotaEmissao = 'financeiro/orcamento/relatorios/cotas-despesa';

        ctnAbaFiltros.style.display = 'block';
        cntAbaNotasExplicativas.style.display = 'block';

        viewInstituicao = new DBViewInstituicao('viewInstituicao', document.getElementById('ctnInstituicao'));
        viewInstituicao.show();

        const validar = () => {
            try {
                if (viewInstituicao.getInstituicoesSelecionadas(true).length === 0) {
                    throw 'Selecione ao menos uma instituição';
                }
            } catch (e) {
                alert(e)
                return false;
            }
            return true;
        }

        document.getElementById('emitir').addEventListener('click', () => {

            if (!validar()) {
                return;
            }

            const formData = new FormData(document.getElementById('formulario'));
            for (let codigo of viewInstituicao.getInstituicoesSelecionadas(true)) {
                formData.append('instituicoes[]', codigo);
            }
            formData.append('filtros', JSON.stringify(getFiltros()));
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${rotaEmissao}`, {body: formData}).then(response => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                const download = new DBDownload();
                download.addFile(response.data.pdf, "Cotas mensais da despesa - PDF");
                download.addFile(response.data.csv, "Cotas mensais da despesa - CSV");
                download.show();
            });
        });
    });

</script>
