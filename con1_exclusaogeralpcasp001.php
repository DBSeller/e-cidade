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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <style>
        .form-container input[type="text"] {
            min-height: 22px;
            margin-left: 2px;
        }
    </style>
</head>
<body class="body-default">
<div class="alert alert-primary text-left" role="alert">
    Essa rotina realiza a exclusão das contas não utilizadas no sistema.
    Todas contas apresentadas não tem uso no exercício atual e podem serem excluídas.
</div>
<div class="container">
    <table class="form-container">
        <tr>
            <td >
                <label for="exercicio">Exercício:</label>
            </td>
            <td>
                <select id="exercicio" name="exercicio" rel="ignore-css"></select>
            </td>
        </tr>

        <tr>
            <td><label for="estrutural">Estrutural:</label></td>
            <td>
                <input type="text" id="estrutural" name="estrutural" class="field-size4" maxlength="15"
                       rel="ignore-css" oninput="js_ValidaCampos(this, 1, 'Estrutural', 't', 'f', event);">
            </td>
            <td style="text-align: left">
                <button type="button" id="btnBuscar" name="btnBuscar" class="btn btn-light" disabled>
                    <i class="fas fa-search"></i>
                </button>
            </td>
        </tr>
    </table>
</div>

<div class="subcontainer" style="width: 1200px">
    <fieldset id="ctnPesquisaPcasp">
        <legend>Selecione as contas para excluir</legend>
        <table id="tableContas"
               class="table table-sm"
               data-height="500"
               data-maintain-meta-data="true"
               data-show-footer="true"
               style="width: 100%;">
        </table>
    </fieldset>

    <button type="button" id="btnExcluir" name="btnExcluir" class="btn btn-light" disabled>
        <i class="fas fa-trash-alt"></i>
        Excluir
    </button>
</div>

<?php db_menu() ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/arrays.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>

<script type="text/javascript">
    $.noConflict();

    const cboExercicio = document.getElementById('exercicio');
    const inputEstrutural = document.getElementById('estrutural');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnExcluir = document.getElementById('btnExcluir');

    PHPSession.loadData().then(() => {
        btnBuscar.disabled = false;
        btnExcluir.disabled = false;
        let exercicio = Number(PHPSession.getValueSession('DB_anousu'));
        cboExercicio.add(new Option(exercicio, exercicio));
        cboExercicio.add(new Option(exercicio + 1, exercicio + 1));
    });

    const routs = {
        get: 'financeiro/contabilidade/plano-contas/exclusao-geral/pcasp',
        del: 'financeiro/contabilidade/plano-contas/exclusao-geral/pcasp'
    };

    const table = jQuery('#tableContas');
    table.bootstrapTable({
        locale: 'pt-BR',
        clickToSelect: true,
        search: true,
        columns: [
            {
                field: 'check',
                checkbox: true,
                align: 'center',
                valign: 'middle',
                sortable: true
            },
            {
                "title": "Conta",
                "field": 'c60_estrut',
                "align": 'center',
                "valign": 'middle',
                "width": "200",
                footerFormatter: () => {
                    return 'Total de registros';
                }
            },
            {
                "title": "Reduzido",
                "field": 'c61_reduz',
                "align": 'center',
                "valign": 'middle',
                "width": "200"
            },
            {
                "title": "Nome",
                "field": 'c60_descr',
                "align": 'left',
                "valign": 'middle',
                footerFormatter: (data) => {
                    return data.length;
                }
            }
        ]
    });

    btnBuscar.addEventListener('click', () => {
        if (inputEstrutural.value === '') {
            alert("Informe ao menos o primeiro dígito do estrutural.");
            return;
        }

        let exercicio = cboExercicio.value;
        HttpClient.defaultOptions.reportMessage = 'Buscando contas para excluir, aguarde.';
        HttpClient.get(`${PHPSession.requestApi}/${routs.get}/${inputEstrutural.value}/${exercicio}`).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            table.bootstrapTable('load', response.data);
        });
    });

    btnExcluir.addEventListener('click', () => {
        // filtra os dados necessários para reduzir tamanho da requisição
        const contas = table.bootstrapTable('getSelections').map(conta => {
            return {
                c60_codcon: conta.c60_codcon,
                c60_anousu: conta.c60_anousu,
                c60_estrut: conta.c60_estrut,
                c61_reduz: conta.c61_reduz,
            }
        });

        if (contas.length === 0) {
            alert('Selecione ao menos uma conta para excluir.');
            return;
        }

        const formData = new FormData;
        formData.append('contas', JSON.stringify(contas))
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.del}`, {
            body: formData,
            reportMessage: 'Aguarde, removendo contas.'
        }).then(response => {
            let message = response.message

            // if (response.data?.logs != undefined) {
            //     message += `\n${response.data.logs}`;
            // }
            alert(message);

            table.bootstrapTable('load', []);
        });
    });
</script>
