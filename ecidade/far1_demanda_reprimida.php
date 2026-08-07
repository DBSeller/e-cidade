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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('dbforms/db_funcoes.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
</head>
<body>
<?php validaDepartamentoLogado('unidade'); ?>
<div id="alert" class="alert-info" role="alert" style="text-align: center;" hidden></div>
<div class="container">
    <fieldset>
        <legend>Lançar Medicamento</legend>
        <input id="id" hidden>
        <table class="form-container">
            <tr>
                <tr>
                    <td>
                        <label id="paciente">Paciente:</label>
                    </td>
                    <td>
                        <input type="text" id="z01_i_cgsund" class="field-size2">
                        <input type="text" id="z01_v_nome" class="readonly field-size8" readonly>
                    </td>
                </tr>
            </tr>
            <tr>
                <td>
                    <label id="medicamento">Medicamento:</label>
                </td>
                <td>
                    <input id="fa01_i_codigo" type="text" class="field-size2">
                    <input id="m60_descr" type="text" class="field-size8">
                </td>
            </tr>
            <tr>
                <td>
                    <label>Quantidade:</label>
                </td>
                <td>
                    <input id="quantidade" type="text" class="field-size2">
                </td>
            </tr>
            <tr>
                <td>
                    <label>Observações:</label>
                </td>
                <td>
                    <textarea id="observacoes" rows="4" maxlength="500" style="min-width: 428px; max-width: 428px;"></textarea>
                </td>
            </tr>
        </table>
        </fieldset>
        <button onclick="salvar();">
            <i class="fas fa-save"></i>
            Salvar
        </button>
        <button onclick="limparCampos();">
            <i class="fas fa-file"></i>
            Novo
        </button>
        <button id="btnFechar" onclick="parent.db_iframe_demandareprimida.hide();" hidden>
            <i class="fas fa-times-circle"></i>
            Fechar
        </button>
    </div>
</div>
<br>
<div class="subcontainer">
    <fieldset>
        <legend>Demandas do Paciente</legend>
        <table id="table-demanda-reprimida" class="table table-sm"></table>
    </fieldset>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script>
$.noConflict();
const routes = {
    salvar: 'saude/farmacia/cadastro/demanda-reprimida/save',
    apagar: 'saude/farmacia/cadastro/demanda-reprimida/delete',
    getByPaciente: 'saude/farmacia/consulta/demanda-reprimida/by-paciente',
    estoqueMedicamento: 'saude/farmacia/consulta/medicamento/estoque',
    autoCompletePaciente: 'sau4_pesquisacgs.RPC.php?action',
    autoCompleteMedicamento: 'far4_retirada_autonomeRPC.php?tipo=1'
};

const divAlert = document.getElementById('alert');
const inputId = document.getElementById('id');

const paciente = {
    ancora: document.getElementById('paciente'),
    inputId: document.getElementById('z01_i_cgsund'),
    inputNome: document.getElementById('z01_v_nome')
};
const medicamento = {
    ancora: document.getElementById('medicamento'),
    inputId: document.getElementById('fa01_i_codigo'),
    inputDescricao: document.getElementById('m60_descr')
};
const inputQuantidade = document.getElementById('quantidade');
const txtObservacoes = document.getElementById('observacoes');

const btnFechar = document.getElementById('btnFechar');

const table = jQuery('#table-demanda-reprimida');

const lookUpPaciente = new DBLookUp(paciente.ancora, paciente.inputId, paciente.inputNome, {
    'sArquivo': 'func_cgs_und.php',
    'sObjetoLookUp': 'db_iframe_cgs',
    'sLabel': 'Pesquisar Pacientes',
    'fCallBack': desabilitaPaciente
});

paciente.inputNome.classList.remove('readonly');
paciente.inputNome.readOnly = false;

const lookUpMedicamento = new DBLookUp(medicamento.ancora, medicamento.inputId, medicamento.inputDescricao, {
    'sArquivo': 'func_far_matersaude.php',
    'sObjetoLookUp': 'db_iframe_far_matersaude',
    'sLabel': 'Pesquisar Medicamentos',
    'fCallBack': consultarSaldo,
    "aParametrosAdicionais": ['lancador']
});

medicamento.inputDescricao.classList.remove('readonly');
medicamento.inputDescricao.readOnly = false;

const pacienteAutoComplete = new dbAutoComplete(paciente.inputNome, routes.autoCompletePaciente);
pacienteAutoComplete.setTxtFieldId(paciente.inputId);
pacienteAutoComplete.show();
pacienteAutoComplete.setCallBackFunction((id, label) => {
    if (id == '') {
        alert('Paciente inválido.');
        paciente.inputNome.value = '';
        return false;
    }

    paciente.inputId.value = id;
    paciente.inputNome.value = label;
    desabilitaPaciente();
});

const medicamentoAutoComplete = new dbAutoComplete(medicamento.inputDescricao, routes.autoCompleteMedicamento);
medicamentoAutoComplete.setTxtFieldId(medicamento.inputId);
medicamentoAutoComplete.show();
medicamentoAutoComplete.setCallBackFunction((id, label) => {
    if (id == '') {
        alert('Medicamento inválido.');
        medicamento.inputDescricao.value = '';
        return;
    }

    medicamento.inputId.value = id;
    medicamento.inputId.dispatchEvent(new Event('change'));
});

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

window.onload = () => {
    if (urlParams.has('paciente')) {
        paciente.inputId.value = urlParams.get('paciente');
        paciente.inputId.dispatchEvent(new Event('change'));
        desabilitaPaciente();
        btnFechar.hidden = false;
    }

    if (urlParams.has('medicamento')) {
        medicamento.inputId.value = urlParams.get('medicamento');
        medicamento.inputId.dispatchEvent(new Event('change'));
    }
}

paciente.inputId.addEventListener('change', () => {
    if (paciente.inputId.value == '') {
        paciente.inputNome.value = '';
        limparCampos();

        return;
    }
    buscarDados();
});

jQuery(document).ready(() => {
    const actions = {
        'click .alterar': (e, d, data) => {
            inputId.value = data.id;
            paciente.inputId.value = data.idPaciente;
            paciente.inputNome.value = data.nomePaciente;
            desabilitaPaciente();
            medicamento.inputId.value = data.idMedicamento;
            medicamento.inputDescricao.value = data.descricaoMedicamento;
            desabilitaMedicamento();
            inputQuantidade.value = data.quantidade;
            txtObservacoes.value = data.observacoes;

            if (divAlert.firstChild) {
                divAlert.removeChild(divAlert.firstChild);
            }

            let p = document.createElement('p');
            p.innerHTML = `Editando registro de código ${data.id}`;
            divAlert.appendChild(p);
            divAlert.hidden = false;
        },
        'click .apagar': (e, d, data) => {
            if (!confirm('Confirma a exclusão do registro?')) {
                return false;
            }
            if (inputId.value == data.id) {
                limparCampos(true);
            }
            
            const formData = new FormData();

            formData.append('id', data.id);

            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routes.apagar}`, {body: formData}).then(response => {
                alert(response.message);
                if(response.error) {
                    return;
                }
                
                buscarDados();
            });
        }
    };

    table.bootstrapTable({
        height: 350,
        search: true,
        detailView: true,
        columns: [
            {
                field: 'id',
                title: 'Código',
                halign: 'center',
                align: 'center',
                width: 60
            },
            {
                field: 'dataHora', 
                title: 'Data',
                halign: 'center',
                align: 'center',
                width: 120,
                formatter: value => {
                    return `<strong>${value}</strong>`;
                }
            },
            {
                field: 'unidade',
                title: 'Unidade',
                halign: 'center',
                align: 'left',
                width: 380,
                formatter: (a, data) => {
                    return `${data.idUnidade} - ${data.descricaoUnidade}`;
                }
            },
            {
                field: 'medicamento',
                title: 'Medicamento',
                halign: 'center',
                align: 'left',
                width: 380,
                formatter: (a, data) => {
                    return `${data.idMedicamento} - ${data.descricaoMedicamento}`;
                }
            },
            {
                field: 'quantidade',
                title: 'Quantidade',
                halign: 'center',
                align: 'center',
                width: 100
            },
            {
                field: 'loginUsuario',
                title: 'Usuário',
                halign: 'center',
                align: 'center',
                width: 100
            },
            {
                field: 'acoes',
                title: 'Ações',
                halign: 'center',
                align: 'center',
                width: 100,
                formatter: () => {
                    let btnAlterar = '<a class="alterar"><i class="fas fa-edit"></i></i></a>';
                    let btnApagar = '<a class="apagar"><i class="fas fa-trash-alt"></i></a>';
                    return `${btnAlterar}&nbsp;${btnApagar}`
                },
                events: actions
            }
        ],
        detailFormatter: (index, row) => {
            return `
                <div style="width: 1260px; word-wrap: break-word;">
                    <p style="text-align: left; white-space: pre-line;"><b>Observações</b>:\n ${row.observacoes}</p>
                </div>
            `;
        }
    });
});

function desabilitaPaciente() {
    paciente.inputNome.classList.add('readonly');
    paciente.inputNome.readOnly = true;
    lookUpPaciente.desabilitar();
}

function desabilitaMedicamento() {
    medicamento.inputDescricao.classList.add('readonly');
    medicamento.inputDescricao.readOnly = true;
    lookUpMedicamento.desabilitar();
} 

function salvar() {
    if (!validaCampos()) {
        return;
    }

    const formData = new FormData();

    if (inputId.value != '') {
        formData.append('id', inputId.value);
    }

    formData.append('paciente', paciente.inputId.value);
    formData.append('medicamento', medicamento.inputId.value);
    formData.append('quantidade', parseInt(inputQuantidade.value));
    formData.append('observacoes', txtObservacoes.value);

    PHPSession.appendFormData(formData);

    HttpClient.post(`${PHPSession.requestApi}/${routes.salvar}`, {body: formData}).then(response => {
        alert(response.message);
        if (response.error) {
            return;
        }

        buscarDados();
        limparCampos(true);
    });
}

function validaCampos() {
    if (medicamento.inputId.value == '') {
        alert('Informe o medicamento!');
        return false;
    }
    if (inputQuantidade.value == '') {
        alert('Informe a quantidade!');
        return false;
    }
    if (parseInt(inputQuantidade.value) != Number(inputQuantidade.value)) {
        alert('Informe uma quantidade válida!');
        return false;
    }

    return true;
}

async function buscarDados() {
    await PHPSession.loadData();

    HttpClient.get(`${PHPSession.requestApi}/${routes.getByPaciente}/${paciente.inputId.value}`).then(response => {
        if (response.error) {
            alert(response.message);
            return;
        }

        table.bootstrapTable('load', response.data);
    });
}

function limparCampos(mantemPaciente = false) {
    if (!urlParams.has('paciente') && !mantemPaciente) {
        paciente.inputId.value = ''
        paciente.inputNome.value = '';
        lookUpPaciente.habilitar();
        paciente.inputNome.classList.remove('readonly');
        paciente.inputNome.readOnly = false;
        table.bootstrapTable('removeAll');
    }
    medicamento.inputId.value = '';
    medicamento.inputDescricao.value = '';
    lookUpMedicamento.habilitar();
    medicamento.inputDescricao.classList.remove('readonly');
    medicamento.inputDescricao.readOnly = false;
    inputQuantidade.value = '';

    txtObservacoes.value = '';

    divAlert.hidden = true;
    inputId.value = '';
}

function consultarSaldo() {
    if (medicamento.inputId.value == '') {
        return;
    }

    const formData = new FormData();

    formData.append('idMedicamento', medicamento.inputId.value);

    PHPSession.appendFormData(formData);

    HttpClient.post(`${PHPSession.requestApi}/${routes.estoqueMedicamento}`, {body: formData}).then(response => {
        if (response.error) {
            alert(response.message);
        }

        if (response.data > 0) {
            let descricao = `${medicamento.inputId.value} - ${medicamento.inputDescricao.value}`;
            let saldo = response.data;
            alert(`O medicamento informado possui saldo no estoque!\n${descricao}\nSaldo: ${saldo}`);
            medicamento.inputId.value = '';
            medicamento.inputDescricao.value = '';
        }
    });
}
</script>
</body>