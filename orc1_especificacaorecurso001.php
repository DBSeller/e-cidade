<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form id="frmFiltros">
        <fieldset class="">
            <legend>Manutenção das especificações de Recursos</legend>
            <table class="form-container">
                <tr>
                    <td><label for="codigoEspecificacao">Especificação:</label></td>
                    <td>
                        <input type="text" id="codigoEspecificacao" name="codigoEspecificacao" class="field-size2">
                        <input type="text" id="nomeEspecificacao" name="nomeEspecificacao" class="field-size8" >
                    </td>
                </tr>
                <tr style="display: none">
                    <td><label for="estado">Estado:</label></td>
                    <td>
                        <select id="estado" name="estado">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="hidden" id="codigo" name="codigo" class="field-size2">
        <button type="button" value="salvar" id="btnSalvar" name="btnSalvar">
            <i class="fas fa-save"></i>
            Salvar
        </button>

        <button style="display: none" type="button" value="excluir" id="btnExcluir" name="btnExcluir">
            <i class="fas fa-trash-alt"></i>
            Excluir
        </button>

        <button type="button" value="pesquisar" id="btnPesquisar" name="btnPesquisar">
            <i class="fas fa-search"></i>
            Pesquisar
        </button>
    </form>
</div>
<?php
db_menu();
?>
<script language="JavaScript" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">

    const rota = '/financeiro/orcamento/cadastro/especificacao-recurso/';

    const formulario = document.getElementById('frmFiltros');
    const inputCodigo = document.getElementById('codigo');
    const inputCodigoEspecificacao = document.getElementById('codigoEspecificacao');
    const inputNomeEspecificacao = document.getElementById('nomeEspecificacao');
    const cboEstado = document.getElementById('estado');
    const btnSalvar = document.getElementById('btnSalvar');
    const btnPesquisar = document.getElementById('btnPesquisar');
    const btnExcluir = document.getElementById('btnExcluir');

    inputCodigoEspecificacao.onkeypress = (event) => {
        return js_mask(event, '0-9')
    };

    inputNomeEspecificacao.oninput = (event) => {
        js_ValidaCampos(inputNomeEspecificacao, 0, '', 'f', 't', event);
    }

    const limparFormulario = () => {
        formulario.reset();
        inputCodigo.value = '';
        btnExcluir.style.display = 'none';
    }

    btnSalvar.addEventListener('click', () => {
        if (empty(inputCodigoEspecificacao.value)) {
           alert('Informe o código da especificação.');
            return;
        }
        if (empty(inputNomeEspecificacao.value)) {
            alert('Informe a descrição da especificação.');
            return;
        }

        const formData = new FormData(formulario);
        PHPSession.appendFormData(formData);
        formData.append('acao', 'salvar');
        HttpClient.post(PHPSession.requestApi + rota + 'salvar', {body: formData}).then(response => {

            alert(response.message);
            if (response.erro) {
                return;
            }

            limparFormulario();
        });
    });

    function retornoPesquisa(id, codigo, descricao, uf) {

        inputCodigo.value = id;
        inputCodigoEspecificacao.value = codigo;
        inputNomeEspecificacao.value = descricao;
        cboEstado.value = uf;
        btnExcluir.style.display = '';
        db_iframe_especificacao.hide();
    }

    btnExcluir.addEventListener('click', () => {
        if (empty(inputCodigo.value)) {
            alert('Selecione uma especificação antes de excluir.');
            return;
        }

        const formData = new FormData(formulario);
        PHPSession.appendFormData(formData);
        formData.append('acao', 'excluir');

        HttpClient.post(PHPSession.requestApi + rota + 'excluir', {body: formData}).then(response => {

            alert(response.message);
            if (response.erro) {
                return;
            }

            limparFormulario();
        });

    })

    btnPesquisar.addEventListener('click', () => {
        let url = 'func_especificacaorecurso.php';
        url += '?funcao_js=parent.retornoPesquisa|o205_sequencial|o205_codigo|o205_descricao|o205_estado';
        js_OpenJanelaIframe('', 'db_iframe_especificacao', url, 'Pesquisa Especificações', true);
    });
</script>
