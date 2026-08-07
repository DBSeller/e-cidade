<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
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
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script  type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body class="body-default" onload="js_pesquisarAcordo()">
<div class="alert alert-primary text-left" role="alert">
    Caso haja mais de um histórico de alteração, o e-cidade irá exibir a
    última posição de alteração ou cessão de contratado cadastrada, sendo esta passível de exclusão.
</div>
<div class="container">
    <fieldset>
        <fieldset>
            <legend><b>Dados do Acordo</b></legend>
            <table class="form-container" style="border-collapse: separate;">
                <tr>
                    <td><label for="ac16_sequencial" id="acordo_ancora">Acordo: </label></td>
                    <td>
                        <input type="text" id="ac16_sequencial" class="readonly" readonly style="margin-left: 10px;"/>
                        <input type="text" id="ac16_resumoobjeto" />
                    </td>
                </tr>
                <tr>
                    <td><label for="numero_aditamento" id="acordo_aditamento">Nº Termo: </label></td>
                    <td>
                        <input type="text" id="numero_aditamento" class="field-size2" maxlength="20"
                               style="margin-left: 10px;"/>
                    </td>
                </tr>
                <tr id="trTipoAlteracao">
                    <td>
                        <label for="ctnCboTipoAlteracao">Tipo de Alteração: </label>
                    </td>
                    <td id="ctnCboTipoAlteracao">
                        <select name="oCboTipoAlteracao" id="oCboTipoAlteracao"
                                style="width: 120px; margin-left: 10px;">
                            <option value="1">Aditamento</option>
                            <option value="2">Apostilamento</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
        <legend><b>Contratado Atual</b></legend>
        <table class="form-container">
            <tr>
                <td style="font-weight: normal;">
                    <label for="ac16_contratado">NumCgm: </label>
                    <input type="text" id="ac16_contratado" class="field-size2 readonly" style="margin-left: 55px"
                           readonly/>
                    <input type="text" id="z01_nome" class="field-size8 readonly" readonly/>
                </td>
            </tr>
            <tr>
                <td style="font-weight: normal;">
                    <label for="z01_cgccpf">Documento: </label>
                    <input type="text" id="z01_cgccpf" class="field-size4 readonly" readonly style="margin-left: 42px"/>
                </td>
            </tr>
        </table>
        </fieldset>
        <fieldset>
            <legend><b>Novo Contratado</b></legend>
            <table class="form-container">
                <tr>
                    <td><label for="z01_numcgm" id="cgm_ancora">NumCgm: </label></td>
                    <td>
                        <input type="text" id="z01_numcgm" style="margin-left: 45px"/>
                        <input type="text" id="nome_cgm" data="z01_nome" class="field-size8">
                    </td>
                </tr>
                <tr>
                    <td><label for="numero_documento" id="cgm_ancora">Documento: </label></td>
                    <td>
                        <input
                            type="text"
                            id="numero_documento"
                            data="z01_cgccpf"
                            class="field-size4 readonly"
                            style="margin-left: 45px"
                            readonly/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><b>Justificativa</b></legend>
            <tr>
                <td>
                    <textarea
                        id="justificativa"
                        type="text"
                        name="justificativa"
                        rows="3" cols="76"
                        rel="ignore-css"
                        style="background-color:#E6E4F1"
                        maxlength="60"></textarea>
                </td>
            </tr>
        </fieldset>
    </fieldset>
    <button type="button" class="btn btn-light" id="btn_salvar">
        <i class="fas fa-save"></i>
        Salvar Alteração
    </button>
    <button type="button" class="btn btn-light" id="btn_pesquisar" onclick="js_pesquisarAcordo()">
        <i class="fas fa-search"></i>
        Pesquisar Acordo
    </button>
</div>
<div class="subcontainer" id="divAlteracoes" style="width: 800px">
    <fieldset>
        <legend>Histórico Alterações</legend>
        <table id="data-table"
               class="table table-sm">
        </table>
    </fieldset>
</div>

<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript">
    $.noConflict();

    const acordoAncora = document.getElementById('acordo_ancora');
    const acordoCodigo = document.getElementById('ac16_sequencial');
    const acordoObjeto = document.getElementById('ac16_resumoobjeto');
    const cgmAncora = document.getElementById('cgm_ancora');
    const cgmCodigo = document.getElementById('z01_numcgm');
    const cgmDescricao = document.getElementById('nome_cgm');
    const documentoAtual = document.getElementById('z01_cgccpf');
    const documentoNovo = document.getElementById('numero_documento');
    const codigoContratado = document.getElementById('ac16_contratado');
    const nomeContratado = document.getElementById('z01_nome');
    const numeroAditamento = document.getElementById('numero_aditamento');
    const btnSalvar = document.getElementById('btn_salvar');
    const oCboTipoAlteracao = document.getElementById('oCboTipoAlteracao');
    const justificativa = document.getElementById('justificativa');
    const divAlteracoes = document.getElementById('divAlteracoes');
    const tabelaAlteracoes = jQuery('#data-table');
    var vigenciaInicio = '';
    var vigenciaFinal = '';
    var estrangeiro = '';

    function js_pesquisarAcordo() {

        let url = 'func_acordo.php?funcao_js=parent.js_mostrarAcordo|ac16_sequencial|ac16_resumoobjeto'
        js_OpenJanelaIframe('CurrentWindow.corpo',
            'db_iframe_acordo',
            url,
            'Pesquisar Acordos',
            true);
    }

    function js_mostrarAcordo(codigo, resumo) {

        acordoCodigo.value   = codigo;
        acordoObjeto.value = resumo;

        db_iframe_acordo.hide();

        js_buscaContratado();
    }

    function js_buscaContratado() {
        const formData = new FormData();
        formData.append('acao', 'buscarContratadoAtual');
        formData.append('codigoAcordo', acordoCodigo.value);
        HttpClient.post('con4_alteracessacontratado.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            let contratadoAtual = response.contratadoAtual;
            codigoContratado.value = contratadoAtual.ac16_contratado;
            nomeContratado.value = contratadoAtual.z01_nome;
            vigenciaInicio = contratadoAtual.ac16_datainicio;
            vigenciaFinal = contratadoAtual.ac16_datafim;
            if (contratadoAtual.estrangeiro) {
                documentoAtual.value = contratadoAtual.estrangeiro
            } else {
                documentoAtual.value = formatadocumento(contratadoAtual.z01_cgccpf);
            }


            callbackAlteracoes();
        });
    }

    function js_limpaCampos() {
        acordoCodigo.value = '';
        acordoObjeto.value = '';
        cgmCodigo.value = '';
        cgmDescricao.value = '';
        codigoContratado.value = '';
        nomeContratado.value = '';
        numeroAditamento.value = '';
        justificativa.value = '';
        documentoNovo.value = '';
        documentoAtual.value = '';
    }

    function js_verificaCampos() {
        if (numeroAditamento.value === '') {
            alert('Campo Número do Termo é de preenchimento obrigatório!')
            return false;
        }

        if (cgmCodigo.value === '') {
            alert('Por favor, informe o novo contratado!');
            return false;
        }

        if (cgmCodigo.value === codigoContratado.value) {
            alert('Novo contratado deve ser diferente do contratado atual!');
            return false
        }

        return true;
    }

    function js_salvarAlteracao() {
        const ALTERACAO_CESSAO_CONTRATADO = 9;

        var dataInicio = vigenciaInicio.split('-').reverse().join('/');
        var dataFim = vigenciaFinal.split('-').reverse().join('/');

        const formData = new FormData;
        formData.append('acao', 'salvarAlteracaoContratado');
        formData.append('codigoAcordo', acordoCodigo.value);
        formData.append('contratadoAtual', codigoContratado.value);
        formData.append('novoContratado', cgmCodigo.value);
        formData.append('dataFim', dataFim);
        formData.append('dataInicio', dataInicio);
        formData.append('numeroAditamento', numeroAditamento.value);
        formData.append('oCboTipoAlteracao', oCboTipoAlteracao.value);
        formData.append('justificativa', encodeURIComponent(tagString(justificativa.value)));
        formData.append('tipoAditamento', ALTERACAO_CESSAO_CONTRATADO);

        HttpClient.post('con4_alteracessacontratado.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }
            alert("Alteração ou cessão de contratado realizado com sucesso. Um evento Termo Aditivo foi criado automaticamente para esta ação.");
            js_limpaCampos();
        });
    }

    new DBLookUp(acordoAncora, acordoCodigo, acordoObjeto, {
        'sArquivo': 'func_acordo.php',
        'sLabel': 'Pesquisar Acordos',
        'sObjetoLookUp': "db_iframe_acordo",
        'fCallBack' : js_buscaContratado
    });

    new DBLookUp(cgmAncora, cgmCodigo, cgmDescricao, {
        'sArquivo': 'func_nome.php',
        'sLabel': 'Pesquisar Cgm',
        'sObjetoLookUp': "db_iframe_cgm",
        'aCamposAdicionais': ['z01_cgccpf'],
        'oCampoDocumento': documentoNovo,
        'fCallBack': function (param1, param2, documento) {
            const formData = new FormData();
            formData.append('acao', 'buscarEstrangeiro')
            formData.append('cgmCodigo', cgmCodigo.value)

            HttpClient.post('con4_alteracessacontratado.RPC.php', {body: formData}).then(response => {
                if (response.erro) {
                    alert(response.mensagem);
                    return;
                }
                documentoNovo.value = formatadocumento(documento)
                if (response.documentoEstrangeiro !== '') {
                    documentoNovo.value = response.documentoEstrangeiro
                }
            });
        }
    });

    function formatadocumento(documento) {
        if (documento.length === 14) {
            documento = documento.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
        } else {
            documento = documento.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }
        return documento;
    }

    function callbackAlteracoes () {
        const formData = new FormData();
        formData.append('acao', 'buscarUltimaAlteracao');
        formData.append('acordoCodigo', acordoCodigo.value);
        tabelaAlteracoes.bootstrapTable('removeAll');
        HttpClient.post('con4_alteracessacontratado.RPC.php', {body: formData}).then(response => {
            if (response.alteracoes !== '') {
                let data              = [];
                data.push(response.alteracoes);
                tabelaAlteracoes.bootstrapTable('load', data);
            }
        });
    }

    jQuery(document).ready(jQuery => {

        window.operateEvents = {
            'click .excluir': (e, d, data) => {
                if (!confirm('Confirma a exclusão do registro?' +
                    ' Esta ação excluirá também o evento criado de' +
                    ' forma automática a partir da inclusão do aditamento')) {
                    return false;
                }

                let formData = new FormData();

                formData.append('acao', 'exclusaoAditamentoContratado');
                formData.append('sequencialAlteracao', data.ac60_sequencial);
                HttpClient.post('con4_alteracessacontratado.RPC.php', {body: formData}).then(response => {
                    alert(response.mensagem);
                    if(response.erro) {
                        return;
                    }

                    js_buscaContratado();
                    tabelaAlteracoes.bootstrapTable('refresh');
                })
            }
        };
        const colunas = [
            {
                field: 'ordem',
                visible: false
            },
            {
                field: 'nomeNovo',
                visible: false
            },
            {
                field: 'nomeAnterior',
                visible: false
            },
            {
                field: 'posicao',
                title: 'Posição',
                halign: 'center',
                align: 'center',
                formatter: (valor, row, index) => {
                    return `${row.ac60_posicao}`;
                }
            },
            {
                field: 'acordo',
                title: 'Contrato',
                halign: 'center',
                align: 'center',
                formatter: (valor, row, index) => {
                    return `${row.numeroAcordo}`;
                }
            },
            {
                field: 'posicaoAcordo',
                title: 'Nº Termo',
                halign: 'center',
                align: 'center',
                formatter: (valor, row, index) => {
                    return `${row.posicaoAcordo}`;
                }
            },
            {
                field: 'posicao',
                title: 'CGM Anterior',
                halign: 'center',
                align: 'center',
                formatter: (valor, row, index) => {
                    return `${row.ac60_anterior}`;
                }
            },
            {
                field: 'novo',
                title: 'CGM Atual',
                halign: 'center',
                align: 'center',
                formatter: (valor, row, index) => {
                    return `${row.ac60_novo}`;
                }
            },
            {
                field: 'acao',
                title: 'Ações',
                halign: 'center',
                align: 'center',
                formatter: (valor, data, index) => {
                    return ['<a class="excluir" href="javascript:void(0)" title="Excluir">',
                        '  <i class="fas fa-trash-alt"></i>',
                        '</a>'].join('')
                },
                events:  window.operateEvents
            }
        ];

        tabelaAlteracoes.bootstrapTable({
            locale: 'pt-BR',
            height: 180,
            search: false,
            class: "table table-sm",
            columns: colunas,
            showButtonText: true,
            detailView: true,
            cache: false,
            useRowAttrFunc: true,
            reorderableRows: true,
            detailFormatter: (index, row) => {
                return `<fieldset>
                            <legend><b>Dados CGM</b></legend>
                        <p style="text-align: left;"><b>CgmAnterior</b>: ${row.ac60_anterior} - ${row.nomeAnterior}</p>
                        <p style="text-align: left;"> <b>CgmNovo</b>: ${row.ac60_novo} - ${row.nomeNovo}</p>
                        </fieldset>`;
            }
        });
    })
    btnSalvar.addEventListener('click', () => {
        if (!js_verificaCampos()) {
            return;
        }
        tabelaAlteracoes.bootstrapTable('removeAll');
        js_salvarAlteracao();
    })

</script>
</body>
</html>
