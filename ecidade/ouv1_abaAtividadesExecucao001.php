<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.css">
</head>
<body class="body-default">
<div class="container">
    <input type="hidden" id="codigoTipoProcesso" value="<?= isset($p51_codigo) ? $p51_codigo : '' ?>">
    <fieldset>
        <legend>Atividades de Execução do Processo</legend>
        <table class="form-container" style="border-collapse: separate;">
            <tr>
                <td><label for="p51_codigo" id="tipoprocesso_ancora">Código do Tipo: &nbsp;</label></td>
                <td>
                    <input type="text" id="p51_codigo" value="<?= isset($p51_codigo) ? $p51_codigo : '' ?>"/>
                    <input type="text" id="p51_descr" value="<?= isset($p51_descr) ? $p51_descr : '' ?>"/>
                </td>
            </tr>
            <tr>
                <td><label for="atividade">Atividade: </label></td>
                <td>
                    <select name="atividade" id="atividade" class="">
                        <option value="">Seleciona uma atividade</option>
                        <option value="1">Gerar</option>
                        <option value="2">Conferir</option>
                        <option value="3">Assinar</option>
                        <option value="4">Arquivar</option>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" class="btn btn-light" id="btnAdicionar">
        <i class="fas fa-plus"></i>
        Adicionar
    </button>
</div>
<div class="container">
    <fieldset>
        <legend>Lista de Atividades</legend>
        <div style="width: 1000px">
            <table id="data-table"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"
                   style="width: 100%;">
            </table>
        </div>
    </fieldset>
    <div class="alert alert-primary text-left" role="alert">
        Para mudar a <strong>ordem</strong> de execução das <strong>Atividades</strong>, segure e arraste a atividade para a posição que deseja!
    </div>
</div>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/jquery-tablednd/jquery.tablednd.js"></script>
<script src="assets/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    var urlApi;
    var urlApiProtocolo;
    window.addEventListener('load', () => {
        PHPSession.loadData().then(() => {
            urlApi = PHPSession.requestApi;
            urlApiProtocolo = `${urlApi}/patrimonial/protocolo`;
            buscarAtividades();
            if (!empty(cboCodigoTipoProcesso.value)) {
                buscarAtividadesVinculadas(cboCodigoTipoProcesso.value);
            }
        });
    });

    const cboAtividade = document.getElementById('atividade');
    const cboCodigoTipoProcesso = document.getElementById('p51_codigo');
    const btnAdicionar = document.getElementById('btnAdicionar');

    const buscarAtividades = () => {
        HttpClient.get(`${urlApiProtocolo}/atividades-execucao`).then((response) => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const atividades = response.data;
            cboAtividade.options.length = 1;
            atividades.map((atividade) => {
                cboAtividade.add(new Option(atividade.p114_atividade, atividade.p114_codigo));
            });

        });
    }

    const buscarAtividadesVinculadas = (codigoTipoProcesso) => {
        HttpClient.get(`${urlApiProtocolo}/atividades-execucao/tipo-processo/${codigoTipoProcesso}`)
            .then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                const atvidadesVinculadas = response.data;
                table.bootstrapTable('load', atvidadesVinculadas);
            });
    }

    const tipoProcessoAncora = document.getElementById("tipoprocesso_ancora");
    const tipoProcessoCodigo = document.getElementById("p51_codigo");
    const tipoProcessoDescricao = document.getElementById("p51_descr");

    const tipoProcessoLookup = new DBLookUp(tipoProcessoAncora, tipoProcessoCodigo, tipoProcessoDescricao, {
        'sArquivo': 'func_tipoproc_todos.php',
        'sLabel': 'Pesquisa',
        'sObjetoLookUp': "db_iframe_tipo_processo"
    });
    tipoProcessoLookup.desabilitar();

    var table = jQuery('#data-table');

    const callbackAcoes = (value, row) => {
        return `<a href="#" class="excluir"><i class="fas fa-trash"></i></a>`;
    }

    const ordemFormatter = (value, row) => {
        return row.pivot.p115_ordem;
    }

    window.operateEvents = {
        'click .excluir': function (e, value, row, index) {
            const formData = new FormData();
            PHPSession.appendFormData(formData);
            formData.append('codigoTipoProcesso', cboCodigoTipoProcesso.value);
            formData.append('codigoAtividade', row.p114_codigo);
            formData.append('ordem', row.pivot.p115_ordem);
            HttpClient.post(`${urlApiProtocolo}/atividades-execucao/excluir-vinculo`, {body: formData})
                .then((response) => {
                    alert(response.message);
                    if (response.error) {
                        return;
                    }
                    const atvidadesVinculadas = response.data;
                    table.bootstrapTable('load', atvidadesVinculadas);
                });
        }
    }

    const salvarOrdenacao = (itens) => {
        const formData = new FormData();
        PHPSession.appendFormData(formData);
        formData.append('codigoTipoProcesso', cboCodigoTipoProcesso.value);
        formData.append('atividadesOrdenar', JSON.stringify(itens));
        HttpClient.post(`${urlApiProtocolo}/atividades-execucao/reordenar-vinculos`, {body: formData})
            .then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                const atvidadesVinculadas = response.data;
                table.bootstrapTable('load', atvidadesVinculadas);
            });
    }

    table.bootstrapTable({
        data: [],
        locale: 'pt-BR',
        showButtonText: true,
        class: "table table-sm",
        reorderableRows: true,
        useRowAttrFunc: true,
        onReorderRow: salvarOrdenacao,
        columns: [
            {
                title: 'Atividade',
                field: 'p114_atividade',
                align: 'left'
            }, {
                title: 'Status',
                field: 'p114_status',
                align: 'left'
            }, {
                title: 'Ordem',
                field: 'ordem',
                align: 'left',
                width: '70',
                formatter: ordemFormatter
            }, {
                field: 'acoes',
                title: 'Excluir',
                align: 'center',
                events: window.operateEvents,
                formatter: callbackAcoes
            }
        ]
    });

    btnAdicionar.addEventListener('click', (event) => {
        if (empty(cboAtividade.value)) {
            alert("Informe a atividade para vincular");
            return;
        }

        const formData = new FormData();
        PHPSession.appendFormData(formData);
        formData.append('codigoTipoProcesso', cboCodigoTipoProcesso.value);
        formData.append('codigoAtividade', cboAtividade.value);
        HttpClient.post(`${urlApiProtocolo}/atividades-execucao/vincular`, {body: formData})
            .then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                const atvidadesVinculadas = response.data;
                table.bootstrapTable('load', atvidadesVinculadas);
                cboAtividade.value = '';
            });
    })
</script>
</body>
</html>
