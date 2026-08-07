<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2022  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("classes/empenho.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<!Doctype html>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>

    <!-- bootstrap table -->
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <style>
        .bad-row {
            color: #dc3545;
        }

        .warning-row {
            color: #ffc107;
        }

        .ok-row {
            color: #28a745;
        }
    </style>
</head>

<body>
    <form name='form1' action='javascript:;'>
        <div class="container">
            <fieldset style="width: 40%">
                <legend>Filtros</legend>

                <table class="form-container">
                    <!-- instit -->
                    <tr>
                        <td>
                            <input type="hidden" id="instit" value="<?= db_getsession('DB_instit') ?>">
                        </td>
                    </tr>

                    <!-- Evento -->
                    <tr>
                        <td>
                            <label for="evento">Evento Reinf: </label>
                        </td>
                        <td>
                            <select name="evento" id="evento">
                                <option value="r2010">R-2010</option>
                                <option value="r2055">R-2055</option>
                            </select>
                        </td>
                    </tr>

                    <!-- cgm -->
                    <tr>
                        <td title="Número do cgm">
                            <label for="ancoraCgm">
                                <a href="#" id="ancoraCgm">Cgm: </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="z01_numcgm" id="z01_numcgm">
                            <input type="text" name="z01_nome" id="z01_nome">
                        </td>
                    </tr>

                    <!-- orgao / unidade -->
                    <tr class="d-none" id="trOrgao">
                        <td>
                            <label for="o40_orgao">
                                <a href="#" id="ancoraOrgao">Órgão: </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" id="o40_orgao">
                            <input type="text" id="o40_descr">
                        </td>
                    </tr>
                    <tr class="d-none" id="trUnidade">
                        <td>
                            <label for="o41_unidade">
                                <a href="#" id="ancoraUnidade">Unidade: </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" id="o41_unidade">
                            <input type="text" id="o41_descr">
                        </td>
                    </tr>

                    <!--nota fiscal-->
                    <tr>
                        <td><label for="nota">Nota Fiscal: </label></td>
                        <td><input type="text" name="nota" id="nota"></td>
                    </tr>

                    <!-- periodo -->
                    <tr>
                        <td>
                            <span style="cursor: help;" title="Período da emissão da Nota Fiscal">
                                Período:
                            </span>
                        </td>
                        <td>
                            <?php db_inputdata("dataNotaInicial", null, null, null, true, "text", 1); ?> à
                            <?php db_inputdata("dataNotaFinal", null, null, null, true, "text", 1); ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <button name="pesquisar" id="pesquisar" onClick="js_getRetencoes()">Pesquisar</button>
        </div>
        <br>
    </form>
    <div style="width: 70%; display: none;" class="subcontainer" id="notasContainer">
        <fieldset>
            <legend>Notas</legend>
            <table id="gridNotas" class="table table-sm" data-height="250" data-virtual-scroll="true"
                style="width: 100%;">
            </table>
        </fieldset>
    </div>
    <script src="assets/jquery/jquery-3.5.1.min.js"></script>
    <script src="assets/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
    <script src="scripts/session.js"></script>
    <script>
        $.noConflict();

        /**
         * Sessoes
        */
        const instit = $F('instit');

        /*
        * EFDReinf - Configuracoes
        */
        let efdConfig = false;

        /**
         * RPC (controller) das retencoes
         * */
        const rpc = 'emp4_manutencaoretencoes.RPC.php';

        /**
         * Loockup do cgm
         * */
        const lookUpCgm = new DBLookUp($('ancoraCgm'), $('z01_numcgm'), $('z01_nome'), {
            'sArquivo': 'func_cgm.php',
            'sLabel': 'Pesquisar Cgm',
            'sObjetoLookUp': "db_iframe_cgm"
        });

        /**
         * Loockup do Orgao
         * */
        const lookUpOrgao = new DBLookUp($('ancoraOrgao'), $('o40_orgao'), $('o40_descr'), {
            'sArquivo': 'func_orcorgao.php',
            'sLabel': 'Pesquisar Órgão',
            'sObjetoLookUp': 'db_iframe_orgao',
            'aParametrosAdicionais': [`instit=${instit}`],
            'fCallBack' : () => {
                lookUpUnidade.desabilitar();

                $('o41_unidade').value = '';
                $('o41_descr').value   = '';

                if ($F('o40_orgao')) {
                    let param = [
                        'orgao=' + $F('o40_orgao'),
                        `instit=${instit}`
                    ];
                    lookUpUnidade.setParametrosAdicionais(param);
                    lookUpUnidade.habilitar();
                }
            }
        });

        /**
         * Loockup do Unidade
        */
        const lookUpUnidade = new DBLookUp($('ancoraUnidade'), $('o41_unidade'), $('o41_descr'), {
            'sArquivo': 'func_orcunidade.php',
            'sLabel': 'Pesquisar Unidade',
            'sObjetoLookUp': 'db_iframe_unidade',
        });
        lookUpUnidade.desabilitar();

        /**
         * Janela auxiliar para exibir os alertas das retencoes
         */
        const windowAuxRetencao = new windowAux('win', '', 500, 350);

        /**
         * Init grid
         */
        const gridNotas = jQuery('#gridNotas');

        /**
         * Container das notas
         */
        const notasContainer = document.querySelector('#notasContainer');

        /**
         * Entrypoint
         */
        PHPSession.loadData().then(async () => {
            await getEfdConfig()
            enabledFiltroOrgaounidade();
        });

        function enabledFiltroOrgaounidade() {
            if (efdConfig && efdConfig.efd07_filtraorgaounidade) {
                const trOrgao   = document.querySelector('#trOrgao');
                const trUnidade = document.querySelector('#trUnidade');

                trOrgao.classList.remove('d-none');
                trUnidade.classList.remove('d-none');
            }
        }

        /*
        * Request para obter as configuracoes do efd-reinf
        */
        async function getEfdConfig() {
            const url = PHPSession.requestApi;
            const api = url + '/integracoes/efd-reinf/configuracao/get';

            let response = false;
            const formData = new FormData();

            formData.append('get', true);
            PHPSession.appendFormData(formData);

            response = await HttpClient.post(api, { body: formData });
            if (response.error) {
                let msg = "Erro ao buscar as configurações do efd-reinf: \n" + response.message;
                alert(msg);
                return false;
            }

            if (response.data) {
                efdConfig = response.data;
            }
        }

        /**
         * Request para retornar as retencoes de
         * acordo com os filtros de pesquisa
         * */
        function js_getRetencoes() {
            if (!js_validateFilter()) {
                return false;
            }

            js_divCarregando("Aguarde, pesquisando retencões.", "msgBox");

            let params = {
                nota: $F('nota'),
                cgm: $F('z01_numcgm'),
                periodo: [$F('dataNotaInicial'), $F('dataNotaFinal')],
                evento: $F('evento')
            }

            // se tiver o filtro orgaounidade habilitado
            if ($F('o40_orgao') || $F('o41_unidade')) {
                params.orgaoUnidade = {};
                params.orgaoUnidade.orgao = $F('o40_orgao');
                params.orgaoUnidade.unidade = $F('o41_unidade');
            }

            let body = JSON.stringify({ exec: 'getRetencoes', filters: params });
            let options = {
                method: 'post',
                parameters: 'json=' + body,
                onComplete: js_retornoRetencoes
            }

            let request = new Ajax.Request(rpc, options);
        }

        /**
         * Retorno das retencoes e monta o grid
         * */
        function js_retornoRetencoes(response) {
            js_removeObj("msgBox");

            if (response.status == 200 && response.responseText) {
                retencoes = JSON.parse(response.responseText);

                if (retencoes.lErro == true) {
                    let msg = "Erro ao buscar as retenções: \n" + retencoes.sMessage;
                    alert(msg);
                    return;
                }

                // monta grid
                notasContainer.style.display = 'block';
                ($F('evento') == 'r2010') ? buildGridR2010(retencoes.data) : buildGridR2055(retencoes.data);
            } else {
                alert('Erro na Requisição.');
                return;
            }
        }

        /**
         * Adiciona status nas linhas inconsistentes
         * e estrura os campos
        * */
        function js_validateRows(item) {
            let className = '';
            let erros = ($F('evento') == 'r2010') ? validateR2010(item) : validateR2055(item);

            if (erros.length > 0) {
                className = 'bad-row'
            }

            return {
                class: className,
                erros: erros
            }
        }

        /**
         * Modal/Action da janela de alterar a retencao
         * */
        function js_manutencaoRentecao(ev, evento) {
            let retencao = ev.dataset.retencao;
            let action = evento == 'r2010' ? 'emp4_manutencaoRetencaoR2010.php' : 'emp4_manutencaoRetencaoR2055.php'

            sessionStorage.removeItem('retencao');
            sessionStorage.setItem('retencao', retencao);

            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_retencao',
                action,
                'Manutenção da Retenção', true);
        }

        /** Funcao para validar forumalario de filtros */
        function js_validateFilter() {
            if ($F('dataNotaInicial') == '' && $F('dataNotaFinal') == '') {
                alert('Você deve selecionar um período');
                return false;
            }

            if (efdConfig && efdConfig.efd07_filtraorgaounidade) {
                if (!$F('o40_orgao') && $F('o41_unidade')) {
                    alert('Você deve informar o Órgão');
                    return false;
                }
            }

            return true;
        }

        /** modal com os erros da retenção */
        function js_errosRetencao(ev) {
            let erros = JSON.parse(ev.dataset.erros);

            if (erros.length == 0) { alert('Sem detalhes para este item'); return; }

            windowAuxRetencao.setTitle('Detalhes');
            windowAuxRetencao.setContent(js_pageErrosRetencao(erros));
            windowAuxRetencao.show();
        }

        /** html da pag de erros */
        function js_pageErrosRetencao(list) {
            const container = document.createElement('div');
            const title = document.createElement('h3');
            const ul = document.createElement('ul');

            title.innerHTML = "Os seguintes campos estão vazios ou precisam de revisão";
            title.classList.add('text-center');

            list.forEach(i => {
                let li = document.createElement('li');
                li.innerHTML = i;
                ul.appendChild(li);
            });

            container.appendChild(title);
            container.appendChild(ul);

            return container;
        }

        /**
         * Validacaoes dos campos do evento R-2010
         *
         * @return array
         */
        function validateR2010(item) {
            const erros = [];
            const filters =  [
                {
                    name: 'Número da Nota Fiscal',
                    value: item.numero_nota,
                    validate: '^[0-9]+$'
                },
                {
                    name: 'Indicativo de serviço de Obra',
                    value: item.indicativo_obra_tipo,
                    validate: '^[0-2]$'
                },
                {
                    name: 'Tipo de serviço da nota fiscal',
                    value: item.referencia_tipo_servico,
                    validate: '^[0-9]+$'
                },
                {
                    name: 'Valores das retenções adicionais',
                    value: item.receitasadicionais_sequencial,
                    validate: '^[1-9][0-9]*$'
                },
                {
                    name: 'Número de série nota fiscal',
                    value: item.serie_nota,
                    validate: '^[A-Za-z0-9]{0,5}$'
                }
            ];

            // validacoes de regex
            filters.forEach(i => {
                let validate = new RegExp(i.validate);
                let value = i.value;

                if (typeof value == 'string') {
                    value = i.value.trim();
                }

                if (!validate.test(value)) {
                    erros.push(i.name);
                }
            });


            // validacoes personalizadas
            let retencao = Number(item.valor_retencao);
            let aliquota = (item.indicativo_cprb === true) ? 0.035 : 0.11;
            let base = (item.indicativo_valor_base === true)
                ? Number(item.valor_base_retido)
                : Number(item.valor_nota_liq) + Number(item.notas_nao_retidas)

            // o valor da retencao lancada nao pode ser maior que equacao de calculo
            // o efdreinf trunca as casas decimais e adiciona 1 centavo para a margem
            if (retencao > ((aliquota * base) + 0.01).toFixed(2)) {
                let percent = (100 * aliquota).toFixed(2);
                let msg = `
                    Retenção de ${js_formatar(retencao, 'f')}
                    maior que ${percent}%
                    de ${js_formatar(base, 'f')}
                `;

                erros.push(msg);
            }

            return erros;
        }

        /**
         * Validacaoes dos campos do evento R-2055
         *
         * @return array
         */
        function validateR2055(item) {
            const erros = [];
            const filters = [
                {
                    name: 'Valores da retenção (senar, gilrat, cp)',
                    value: item.e158_sequencial,
                    validate: '^[1-9][0-9]*$'
                },
                {
                    name: 'Indicativo de aquisição',
                    value: item.indAqProd,
                    validate: '^[1-7]$'
                }
            ];

            // validacoes de regex
            filters.forEach(i => {
                let validate = new RegExp(i.validate);
                let value = i.value;

                if (typeof value == 'string') {
                    value = i.value.trim();
                }

                if (!validate.test(value)) {
                    erros.push(i.name);
                }
            });

            return erros;
        }

        /**
         * Grid evento R-2010
         */
        function buildGridR2010(retencoes) {
            let data = [];

            // header grid
            const columns = [
                { field: "status", title: "Status", align: "center" },
                { field: "statusCode", title: "Status", visible: false },
                { field: "nf", title: "NF", align: "center" },
                { field: "nfdata", title: "Data da NF", align: "center" },
                { field: "prestador", title: "Prestador de Serviço", align: "center" },
                { field: "nfservico", title: "Tipo de Serviço da NF", align: "center" },
                { field: "nfvalor", title: "Valor da NF", align: "center" },
                { field: "nfvalorbase", title: "Valor base", align: "center"},
                { field: "nfvalorbruto", title: "Valor Total", align: "center"},
                { field: "aliquota", title: "Alíquota", align: "center" },
                { field: "retencao", title: "Valor Retido", align: "center" },
                {
                    field: "orgunid",
                    title: "Órgão Unidade",
                    align: "center",
                    visible: $F('o40_orgao') || $F('o41_unidade') ? true : false
                },
                { field: "acao", title: "Ação", align: "center" }
            ];

            // body grid
            retencoes.forEach(item => {
                let rowValidate = js_validateRows(item);
                let row = {};

                if (rowValidate.erros.length > 0) {
                    row.statusCode = 0;
                    row.status = `
                <a href="#" onclick='js_errosRetencao(this)'
                    data-erros='${JSON.stringify(rowValidate.erros)}'
                    class='${rowValidate.class}' title="Clique para visualizar">
                        <i class="fa fa-exclamation-triangle"></i>
                </a>`;
                } else {
                    row.statusCode = 1;
                    row.status = "<span class='ok-row' title='Item de acordo para o processamento do R-2010'><i class='fa fa-check-circle'></i>";
                }

                row.nf = item.numero_nota;
                row.nfdata = js_formatar(item.data_emissao, 'd');
                row.prestador = item.nome_prestador;
                row.nfservico = item.referencia_tipo_servico_desc === null ? '-' : item.referencia_tipo_servico_desc;
                row.nfvalor = js_formatar(item.valor_nota_liq, 'f');
                row.nfvalorbase = js_formatar(item.valor_base_retido, 'f');
                row.nfvalorbruto = js_formatar((Number(item.valor_nota_liq) + Number(item.notas_nao_retidas)).toFixed(2), 'f');
                row.aliquota = item.aliquota + '%';
                row.retencao = js_formatar(item.valor_retencao, 'f');
                row.orgunid = item.orgao_unidade;
                row.acao = `<button onclick="js_manutencaoRentecao(this, 'r2010')" data-retencao='${JSON.stringify(item)}'>Alterar</button>`;

                data.push(row);
            });

            gridNotas.bootstrapTable('destroy');
            gridNotas.bootstrapTable({
                data: data,
                locale: 'pt-BR',
                search: true,
                searchHighlight: true,
                columns: columns,
                buttons: buttons,
                showButtonText: true
            });
        }

        /**
         * Grid evento R-2055
         */
        function buildGridR2055(retencoes) {
            let data = [];

            // header grid
            const columns = [
                { field: "status", title: "Status", align: "center" },
                { field: "statusCode", title: "Status", visible: false },
                { field: "nfdata", title: "Data da NF", align: "center" },
                { field: "nfnumero", title: "Número da NF", align: "center" },
                { field: "prestador", title: "Prestador", align: "center" },
                { field: "indaquis", title: "Ind. de Aquisição", align: "center" },
                { field: "vlrbruto", title: "Valor Bruto", align: "center" },
                { field: "vlrsenar", title: "Valor Senar", align: "center" },
                { field: "vlrrat", title: "Valor Gilrat", align: "center" },
                { field: "vlrcp", title: "Valor CP", align: "center"},
                {
                    field: "orgunid",
                    title: "Órgão Unidade",
                    align: "center",
                    visible: $F('o40_orgao') || $F('o41_unidade') ? true : false
                },
                { field: "acao", title: "Ação", align: "center" }
            ];

            // body grid
            retencoes.forEach(item => {
                let rowValidate = js_validateRows(item);
                let row = {};

                if (rowValidate.erros.length > 0) {
                    row.statusCode = 0;
                    row.status = `
                <a href="#" onclick='js_errosRetencao(this)'
                    data-erros='${JSON.stringify(rowValidate.erros)}'
                    class='${rowValidate.class}' title="Clique para visualizar">
                        <i class="fa fa-exclamation-triangle"></i>
                </a>`;
                } else {
                    row.statusCode = 1;
                    row.status = "<span class='ok-row' title='Item de acordo para o processamento do R-2055'><i class='fa fa-check-circle'></i>";
                }

                row.nfdata = js_formatar(item.data_nota, 'd');
                row.nfnumero = item.nfnumero;
                row.prestador = item.prestador;
                row.vlrbruto = item.vlrBruto ? js_formatar(item.vlrBruto, 'f') : '-';
                row.vlrsenar = item.e158_vlrsenar ? js_formatar(item.e158_vlrsenar, 'f') : '-';
                row.vlrrat = item.e158_vlrrat ? js_formatar(item.e158_vlrrat, 'f') : '-';
                row.vlrcp = item.e158_vlrcp ? js_formatar(item.e158_vlrcp, 'f') : '-';
                row.indaquis = item.indAqProd === null ? '-' : String(item.indAqProd);
                row.acao = `<button onclick="js_manutencaoRentecao(this, 'r2055')" data-retencao='${JSON.stringify(item)}'>Alterar</button>`;

                data.push(row);
            });

            gridNotas.bootstrapTable('destroy');
            gridNotas.bootstrapTable({
                data: data,
                locale: 'pt-BR',
                search: true,
                searchHighlight: true,
                columns: columns,
                buttons: buttons,
                showButtonText: true
            });
        }

        function buttons() {
            return {
                btnFilterError: {
                    text: 'Inconsistentes',
                    icon: 'fa-exclamation-triangle',
                    event(e) {
                        console.log(this, e);
                        gridNotas.bootstrapTable('filterBy', {statusCode: 0});
                    }
                },
                btnFilterSuccess: {
                    text: 'Corretas',
                    icon: 'fa-check-circle',
                    event() {
                        gridNotas.bootstrapTable('filterBy', {statusCode: 1});
                    }
                },
                btnRefresh: {
                    text: 'Todas',
                    icon: 'fa-th-list',
                    event() {
                        gridNotas.bootstrapTable('refreshOptions', {});
                    }
                }
            }
        }
    </script>
</body>

</html>
