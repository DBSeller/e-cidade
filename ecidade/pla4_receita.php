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
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
    <div style="width: 1200px">
        <h2 style="text-align: left">Manutenção das fontes de receitas.</h2>
        <table>
            <tr>
                <td style="text-align: left"><label class="bold" for="planejamento">Planejamento:&nbsp;</label></td>
                <td>
                    <select id="planejamento" class="field-size8">
                        <option value="">Selecione um plano</option>
                    </select>
                </td>
            </tr>
        </table>
        <fieldset id="ctnTable" style="margin-top: 20px;">
            <legend>Natureza das receitas cadastradas</legend>
            <table id="data-table"
                   class="table table-sm"
                   data-height="250"
                   data-detail-view="true"
                   data-virtual-scroll="true"
                   style="width: 100%;">
            </table>
        </fieldset>
    </div>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script type="text/javascript" src="scripts/classes/bootstrapTable/detailFormaterTable.js"></script>

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
    $.noConflict();
    jQuery(document).ready(function ($) {

        const routs = {
            buscar : 'financeiro/planejamento/receita/previsao/buscar',
            excluir : 'financeiro/planejamento/receita/previsao/remover',
        };

        const planejamento = new Planejamento(document.getElementById('planejamento'));
        var table = $('#data-table');

        PHPSession.loadData().then(() => {
            planejamento.load();
        });

        planejamento.getElement().addEventListener('change', () => {
            table.bootstrapTable('load', []);
            if (planejamento.getValue() === '') {
                return;
            }

            buscarReceitas();
        });

        const buscarReceitas = () => {
            const formData = new FormData();
            formData.append('planejamento', planejamento.getValue());
            formData.append('inclusaomanual', true);
            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, buscando as receitas cadastradas.`
            };

            HttpClient.post(`${PHPSession.requestApi}/${routs.buscar}`, parametros).then(response => {
                response.data.each(natureza => {
                    natureza.natureza = natureza.natureza_receita.estruturalMascara,
                    natureza.descricao = natureza.natureza_receita.o57_descr
                });

                table.bootstrapTable('load', response.data);
            });
        }

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

        function buttons() {
            return {
                btnAdd: {
                    text: 'Adicionar Receita',
                    icon: 'fa-plus',
                    event: function () {
                        let codigo = planejamento.getValue();
                        if (codigo == '') {
                            alert("Selecione o planejamento.");
                            return;
                        }
                        let ano = planejamento.getPlano().pl2_ano_inicial;

                        location.href = `pl4_receita_manutencao.php?planejamento=${codigo}&exercicio=${ano}`;
                    },
                    attributes: {
                        title: 'Clique para adicionar uma nova receita'
                    }
                }
            }
        };

        window.operateEvents = {
            'click .alterar': function (e, value, row, index) {
                let ano = planejamento.getPlano().pl2_ano_inicial;
                location.href = `pl4_receita_manutencao.php?codigo=${row.id}&exercicio=${ano}`;
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir a estimativa da receita ${row.natureza}`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('id', row.id);
                        PHPSession.appendFormData(formData);

                        const parametros = {
                            body: formData,
                            reportMessage: `Aguarde, removendo detalhamento.`
                        }

                        HttpClient.post(`${PHPSession.requestApi}/${routs.excluir}`, parametros).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }
                            table.bootstrapTable('remove', {
                                field: 'id',
                                values: [row.id]
                            });
                        });
                    }
                });
            }
        }

        const colunas = [
            {
                title: 'Natureza',
                field: 'natureza',
                align: 'left',
                valign: 'middle',

                sortable: true
            }, {
                title: 'Descrição',
                field: 'descricao',
                align: 'left',
                valign: 'center',
                sortable: true
            }, {
                title: 'CP',
                field: 'concarpeculiar_id',
                align: 'center',
                valign: 'center',
                width: 30,
                sortable: true
            }, {
                title: 'Ações',
                field: 'acoes',
                align: 'center',
                valign: 'center',
                width: '100',
                events: window.operateEvents,
                formatter: formatterActions
            }
        ];

        const detailFormatter = (index, row) => {
            let dados = formataDadosAnalitico(row);
            return detailFormaterTable.createDetail(dados, 'Natureza da Receita Analítica');
        };

        table.bootstrapTable({
            columns: colunas,
            buttons: buttons,
            detailFormatter: detailFormatter,
            uniqueId: "natureza",
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

        const formataDadosAnalitico = (dadosLinha) => {
            let fonteRecurso = dadosLinha.recurso.fonteRecurso;
            return [
                [
                    {
                        label: "Natureza:",
                        valor: `${dadosLinha.natureza} - ${dadosLinha.descricao}`
                    },
                    {
                        label: "Instituição:",
                        valor: dadosLinha.instituicao.nomeinst
                    }
                ],
                [
                    {
                        label: "Órgão:",
                        valor: `${dadosLinha.orgao.formatado} - ${dadosLinha.orgao.o40_descr}`
                    },
                    {
                        label: "Unidade:",
                        valor: `${dadosLinha.unidade.formatado} - ${dadosLinha.unidade.o41_descr}`
                    }
                ],
                [
                    {
                        label: "Fonte de Recurso :",
                        valor: `${fonteRecurso.gestao} - ${fonteRecurso.descricao}`
                    },
                    {
                        label: "Complemento da Fonte:",
                        valor: `${dadosLinha.recurso.complemento.descricao}`
                    }
                ],
                [
                    {
                        label: "CP :",
                        valor: `${dadosLinha.concarpeculiar_id} - ${dadosLinha.caracteristica_peculiar.c58_descr}`
                    },
                    {
                        label: "Esfera Orçamentária:",
                        valor: `${dadosLinha.descricao_esfera}`
                    }
                ]
            ];
        };
    });
</script>
