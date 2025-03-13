<?php

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
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">

    <div style="width: 1000px">

        <h2 style="text-align: left">Manutenção dos Planos de Governo</h2>

        <fieldset id="ctnTable" style="margin-top: 20px;">
            <legend>Planos de Governo</legend>
            <div style="clear: both"></div>

            <table id="data-table"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"

                   style="width: 100%;">
            </table>
        </fieldset>
    </div>
</div>

<?php db_menu() ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript">

    const status = 1; // Só pode buscar os Planos de Governo EM DESENVOLVIMENTO

    const getURLParameters = url =>
        (url.match(/([^?=&]+)(=([^&]*))/g) || []).reduce(
            (a, v) => ((a[v.slice(0, v.indexOf('='))] = v.slice(v.indexOf('=') + 1)), a),
            {}
        );

    const get = getURLParameters(window.location.search);

    const routs = {
        index: 'financeiro/planejamento/consulta/planos',
        remove: 'financeiro/planejamento/remove',
        criarVinculo: 'financeiro/planejamento/criarVinculo',
    }

    const rota = "financeiro/planejamento/consulta/planos";
    $.noConflict();
    jQuery(document).ready(function ($) {

        function buttons() {
            return {
                btnAdd: {
                    html:
                        '<div style="text-align: right; margin-right: 5px;">' +
                        '  <button class="adicionar"> Adicionar <i class="fa fa-plus"></i></button>' +
                        '</div>',
                }
            }
        }

        const operateFormatterActions = (value, row, index) => {
            return [
                '<a class="alterar" href="javascript:void(0)" title="Alterar">',
                '  <i class="fa fa-edit"></i>',
                '</a>',
                '&nbsp;&nbsp;',
                '<a class="excluir" href="javascript:void(0)" title="Excluir">',
                '  <i class="fas fa-trash-alt"></i>',
                '</a>'
            ].join('')
        }

        window.operateEvents = {
            'click .alterar': function (e, value, row, index) {
                location.href = `pla1_planejamento.php?tipo=${get.tipo}&codigo=${row.pl2_codigo}`;
            },
            'click .excluir': function (e, value, row, index) {

                let preposicao = get.tipo === 'PPA' ? 'o' : 'a';

                alertify.confirm(`Tem certeza que deseja excluir ${preposicao} ${get.tipo}`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl2_codigo', row.pl2_codigo);
                        PHPSession.appendFormData(formData);

                        HttpClient.post(`${PHPSession.requestApi}/${routs.remove}`, {body: formData}).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }
                            table.bootstrapTable('remove', {
                                field: 'pl2_codigo',
                                values: [row.pl2_codigo]
                            });
                        });
                    }
                });
            }
        }

        const table = jQuery('#data-table');
        table.bootstrapTable({
            buttons: buttons,
            locale: 'pt-BR',
            columns: [
                {
                    "title": "Período",
                    "field": 'periodo',
                    "align": 'center',
                    "valign": 'middle'
                },
                {
                    "title": "Plano",
                    "field": 'pl2_titulo',
                    "align": 'left',
                    "valign": 'middle'
                },
                {
                    "title": "Ações",
                    "field": 'acoes',
                    "align": 'center',
                    "valign": 'middle',
                    events: window.operateEvents,
                    formatter: operateFormatterActions
                }
            ]
        });

        PHPSession.loadData().then(() => {
            HttpClient.get(`${PHPSession.requestApi}/${routs.index}/${get.tipo}/${status}`).then(response => {
                let dados = response.data.map((plano) => {
                    plano.periodo = `${plano.pl2_ano_inicial} à ${plano.pl2_ano_final}`;
                    return plano;
                });

                table.bootstrapTable('load', dados);
            });
        });

        document.querySelector('.adicionar').addEventListener('click', () => {

            if (get.tipo !== 'PPA') {

                let tipoVincular = get.tipo === 'LDO' ? 'PPA' : 'LDO';
                const formData = new FormData();
                formData.append('tipo', get.tipo);
                formData.append('tipoVincular', tipoVincular);

                PHPSession.appendFormData(formData);

                const parametros = {
                    body: formData,
                    reportMessage: `Aguarde, buscando ${tipoVincular} aprovado para criar vínculo.`
                }

                HttpClient.post(`${PHPSession.requestApi}/${routs.criarVinculo}`, parametros).then(response => {

                    if (!response.error) {
                        table.bootstrapTable('load', response.data);
                        let codigo = response.data.pl2_codigo;
                        location.href = `pla1_planejamento.php?tipo=${get.tipo}&codigo=${codigo}`;
                    } else {
                        alert(response.message);
                    }
                });
            } else {
                location.href = `pla1_planejamento.php?tipo=${get.tipo}`;
            }
        });
    });

</script>
</body>
</html>
