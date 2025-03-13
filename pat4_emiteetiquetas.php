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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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

        .modelos-etiquetas tr, .modelos-etiquetas img {
            cursor: pointer;
        }
    </style>
</head>
<body class="body-default">
<div class="container" style="width: 800px;">
    <fieldset>
        <legend>Imprimir Etiquetas</legend>
        <table class="form-container" style="border-collapse: separate;">
            <tr>
                <td>
                    <label for="coddepto" id="departamentoAncora">Departamento:</label>
                </td>
                <td>
                    <input type="text" id="coddepto"/>
                    <input type="text" id="descrdepto"/>
                </td>
            </tr>
            <tr>
                <td><label for="t30_codigo" id="divisaoAncora">Código da divisão:</label></td>
                <td>
                    <input type="text" id="t30_codigo"/>
                    <input type="text" id="t30_descr"/>
                </td>
            </tr>
            <tr>
                <td><label for="t52_bem" id="bemInicialAncora">Bem Inicial:</label></td>
                <td>
                    <input type="text" id="t52_bem"/>
                    <input type="text" id="t52_descr"/>

                    <label id="ancoraBemFinal">até:</label>

                    <input type="text" id="codigoBemFinal"/>
                    <input type="text" id="descrBemFinal"/>
                </td>
            </tr>
            <tr>
                <td><label for="t52_bem" id="placaInicialAncora">Placa:</label></td>
                <td>
                    <input type="text" id="placaInicialCodigo"/>
                    <input type="text" id="placaInicialDescr"/>

                    <label id="placaFinalAncora">até:</label>

                    <input type="text" id="placaFinalCodigo"/>
                    <input type="text" id="placaFinalDescr"/>
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" class="btn btn-light" onclick="pesquisar()">
        <i class="fas fa-search"></i>
        Pesquisar
    </button>
    <button type="button" class="btn btn-light" onclick="limparCampos()">
        <i class="fas fa-trash"></i>
        Limpar
    </button>
</div>

<div id="divModeloEtiqueta" class="container">
    <table class="modelos-etiquetas" style="text-align: left;">
        <tr>
            <td>
                <input type="radio" name="modelo" id="modelo01" value='1' checked/>
            </td>
            <td>
                <label for="modelo01"><img
                        src="app/Domain/Patrimonial/Patrimonio/Relatorios/TemplatesEtiquetas/ImagemModelo/MODELO_UM.png"
                        height='150'/></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="modelo" id="modelo02" value='2' checked>
            </td>
            <td>
                <label for="modelo02"><img
                        src="app/Domain/Patrimonial/Patrimonio/Relatorios/TemplatesEtiquetas/ImagemModelo/MODELO_DOIS.png"
                        height='150'/></label>
            </td>
        </tr>
    </table>
    <button type="button" class="btn btn-light" onclick="imprimir()">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>

<div id="divBens" class="subcontainer" style="width: 800px; display: none;">
    <fieldset>
        <legend>Emitir placas</legend>
        <table id="data-table-emitir"
               class="table table-sm">
        </table>
    </fieldset>
</div>

<script type="text/javascript" src="scripts/session.js"></script>

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

    const routes = {
        buscarBens: 'patrimonial/patrimonio/consulta/bem/buscar',
        emitirBens: 'patrimonial/patrimonio/etiquetas/imprimir'

    };

    const departamentoAncora = document.getElementById("departamentoAncora");
    const departamentoCodigo = document.getElementById("coddepto");
    const departamentoDescricao = document.getElementById("descrdepto");
    const divisaoAncora = document.getElementById("divisaoAncora");
    const divisaoCodigo = document.getElementById("t30_codigo");
    const divisaoDescricao = document.getElementById("t30_descr");
    const bemInicialAncora = document.getElementById("bemInicialAncora");
    const bemInicialCodigo = document.getElementById("t52_bem");
    const bemInicialDescr = document.getElementById("t52_descr");
    const bemFinalAncora = document.getElementById("ancoraBemFinal");
    const bemFinalCodigo = document.getElementById("codigoBemFinal");
    const bemFinalDescr = document.getElementById("descrBemFinal");
    const placaInicialAncora = document.getElementById("placaInicialAncora");
    const placaInicialCodigo = document.getElementById("placaInicialCodigo");
    const placaInicialDescr = document.getElementById("placaInicialDescr");
    const placaFinalAncora = document.getElementById("placaFinalAncora");
    const placaFinalCodigo = document.getElementById("placaFinalCodigo");
    const placaFinalDescr = document.getElementById("placaFinalDescr");

    const radioModelo = document.getElementsByName("modelo");

    const divModeloEtiqueta = document.getElementById('divModeloEtiqueta');
    const fecharModal = () => {
        if (!!windowModeloEtiqueta.oDBMask) {
            windowModeloEtiqueta.oDBMask.destroy();
        }
        windowModeloEtiqueta.hide();
    }
    const windowModeloEtiqueta = new windowAux('windowModeloEtiqueta', 'Configurar Modelo', 800, 500);
    windowModeloEtiqueta.setContent(divModeloEtiqueta);
    windowModeloEtiqueta.setShutDownFunction(fecharModal);

    const divBens = document.getElementById('divBens');
    const tabelaEmitirPlacas = jQuery('#data-table-emitir');


    new DBLookUp(departamentoAncora, departamentoCodigo, departamentoDescricao, {
        'sArquivo': 'func_db_depart.php',
        'sLabel': 'Pesquisar Departamento',
        'sObjetoLookUp': "db_iframe_depart"
    });

    new DBLookUp(divisaoAncora, divisaoCodigo, divisaoDescricao, {
        'sArquivo': 'func_departdiv.php',
        'sLabel': 'Pesquisar Divisão',
        'sObjetoLookUp': "db_iframe_departdiv"
    });

    new DBLookUp(bemInicialAncora, bemInicialCodigo, bemInicialDescr, {
        'sArquivo': 'func_bens.php',
        'sLabel': 'Pesquisar Bem',
        'sObjetoLookUp': "db_iframe_bens"
    });

    new DBLookUp(bemFinalAncora, bemFinalCodigo, bemFinalDescr, {
        'sArquivo': 'func_bens.php',
        'sLabel': 'Pesquisar Bem',
        'sObjetoLookUp': "db_iframe_bens_final",
        "aCamposAdicionais": ['t52_bem', 't52_descr'],
        'fCallBack': (a, erro, codigoBem, descrBem) => {
            if (erro || a !== '') {
                return;
            }
            bemFinalCodigo.value = codigoBem;
            bemFinalDescr.value = descrBem;
        }
    });

    new DBLookUp(placaInicialAncora, placaInicialCodigo, placaInicialDescr, {
        'sArquivo': 'func_bens.php',
        'sLabel': 'Pesquisar Placa',
        'sObjetoLookUp': "db_iframe_placas",
        "aCamposAdicionais": ['t52_ident', 't52_descr'],
        'fCallBack': (a, erro, codigoPlaca, descrBem) => {
            if (erro || a !== '') {
                return;
            }
            placaInicialCodigo.value = codigoPlaca;
            placaInicialDescr.value = descrBem;
        }
    });

    new DBLookUp(placaFinalAncora, placaFinalCodigo, placaFinalDescr, {
        'sArquivo': 'func_bens.php',
        'sLabel': 'Pesquisar Placa',
        'sObjetoLookUp': "db_iframe_placas_final",
        "aCamposAdicionais": ['t52_ident', 't52_descr'],
        'fCallBack': (a, erro, codigoPlaca, descrBem) => {
            if (erro || a !== '') {
                return;
            }
            placaFinalCodigo.value = codigoPlaca;
            placaFinalDescr.value = descrBem;
        }
    });


    bemInicialDescr.hidden = true;
    bemFinalDescr.hidden = true;
    placaInicialDescr.hidden = true;
    placaFinalDescr.hidden = true;


    function pesquisar() {
        if (!validaCampos()) {
            alert('Informe um campo!');
            return false;
        }

        const formData = new FormData();
        formData.append('departamento', departamentoCodigo.value);
        formData.append('divisao', divisaoCodigo.value);
        if (empty(placaInicialCodigo.value)) {
            placaInicialCodigo.value = 1
        }
        formData.append('bemInicial', bemInicialCodigo.value);
        formData.append('bemFinal', bemFinalCodigo.value);
        formData.append('placaInicial', placaInicialCodigo.value);
        formData.append('placaFinal', placaFinalCodigo.value);
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routes.buscarBens}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            let dados = {
                items: response.data,
                footer: {
                    "codigo": "alguma coisa",
                    "_codigo_colspan": 4,
                }
            }
            tabelaEmitirPlacas.bootstrapTable('load', response.data);
        })
    }

    function limparCampos() {
        departamentoAncora.value = '';
        departamentoCodigo.value = '';
        departamentoDescricao.value = '';
        divisaoAncora.value = '';
        divisaoCodigo.value = '';
        divisaoDescricao.value = '';
        bemInicialAncora.value = '';
        bemInicialCodigo.value = '';
        bemInicialDescr.value = '';
        bemFinalAncora.value = '';
        bemFinalCodigo.value = '';
        bemFinalDescr.value = '';
        placaInicialAncora.value = '';
        placaInicialCodigo.value = '';
        placaInicialDescr.value = '';
        placaFinalAncora.value = '';
        placaFinalCodigo.value = '';
        placaFinalDescr.value = '';

        tabelaEmitirPlacas.bootstrapTable('removeAll');
    }

    divBens.style.display = '';

    function validaCampos() {
        if (departamentoCodigo.value !== '') {
            return true;
        }
        if (divisaoCodigo.value !== '') {
            return true;
        }
        if (bemInicialCodigo.value !== '') {
            return true;
        }
        if (bemFinalCodigo.value !== '') {
            return true;
        }
        if (placaInicialCodigo.value !== '') {
            return true;
        }
        if (placaFinalCodigo.value !== '') {
            return true;
        }
        return false;
    }

    jQuery(document).ready(jQuery => {
        const buttons = () => {
            return {
                btnImprimir: {
                    html:
                        `<div style="text-align: left; margin-right: 5px;">
                        <button onClick="selecionarModelo();"> Imprimir <i class="fas fa-print"></i></i></button>
                    </div>`
                }
            };
        }

        tabelaEmitirPlacas.bootstrapTable({
            height: 400,
            buttons: buttons,
            showFooter: true,
            dataField: 'items',
            columns: [
                {
                    checkbox: true,
                    width: 20
                },
                {
                    field: 'codigo',
                    title: 'Código',
                    halign: 'center',
                    align: 'center',
                    width: 100
                },
                {
                    field: 'descricao',
                    title: 'Descrição',
                    halign: 'center',
                    align: 'center',
                    width: 500,
                    footerFormatter: (data) => {
                        return "Registros totais: " + data.length;
                    },
                },
                {
                    field: 'placa',
                    title: 'Placa',
                    halign: 'center',
                    align: 'center',
                    width: 100
                },
            ],
        });
    });

    function selecionarModelo() {
        windowModeloEtiqueta.show(0, 0, true);
    }

    function imprimir() {
        let bensSelecionados = tabelaEmitirPlacas.bootstrapTable('getSelections');

        if (bensSelecionados.length == 0) {
            alert('Selecione os bens para emitir!');
            return false;
        }


        const formData = new FormData();

        for (let bemSelecionado of bensSelecionados) {
            formData.append('codigos[]', bemSelecionado.codigo);
        }
        formData.append('modelo', document.querySelector('input[name="modelo"]:checked').value);

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routes.emitirBens}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return false;
            }
            window.open(response.data, 'bens_placa', "popup");
        });

        fecharModal();
    }

</script>
</body>
</html>
