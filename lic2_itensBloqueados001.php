<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!DOCTYPE html>
<html>
    <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script src="scripts/widgets/Input/DBInput.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputInteger.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputDate.widget.js" type="text/javascript"></script>
    <script src="scripts/classes/http/http.js" type="text/javascript"></script>
    </head>
    <style>
        body {
            overflow-x: hidden;
            overflow-y: scroll !important;
        }

        .griRegistros {
            display: table;
            margin: 100px auto 0 auto;
            text-align: center;
            width: 70%;
            overflow: hidden;
        }
    </style>
    <body >
        <form class="container" id="formLicitacao">
            <fieldset style="width: 300px;" >
                <legend>Relatório de Itens Bloqueaados</legend>
                <table class="form-container">
                    <tr>
                        <td class="field-size2" > <label for="contratos"><a id="abreModal">Licitação:</a></label> </td>
                        <td  consplan="3">
                            <input type="text" class="field-size3" id="codigoLicitacao">
                        </td>
                    </tr>
                </table>
            </fieldset>
            <button id="btnImprimir" name="imprimir" type="button" class="btn btn-sm">
                <i class="fas fa-print"></i>
                Imprimir
            </button>

        </form>

    <div class="griRegistros" id="modal">

        <table id="data-table" class="table table-sm" data-height="500" data-virtual-scroll="true" data-show-columns="true">
        </table>
    </div>

</body>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>

<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
<link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">

<script>
    $.noConflict();
    var ancora = document.querySelector("#abreModal");
    var modal = document.querySelector("#modal");
    var inputLicitacao =  new DBInputInteger($('codigoLicitacao'));

    modal.style.display = 'none';
    var formulario = document.querySelector("#formLicitacao");

    const routs = {
        registrosDePreco : "patrimonial/licitacoes/registrosDePreco",
        itensBloqueados : "patrimonial/licitacoes/itensBloqueados"
    };

    function buscaRegistros() {
        HttpClient.get(`${PHPSession.requestApi}/${routs.registrosDePreco}`).then(response => {
            table.bootstrapTable('load', response.data);
        });
    }

    PHPSession.loadData().then(() => {
        buscaRegistros();
    });

     const colunas = [
        {
            title: 'Cod. Sequencial',
            field: 'l21_codliclicita',
            halign: 'center',
            align: 'right',
            width: '1px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l21_codliclicita}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Tipo de Julgamento',
            field: 'l20_tipojulg',
            halign: 'center',
            align: 'right',
            width: '20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_tipojulg}

                            </a>`;
                return link;
            }
        },
        {
            title: 'Edital',
            field: 'l20_edital',
            halign: 'center',
            align: 'center',
            width:'20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_edital}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Descricao Tipo de Compra',
            field: 'pc50_descr',
            halign: 'center',
            align: 'center',
            width: '70px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.pc50_descr}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Numeração',
            field: 'l20_numero',
            halign: 'center',
            align: 'center',
            width: '20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_numero}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Codigo Tipo de Compra',
            field: 'l20_codtipocom',
            halign: 'center',
            align: 'center',
            width: '20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_codtipocom}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Cod. Usuario',
            field: 'l20_id_usucria',
            halign: 'center',
            align: 'center',
            width: '20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_id_usucria}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Data Criação',
            field: 'l20_datacria',
            halign: 'center',
            align: 'center',
            width: '70px;',
            formatter: (a, data) => {
                date = new Date(data.l20_datacria);
                dataFormatada = date.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${dataFormatada}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Hora Criação',
            field: 'l20_horacria',
            halign: 'center',
            align: 'center',
            width: '60px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_horacria}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Data Abertura',
            field: 'l20_dataaber',
            halign: 'center',
            align: 'center',
            width: '70px;',
            formatter: (a, data) => {
                date = new Date(data.l20_dataaber);
                dataFormatada = date.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${dataFormatada}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Data Publicação',
            field: 'l20_dtpublic',
            halign: 'center',
            align: 'center',
            width: '70px;',
            formatter: (a, data) => {
                date = new Date(data.l20_dtpublic);
                dataFormatada = date.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${dataFormatada}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Hora Abertura',
            field: 'l20_horaaber',
            halign: 'center',
            align: 'center',
            width: '60px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_horaaber}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Local da Licitação',
            field: 'l20_local',
            halign: 'center',
            align: 'center',
            width: '70px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_local.substr(0,20)}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Objeto',
            field: 'l20_objeto',
            halign: 'center',
            align: 'center',
            width: '400px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_objeto.substr(0,20) + '...'}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Sigla',
            field: 'l44_sigla',
            halign: 'center',
            align: 'center',
            width: '50px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l44_sigla}
                            </a>`;
                return link;
            }
        },
        {
            title: 'Situação',
            field: 'l20_licsituacao',
            halign: 'center',
            align: 'center',
            width: '20px;',
            formatter: (a, data) => {
                let link = `<a style="text-decoration: none; color: black"
                                onclick="selecionaLicitacao(${data.l21_codliclicita})" >
                                ${data.l20_licsituacao}
                            </a>`;
                return link;
            }
        }
    ];

    const table = jQuery('#data-table');

    table.bootstrapTable({
        columns: colunas,
        uniqueId: "id",
        locale: 'pt-BR',
        cache: false,
        search: true,
        class: "table table-sm",
    });

    ancora.addEventListener("click", async () => {
        formulario.style.display = "none";
        modal.style.display = "table";
    })

    function selecionaLicitacao(codigoLicictacao) {
        inputLicitacao.value = codigoLicictacao;
        formulario.style.display = "table";
        modal.style.display = "none";
    }

    document.querySelector("#btnImprimir").addEventListener("click", () => {


            var formData = new FormData();
            formData.append("licitacao", inputLicitacao.value);
            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.itensBloqueados}`, {body: formData}).then(response => {
                window.open(response.data.pdf, '_blank');
                document.location.reload();
            });
    })
</script>
<?php
  db_menu();
?>
</html>
