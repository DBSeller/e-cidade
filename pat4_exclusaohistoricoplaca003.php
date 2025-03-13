<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\Patrimonial\Patrimonio\Bem\Model\Bem;

$bem = new Bem(931);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta charset="iso-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Expires" CONTENT="0">
        <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
        <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
            rel="stylesheet"/>
        <link href="estilos.css" rel="stylesheet" type="text/css">

        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
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
    </head>
    <body>
        <div class="container" style="width:40%;">
            <input type="hidden" id="id-bem">
            <div>
                <div style="display:inline-block;">
                    <a href="#" onclick="abreConsultaBem()" style="font-weight:bold;">Código do Bem:</a>
                    <input class="input" id="codigo-bem" style="width:150px" type="text">
                </div>
                <div style="display:inline-block;">
                    <label for="placa" style="font-weight:bold;">Placa:</label>
                    <input class="input" id="sequencial-placa" style="width:150px" type="text">
                </div>
                <button type="button" id="btn-pesquisar" class="btn btn-light">Pesquisar</button>
            </div>
            <div style="text-align:left; margin-bottom:5px;">
                <button type="button" id="btn-excluir" disabled class="btn btn-light">Excluir</button>
            </div>

            <form action="POST">
                <table id="data-table"
                    class="table table-sm"
                    data-height="550"
                    data-virtual-scroll="true"
                    data-show-columns="true"

                    style="width: 100%;">
            </form>
        </div>
    </body>
</html>

<script type="text/javascript">

    const formatterData = (value) => {
        return js_formatar(value, 'd');
    }

    const estiloLinha = (data) => {
        return {classes: 'table-hover'};
    }

    const colunas = [
        {
            checkbox: true
        },
        {
            title: 'Codigo Bem',
            field: 't41_bem',
            halign: 'center',
            align: 'center'
        },
        {
            title: 'Codigo',
            field: 't41_codigo',
            halign: 'center',
            align: 'center'
        },
        {
            title: 'Data',
            field: 't41_data',
            halign: 'center',
            align: 'center',
            formatter: formatterData
        },
        {
            title: 'Observação',
            field: 't41_obs',
            halign: 'center',
            align: 'left',
        },
        {
            title: 'Placa',
            field: 't41_placaseq',
            halign: 'center',
            align: 'center'
        }
    ];

    var btnExcluir = $('#btn-excluir');
    var btnPesquisar = $('#btn-pesquisar');

    var table = $('#data-table');
    var selections = []

    function getIdSelections() {
        return $.map(table.bootstrapTable('getSelections'), function (row) {
            return row.t41_codigo
        });
    }

    table.bootstrapTable({
        columns: colunas,
        uniqueId: "id",
        locale: 'pt-BR',
        cache: false,
        pagination: true,
        pageSize: 15,
        pageList: [10, 25, 50, 100, 200, 'All'],
        search: true,
        class: "table table-sm",
        rowStyle: estiloLinha
    });

    table.bootstrapTable('hideColumn', 't41_codigo');

    table.on(
        'check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table',
        function () {
            btnExcluir.prop('disabled', !table.bootstrapTable('getSelections').length);

            // save your data, here just save the current page
            selections = getIdSelections();
            // push or splice the selections if you want to save all data selections
        }
    );

    btnExcluir.click(() => {
        alertify.confirm(
            'Deseja excluir os históricos selecionados?',
            (e) => {
                if (e) {
                    excluiPlacas();
               }
            }
        );
    });

    btnPesquisar.click(() => {
        const idBem= $('#codigo-bem').val();
        const sequencialPlaca = $('#sequencial-placa').val();

        if (idBem == '' && sequencialPlaca == '') {
            alertify.alert('Preencha algum dos filtros para realizar a pesquisa.')
            return;
        }

        buscaPlacas(idBem, sequencialPlaca);
    });

    function abreConsultaBem() {
        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_consulta_bens', 
            'func_bens.php?funcao_js=parent.consultaBemCallback|t52_bem',
            'Pesquisa',
            true
        );
    }

    function buscaPlacas(idBem = null, sequencialPlaca = null) {
        const parametros = `acao=buscaPlacasParaExclusao&id=${idBem}&sequencialPlaca=${sequencialPlaca}`;
        
        HttpClient.get(`pat4_bensplaca.RPC.php?${parametros}`)
            .then((response) => {
                if (response.placas) {
                    $('#id-bem').val(response.placas[0].t41_bem);
                    table.bootstrapTable('load', response.placas);
                }
            });
    }

    function excluiPlacas() {
        var idsPlacas = getIdSelections();
        const formData = new FormData();

        formData.append('acao', 'excluiPlacas');
        formData.append('idBem', $('#id-bem').val());

        for (var i = 0; i < idsPlacas.length; i++) {
            formData.append('idsPlacas[]', idsPlacas[i]);
        }

        HttpClient.post('pat4_bensplaca.RPC.php', {body: formData}).then((response) => {
            if (response.erro) {
                alertify.alert(response.erro.urlDecode());
                return;
            }

            if (response.foiExcluido == false) {
                alertify.alert(response.mensagem.urlDecode());
                return;
            }

            table.bootstrapTable('remove', {
                field: 't41_codigo',
                values: idsPlacas
            });

            btnExcluir.prop('disabled', true);
        });
    }

    function consultaBemCallback(id) {
        buscaPlacas(id);
        $('#id-bem').val(id);
        db_iframe_consulta_bens.hide();
    }
  
</script>