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
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>

</head>
<body class="body-default">
<div class="alert alert-primary text-left" role="alert">
    Serão listados apenas os programas que estão cadastrados para o exercício inicial do Plano
    (Contabilidade > Procedimentos > Exercício Contábil > Inclusão)
</div>
<div class="container">
    <div style="width: 1200px">
        <h2 style="text-align: left">Manutenção dos Programas Estratégicos do Planejamento</h2>
        <div style="text-align: left">
            <label class="bold" for="planejamento">Planejamento:</label>
            <select id="planejamento" class="field-size8">
                <option value="">Selecione um plano</option>
            </select>
        </div>

        <fieldset id="ctnTable" style="margin-top: 20px;">
            <legend>Programas Estratégicos</legend>

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
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>

<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
<link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
<script type="text/javascript">

    const routs = {
        filtrar : 'financeiro/planejamento/programas-estrategico/filtros',
        remover : 'financeiro/planejamento/programas-estrategico/remover'
    }

    const planejamento = new Planejamento(document.getElementById('planejamento'));

    $.noConflict();
    jQuery(document).ready(function ($) {

        const formatterActions = (value, row, index) => {
            return [
                '<a class="alterar" href="javascript:void(0)" title="Alterar">',
                '  <i class="fa fa-edit"></i>',
                '</a>',
                '&nbsp;&nbsp;',
                '<a class="excluir" href="javascript:void(0)" title="Excluir">',
                '  <i class="fas fa-trash-alt"></i>',
                '</a>'
            ].join('')
        };

        window.operateEvents = {
            'click .alterar': function (e, value, row, index) {
                let parametros = [
                    `codigo=${row.pl9_codigo}`,
                    `planejamento=${planejamento.getValue()}`
                ];
                location.href = `pla4_programaestrategicomanutencao.php?${parametros.join('&')}`;
            },
            'click .excluir': function (e, value, row, index) {
                let programa = `${row.programa} - ${row.descricao}`;
                alertify.confirm(`Tem certeza que deseja excluir o programa ${programa}`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl9_codigo', row.pl9_codigo);
                        PHPSession.appendFormData(formData);

                        const parametros = {
                            body: formData,
                            reportMessage: `Aguarde, removendo programa ${programa}.`
                        }

                        HttpClient.post(`${PHPSession.requestApi}/${routs.remover}`, parametros).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }
                            table.bootstrapTable('remove', {
                                field: 'programa',
                                values: [row.programa]
                            });
                        });
                    }
                });
            }
        }

        function buttons() {
            return {
                btnAdd: {
                    text: 'Adicionar Programa',
                    icon: 'fa-plus',
                    event: function () {
                        let codigo = planejamento.getValue()
                        if (codigo == '') {
                            alert("Selecione um planejamento.");
                            return;
                        }

                        location.href = `pla4_programaestrategicomanutencao.php?planejamento=${codigo}`;
                    },
                    attributes: {
                        title: 'Clique para adicionar um novo programa'
                    }
                }
            }
        }

        const colunas = [
            {
                title: 'Programa',
                field: 'programa',
                align: 'center',
                valign: 'middle',
                sortable: true
            }, {
                title: 'Descrição',
                field: 'descricao',
                align: 'left',
                valign: 'center',
                sortable: true
            }, {
                title: 'Ações',
                field: 'acoes',
                align: 'center',
                valign: 'center',
                events: window.operateEvents,
                formatter: formatterActions
            }
        ];

        var table = $('#data-table');
        table.bootstrapTable({
            columns: colunas,
            buttons: buttons,
            uniqueId: "programa",
            locale: 'pt-BR',
            cache: false,
            height: 500,
            pagination: true,
            pageSize: 10,
            pageList: [10, 25, 50, 100, 200, 'All'],
            search: true,
            showButtonText: true,
            class: "table table-sm"
        });

        PHPSession.loadData().then(() => {
            planejamento.load();
        });

        planejamento.getElement().addEventListener('change', () => {

            if (planejamento.getValue() == '') {
                table.bootstrapTable('load', []);
                return;
            }

            const formData = new FormData();
            PHPSession.appendFormData(formData);
            formData.append('planejamento', planejamento.getValue());

            const parametros = {
                body: formData,
                reportMessage: `Aguarde, buscando programas cadastrados no planejamento.`
            }

            HttpClient.post(`${PHPSession.requestApi}/${routs.filtrar}`, parametros).then(response => {
                table.bootstrapTable('load', response.data);
            });
        });
    });
</script>
