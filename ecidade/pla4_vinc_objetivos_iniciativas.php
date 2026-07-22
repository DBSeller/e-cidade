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
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
    <div style="width: 1200px">
        <h2 style="text-align: left">Vincula os Objetivos dos Programas Estratégicos com as Iniciativas selecionadas.</h2>
        <table >
            <tr>
                <td style="text-align: left"><label class="bold" for="planejamento">Planejamento:&nbsp;</label></td>
                <td>
                    <select id="planejamento" class="field-size8">
                        <option value="">Selecione um plano</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: left"><label class="bold" for="programa">Programas:&nbsp;</label>
                </td>
                <td>
                    <select id="programa" class="field-size8">
                        <option value="">Selecione um programa estratégico</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: left">
                    <label class="bold" for="objetivo">Objetivos:&nbsp;</label></td>
                <td>
                    <select id="objetivo" class="field-size8">
                        <option value="">Selecione um objetivo</option>
                    </select>
                </td>
            </tr>
        </table>
        <fieldset id="ctnTable" style="margin-top: 20px;">
            <legend>Lista das Iniciativas</legend>
            <table id="data-table"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"
                   style="width: 100%;">
            </table>
        </fieldset>
        <button type="button" id="btnSalvar">
            <i class="far fa-save"></i>
            Salvar
        </button>
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
        programas: 'financeiro/planejamento/programas-estrategico/filtros',
        objetivos: 'financeiro/planejamento/objetivo-programa/filtros',
        iniciativas: 'financeiro/planejamento/iniciativa-vincular-objetivo/buscar',
        vincular: 'financeiro/planejamento/iniciativa-vincular-objetivo/vincular'
    };

    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const cboPrograma = document.getElementById('programa');
    const cboObjetivo = document.getElementById('objetivo');
    const btnSalvar = document.getElementById('btnSalvar');

    /**
     * Criado esse array de controle para controlar os selecionados pois a grid não mantem quando usamos a pesquisa.
     * @type {*[]}
     */
    const iniciativasSelecionadas = [];
    $.noConflict();
    jQuery(document).ready(function ($) {

        PHPSession.loadData().then(() => {
            planejamento.load();
        });

        const adicionaIniciativa = (programa) => {
            let index = iniciativasSelecionadas.findIndex(obj => obj.pl12_codigo == programa.pl12_codigo);
            if (index < 0) {
                iniciativasSelecionadas.push(programa);
            }
        };

        const removeIniciativa = (programa) => {
            let index = iniciativasSelecionadas.findIndex(obj => obj.pl12_codigo == programa.pl12_codigo);
            if (index >= 0) {
                iniciativasSelecionadas.splice(index, 1)
            }
        };

        const colunas = [
            {
                field: 'check',
                checkbox: true,
                align: 'center',
                valign: 'middle',
                sortable: true
            }, {
                title: 'Código',
                field: 'acao',
                align: 'center',
                valign: 'middle',
                width: '19%',
                sortable: true
            }, {
                title: 'Iniciativa',
                field: 'descricao_acao',
                align: 'left',
                valign: 'center',
                sortable: true
            }, {
                title: 'Produto',
                field: 'descricao_produto',
                align: 'left',
                valign: 'center',
                sortable: true
            }
        ];

        var table = $('#data-table');
        table.bootstrapTable({
            columns: colunas,
            uniqueId: "pl9_codigo",
            locale: 'pt-BR',
            cache: false,
            height: 450,
            search: true,
            showButtonText: true,
            class: "table table-sm",
            onPostBody: (data) => {
                data.map((iniciativa, index) => {
                    if (iniciativa.selecionado) {
                        table.bootstrapTable('check', index);
                        adicionaIniciativa(iniciativa);
                    } else {
                        removeIniciativa(iniciativa)
                    }
                });
            },
            onCheckAll: (rowsAfter) => {
                rowsAfter.map(iniciativa => {
                    iniciativa.selecionado = true;
                    adicionaIniciativa(iniciativa);
                });
            },
            onUncheckAll: (rowsAfter, rowsBefore) => {
                rowsBefore.map(iniciativa => {
                    iniciativa.selecionado = false;
                    removeIniciativa(iniciativa);
                });
            },
            onCheck: (row) => {
                row.selecionado = true;
                adicionaIniciativa(row);
            },
            onUncheck: (row) => {
                row.selecionado = false;
                removeIniciativa(row);
            }
        });

        const resetSelects = (programa, objetivo) => {
            if (programa) {
                cboPrograma.options.length = 0;
                cboPrograma.add(new Option('Selecione um programa estratégico', ''));
            }
            if (objetivo) {
                cboObjetivo.options.length = 0;
                cboObjetivo.add(new Option('Selecione um objetivo estratégico', ''));
            }
            limparTabela();
        };

        planejamento.getElement().addEventListener('change', () => {

            resetSelects(true, true);
            if (planejamento.getValue() === '') {
                return;
            }

            const formData = new FormData();
            formData.append('planejamento', planejamento.getValue());
            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, buscando os programas estratégicos cadastrados no planejamento.`
            };

            HttpClient.post(`${PHPSession.requestApi}/${routs.programas}`, parametros).then(response => {
                response.data.map(programa => {
                    cboPrograma.add(new Option(`${programa.programa} - ${programa.descricao}`, programa.pl9_codigo));
                });
            });
        });

        cboPrograma.addEventListener('change', () => {
            resetSelects(false, true);
            if (cboPrograma.value === '') {
                return;
            }

            const formData = new FormData();
            formData.append('programa', cboPrograma.value);
            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, buscando os objetivos do programa estratégico.`
            };

            HttpClient.post(`${PHPSession.requestApi}/${routs.objetivos}`, parametros).then(response => {
                response.data.map(objetivo => {
                    cboObjetivo.add(
                        new Option(`${objetivo.pl11_numero} - ${objetivo.pl11_descricao}`, objetivo.pl11_codigo)
                    );
                });
            });
        });

        cboObjetivo.addEventListener('change', () => {
            limparTabela(false, false);
            if (cboObjetivo.value === '') {
                return;
            }

            const formData = new FormData();
            formData.append('programa', cboPrograma.value);
            formData.append('objetivo', cboObjetivo.value);
            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, buscando as iniciativas cadastradas para o planejamento selecionado.`
            };

            HttpClient.post(`${PHPSession.requestApi}/${routs.iniciativas}`, parametros).then(response => {
                response.data.map(objetivo => {
                    objetivo.selecionado = objetivo.objetivos.length > 0;
                });
                table.bootstrapTable('load', response.data);
            });
        });

        btnSalvar.addEventListener('click', () => {
            if (!iniciativasSelecionadas.length) {
                alert('Você deve selecionar ao menos uma iniciativa.');
                return;
            }

            const formData = new FormData();
            formData.append('objetivo', cboObjetivo.value);
            iniciativasSelecionadas.map(iniciativa => {
                formData.append('iniciativas[]', iniciativa.pl12_codigo);
            });

            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, vinculando as iniciativas selecionadas ao objetivo.`
            };

            HttpClient.post(`${PHPSession.requestApi}/${routs.vincular}`, parametros).then(response => {
                alert(response.message);
            });
        });

        const limparTabela = () => {
            table.bootstrapTable('load', []);
            iniciativasSelecionadas.length = 0;
        }
    });

</script>
