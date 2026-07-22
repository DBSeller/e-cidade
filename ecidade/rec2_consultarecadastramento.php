<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <style>
        .modelos-etiquetas tr td {
            padding: 5px 0;
        }

        .modelos-etiquetas tr,
        .modelos-etiquetas img {
            cursor: pointer;
        }
    </style>
</head>

<body class="body-default">
    <div class="container" style="width: 800px;">
        <fieldset>
            <legend>Conferência recadastramento</legend>
            <table class="form-container" style="border-collapse: separate;">
                <tr>
                    <td>
                        <label for="rh37_funcao"><a id="labelCargo">Cargo:</a></label>
                    </td>
                    <td>
                        <input type="text" name="rh37_funcao" id="rh37_funcao">
                        <input type="text" name="rh37_descr" id="rh37_descr">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="r70_codigo"><a id="labelLotacao">Lotação:</a></label>
                    </td>
                    <td>
                        <input type="text" name="r70_codigo" id="r70_codigo">
                        <input type="text" name="r70_descr" id="r70_descr">
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" class="btn btn-light" onclick="buscaConferencia()">
            <i class="fas fa-search"></i>
            Pesquisar
        </button>
        <button type="button" class="btn btn-light" onclick="limparCampos()">
            <i class="fas fa-trash"></i>
            Limpar
        </button>
    </div>

    <div id="divBens" class="subcontainer" style="width: 80%;">
        <fieldset>
            <legend>Atendimentos</legend>
            <table id="data-table-atendimentos" class="table table-sm">
            </table>
        </fieldset>
    </div>

    <script type="text/javascript" src="scripts/session.js"></script>

    <!-- requires bootstrap table -->
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
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

        const routes = {
            buscarBens: 'patrimonial/patrimonio/consulta/bem/buscar',
            emitirBens: 'patrimonial/patrimonio/etiquetas/imprimir'

        };
        var cargoLookUp = new DBLookUp($('labelCargo'), $('rh37_funcao'), $('rh37_descr'), {
          "sArquivo" : "func_rhfuncao.php",
          "sObjetoLookUp" : "db_iframe_rhfuncao",
          "sLabel" : "Pesquisar Cargo",
        });

        var lotacaoLookUp = new DBLookUp($('labelLotacao'), $('r70_codigo'), $('r70_descr'), {
          "sArquivo" : "func_rhlota.php",
          "sObjetoLookUp" : "db_iframe_rhlota",
          "sLabel" : "Pesquisar Lotação",
        });

        const tabelaAtendimentos = jQuery('#data-table-atendimentos');
        const inputFuncao = document.getElementById('rh37_funcao');
        const inputFuncaoDescr = document.getElementById('rh37_descr');
        const inputLotacao = document.getElementById('r70_codigo');
        const inputLotacaoDescr = document.getElementById('r70_descr');
        const sRPC = 'rh4_recadastramento.RPC.php';


        function buscaConferencia() {
            
            const form = {
                route: 'conferencia',
                dados : {
                    cargo : inputFuncao.value,
                    lotacao : inputLotacao.value
                }
            };

            const oAjaxRequest = new AjaxRequest(sRPC, form,
                function (resp, erro) {
                    tabelaAtendimentos.bootstrapTable("load", resp.data);
                });
            oAjaxRequest.execute();
        }

        function limparCampos() {
            tabelaAtendimentos.value = '';
            inputFuncao.value = '';
            inputFuncaoDescr.value = '';
            inputLotacao.value = '';
            inputLotacaoDescr.value = '';
            tabelaAtendimentos.bootstrapTable('removeAll');
        }
        function adicionaAcao(value, row, index) {
            return '<a href="#" title="Consultar" onclick="consultar(\''+row.numero_atendimento+'\', \''+row.p58_codproc+'\')"><i class="fas fa-eye"></i></a>';
        }

        function consultar(atendimento, p58_codproc) {
            js_OpenJanelaIframe(
                'CurrentWindow.corpo',
                'db_visualizador_recadastramento',
                `pro4_processo_recadastramento_externo.php?atendimento=${atendimento}&p58_codproc=${p58_codproc}`,
                'Visualizador Recadastramento',
                true
            );
        }

        jQuery(document).ready(jQuery => {

            tabelaAtendimentos.bootstrapTable({
                height: 550,
                search: true,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'All'],
                columns: [
                    {
                        field: 'numero_atendimento',
                        title: 'Nº Atend.',
                        halign: 'center',
                        align: 'center',
                        sortable: true,
                        width: 80
                    },
                    {
                        field: 'matricula',
                        title: 'Matrícula',
                        halign: 'center',
                        align: 'center',
                        width: 80,
                        sortable: true
                    },
                    {
                        field: 'cpf',
                        title: 'CPF',
                        halign: 'center',
                        align: 'center',
                        width: 100
                    },
                    {
                        field: 'nome',
                        title: 'Nome',
                        halign: 'center',
                        align: 'center',
                        width: 200,
                        sortable: true
                    },
                    {
                        field: 'descricao_lotacao',
                        title: 'Lotação',
                        halign: 'center',
                        align: 'center',
                        width: 200,
                        sortable: true
                    },
                    {
                        field: 'cargo',
                        title: 'Cargo',
                        halign: 'center',
                        align: 'center',
                        width: 200,
                        sortable: true
                    },
                    {
                        field: 'Ação',
                        title: 'Ação',
                        halign: 'center',
                        align: 'center',
                        width: 50,
                        formatter: adicionaAcao,
                    }
                ],
            });
        });
    </script>
</body>

</html>